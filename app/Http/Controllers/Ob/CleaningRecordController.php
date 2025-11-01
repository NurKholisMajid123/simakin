<?php

namespace App\Http\Controllers\Ob;

use App\Http\Controllers\Controller;
use App\Models\CleaningRecord;
use App\Models\CleaningRecordTask;
use App\Models\Ruangan;
use App\Models\Tugas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CleaningRecordController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->toDateString();

        // Get all rooms with today's cleaning records
        $ruangans = Ruangan::with(['cleaningRecords' => function($query) use ($today) {
            $query->whereDate('date', $today);
        }])->get();

        // Get all tasks
        $allTasks = Tugas::all();

        // Ensure cleaning records exist for today
        foreach ($ruangans as $ruangan) {
            $cleaningRecord = $ruangan->cleaningRecords->first();

            if (!$cleaningRecord) {
                // Create cleaning record for today
                $cleaningRecord = CleaningRecord::create([
                    'user_id' => Auth::id(),
                    'room_id' => $ruangan->id,
                    'date' => $today
                ]);
            }

            // Ensure all tasks are present
            $existingTaskIds = $cleaningRecord->tasks->pluck('task_id')->toArray();
            
            foreach ($allTasks as $task) {
                if (!in_array($task->id, $existingTaskIds)) {
                    CleaningRecordTask::create([
                        'cleaning_record_id' => $cleaningRecord->id,
                        'task_id' => $task->id,
                        'is_done' => false,
                    ]);
                }
            }
        }

        // Reload with fresh data
        $ruangans = Ruangan::with([
            'cleaningRecords' => function($query) use ($today) {
                $query->whereDate('date', $today);
            },
            'cleaningRecords.tasks.task'
        ])->get();

        return view('ob.cleaning_records.index', compact('ruangans', 'allTasks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:ruangan,id',
        ]);

        $cleaningRecord = CleaningRecord::firstOrCreate(
            [
                'room_id' => $request->room_id,
                'date' => Carbon::today()->toDateString(),
            ],
            [
                'user_id' => Auth::id(),
            ]
        );

        $allTasks = Tugas::all();
        $submittedTaskIds = $request->input('tasks', []);

        foreach ($allTasks as $task) {
            $isDone = isset($submittedTaskIds[$task->id]);

            $updateData = [
                'is_done' => $isDone,
                'note' => null,
            ];

            if ($isDone) {
                $updateData['completed_by_user_id'] = Auth::id();
                $updateData['completed_at'] = Carbon::now();
            } else {
                $updateData['completed_by_user_id'] = null;
                $updateData['completed_at'] = null;
            }

            CleaningRecordTask::updateOrCreate(
                [
                    'cleaning_record_id' => $cleaningRecord->id,
                    'task_id' => $task->id,
                ],
                $updateData
            );
        }

        return redirect()->route('ob.cleaning-records.index')
            ->with('success', 'Cek kebersihan berhasil disimpan.');
    }
}