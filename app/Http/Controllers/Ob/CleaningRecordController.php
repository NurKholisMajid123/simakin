<?php
namespace App\Http\Controllers\Ob;

use App\Http\Controllers\Controller;
use App\Models\CleaningRecord;
use App\Models\CleaningRecordTask;
use App\Models\RoomAssignment;
use App\Models\Ruangan;
use App\Models\Tugas;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\TaskCompletedNotification;
use App\Notifications\RoomCleaningCompletedNotification;

class CleaningRecordController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->toDateString();
        
        // Get only assigned rooms for current OB
        $assignedRoomIds = RoomAssignment::where('user_id', Auth::id())
            ->where('assigned_date', $today)
            ->pluck('room_id')
            ->toArray();
        
        // Only show assigned rooms (no fallback)
        $ruangans = Ruangan::whereIn('id', $assignedRoomIds)
            ->with(['cleaningRecords' => function($query) use ($today) {
                $query->whereDate('date', $today);
            }])->get();
        
        // Get all tasks
        $allTasks = Tugas::all();
        
        // Only process if user has assignments
        if ($ruangans->isNotEmpty()) {
            // Ensure cleaning records exist for today (only for assigned rooms)
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
        }
        
        // Reload with fresh data (only assigned rooms)
        $assignedRoomIds = RoomAssignment::where('user_id', Auth::id())
            ->where('assigned_date', $today)
            ->pluck('room_id')
            ->toArray();
            
        $ruangans = Ruangan::whereIn('id', $assignedRoomIds)
            ->with([
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
        
        // Check if user is assigned to this room today
        $today = Carbon::today()->toDateString();
        $isAssigned = RoomAssignment::where('user_id', Auth::id())
            ->where('room_id', $request->room_id)
            ->where('assigned_date', $today)
            ->exists();
            
        if (!$isAssigned) {
            return redirect()->route('ob.cleaning-records.index')
                ->with('error', 'Anda tidak dialokasikan untuk ruangan ini.');
        }
        
        $cleaningRecord = CleaningRecord::firstOrCreate(
            [
                'room_id' => $request->room_id,
                'date' => $today,
            ],
            [
                'user_id' => Auth::id(),
            ]
        );
        
        $allTasks = Tugas::all();
        $submittedTaskIds = $request->input('tasks', []);
        
        // Track berapa banyak tugas yang diselesaikan
        $completedTasksCount = 0;
        $previousCompletedCount = $cleaningRecord->tasks()->where('is_done', true)->count();
        
        foreach ($allTasks as $task) {
            $isDone = isset($submittedTaskIds[$task->id]);
            
            $updateData = [
                'is_done' => $isDone,
                'note' => null,
            ];
            
            if ($isDone) {
                $updateData['completed_by_user_id'] = Auth::id();
                $updateData['completed_at'] = Carbon::now();
                $completedTasksCount++;
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
        
        // Reload cleaning record dengan tasks terbaru
        $cleaningRecord = $cleaningRecord->fresh(['tasks', 'ruangan']);
        
        // Hitung total tasks dan yang sudah selesai
        $totalTasks = $allTasks->count();
        $currentCompletedCount = $cleaningRecord->tasks()->where('is_done', true)->count();
        
        // Update status room_cleaned jika semua tugas selesai
        if ($currentCompletedCount === $totalTasks) {
            $cleaningRecord->update(['room_cleaned' => true]);
        } else {
            $cleaningRecord->update(['room_cleaned' => false]);
        }
        
        // Kirim notifikasi ke admin jika ada progress
        if ($currentCompletedCount > 0) {
            $admins = User::where('role', 'admin')->get();
            
            // Kirim notifikasi task completed jika ada perubahan
            if ($currentCompletedCount != $previousCompletedCount) {
                foreach ($admins as $admin) {
                    $admin->notify(new TaskCompletedNotification(
                        $cleaningRecord,
                        Auth::user(),
                        $currentCompletedCount
                    ));
                }
            }
            
            // Kirim notifikasi khusus jika semua tugas selesai
            if ($currentCompletedCount === $totalTasks && $previousCompletedCount < $totalTasks) {
                foreach ($admins as $admin) {
                    $admin->notify(new RoomCleaningCompletedNotification(
                        $cleaningRecord,
                        Auth::user()
                    ));
                }
            }
        }
        
        return redirect()->route('ob.cleaning-records.index')
            ->with('success', 'Cek kebersihan berhasil disimpan.');
    }

    /**
     * Show detail cleaning record
     */
    public function show($id)
    {
        $cleaningRecord = CleaningRecord::with(['ruangan', 'tasks.task', 'tasks.completedBy'])
            ->findOrFail($id);
        
        // Mark notification as read jika ada
        $notification = Auth::user()->notifications()
            ->where('data->cleaning_record_id', $id)
            ->whereNull('read_at')
            ->first();
        
        if ($notification) {
            $notification->markAsRead();
        }
        
        return view('ob.cleaning_records.show', compact('cleaningRecord'));
    }

    /**
     * Mark notification as read
     */
    public function markNotificationRead($notificationId)
    {
        $notification = Auth::user()->notifications()->find($notificationId);
        
        if ($notification) {
            $notification->markAsRead();
        }
        
        return back();
    }
}