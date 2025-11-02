<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use App\Models\User;
use App\Models\CleaningRecord;
use App\Models\CleaningRecordTask;
use App\Notifications\NewTaskAddedNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TugasController extends Controller
{
    public function index()
    {
        $tugas = Tugas::latest()->get();
        return view('admin.tugas.index', compact('tugas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $tugas = Tugas::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // Tambahkan tugas baru ke semua cleaning record yang ada hari ini
        $today = Carbon::today()->toDateString();
        $cleaningRecords = CleaningRecord::whereDate('date', $today)->get();

        foreach ($cleaningRecords as $record) {
            // Cek apakah tugas sudah ada untuk record ini
            $exists = CleaningRecordTask::where('cleaning_record_id', $record->id)
                ->where('task_id', $tugas->id)
                ->exists();

            if (!$exists) {
                CleaningRecordTask::create([
                    'cleaning_record_id' => $record->id,
                    'task_id' => $tugas->id,
                    'is_done' => false,
                ]);

                // Kirim notifikasi ke OB yang bertanggung jawab
                $record->user->notify(new NewTaskAddedNotification($tugas, $record->ruangan));
            }
        }

        // Kirim notifikasi ke semua OB jika tidak ada cleaning record hari ini
        if ($cleaningRecords->isEmpty()) {
            $allOBs = User::where('role', 'ob')->get();
            foreach ($allOBs as $ob) {
                $ob->notify(new NewTaskAddedNotification($tugas));
            }
        }

        return redirect()->route('admin.tugas.index')
            ->with('success', 'Tugas berhasil ditambahkan dan notifikasi dikirim ke OB.');
    }

    public function update(Request $request, Tugas $tugas)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $tugas->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.tugas.index')
            ->with('success', 'Tugas berhasil diperbarui.');
    }

    public function destroy(Tugas $tugas)
    {
        $tugas->delete();

        return redirect()->route('admin.tugas.index')
            ->with('success', 'Tugas berhasil dihapus.');
    }
}