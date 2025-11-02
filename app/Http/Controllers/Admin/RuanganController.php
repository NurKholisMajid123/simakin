<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ruangan;
use App\Models\User;
use App\Models\Tugas;
use App\Models\CleaningRecord;
use App\Models\CleaningRecordTask;
use App\Notifications\NewRoomAddedNotification;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RuanganController extends Controller
{
    public function index()
    {
        $ruangans = Ruangan::latest()->get();
        return view('admin.ruangan.index', compact('ruangans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Buat ruangan baru
        $ruangan = Ruangan::create($validated);

        // Ambil semua tugas yang ada
        $allTasks = Tugas::all();
        
        // Buat cleaning record untuk hari ini (tanpa assign ke OB tertentu dulu)
        $today = Carbon::today()->toDateString();
        
        // Cek apakah sudah ada cleaning record untuk ruangan ini hari ini
        $cleaningRecord = CleaningRecord::where('room_id', $ruangan->id)
            ->where('date', $today)
            ->first();

        // Jika belum ada, buat cleaning record baru
        if (!$cleaningRecord) {
            // Pilih OB pertama sebagai default (atau bisa null)
            $defaultOB = User::where('role', 'ob')->first();
            
            $cleaningRecord = CleaningRecord::create([
                'user_id' => $defaultOB ? $defaultOB->id : null,
                'room_id' => $ruangan->id,
                'date' => $today,
                'room_cleaned' => false,
            ]);

            // Buat tasks untuk cleaning record ini
            foreach ($allTasks as $task) {
                CleaningRecordTask::create([
                    'cleaning_record_id' => $cleaningRecord->id,
                    'task_id' => $task->id,
                    'is_done' => false,
                ]);
            }
        }

        // Kirim notifikasi ke semua OB
        $allOBs = User::where('role', 'ob')->get();
        foreach ($allOBs as $ob) {
            $ob->notify(new NewRoomAddedNotification($ruangan, $allTasks->count()));
        }

        return redirect()->route('admin.ruangan.index')
            ->with('success', 'Ruangan berhasil ditambahkan dan notifikasi telah dikirim ke semua OB.');
    }

    public function update(Request $request, Ruangan $ruangan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $ruangan->update($validated);

        return redirect()->route('admin.ruangan.index')
            ->with('success', 'Ruangan berhasil diperbarui.');
    }

    public function destroy(Ruangan $ruangan)
    {
        $ruangan->delete();

        return redirect()->route('admin.ruangan.index')
            ->with('success', 'Ruangan berhasil dihapus.');
    }
}