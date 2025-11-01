<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use App\Models\CleaningRecord;
use App\Models\CleaningRecordTask;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TugasController extends Controller
{
    public function index()
    {
        $tugas = Tugas::latest()->get();
        return view('admin.tugas.index', compact('tugas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $task = Tugas::create($validated);

        // Sync tugas baru ke semua cleaning records hari ini
        $this->syncTaskToTodayRecords($task);

        return redirect()->route('admin.tugas.index')
            ->with('success', 'Tugas berhasil ditambahkan.');
    }

    public function update(Request $request, Tugas $tugas)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $tugas->update($validated);

        return redirect()->route('admin.tugas.index')
            ->with('success', 'Tugas berhasil diperbarui.');
    }

    public function destroy(Tugas $tugas)
    {
        $tugas->delete();

        return redirect()->route('admin.tugas.index')
            ->with('success', 'Tugas berhasil dihapus.');
    }

    private function syncTaskToTodayRecords($task)
    {
        $today = Carbon::today();
        $todayRecords = CleaningRecord::whereDate('date', $today)->get();

        foreach ($todayRecords as $record) {
            CleaningRecordTask::firstOrCreate([
                'cleaning_record_id' => $record->id,
                'task_id' => $task->id,
            ], [
                'is_done' => false,
            ]);
        }
    }
}