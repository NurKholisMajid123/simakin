<?php

namespace App\Http\Controllers\Ob;

use App\Http\Controllers\Controller;
use App\Models\CleaningRecord;
use App\Models\CleaningRecordTask;
use App\Models\RoomAssignment;
use App\Models\Tugas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        
        // Get assigned rooms for today
        $assignedRoomIds = RoomAssignment::where('user_id', Auth::id())
            ->where('assigned_date', $today->toDateString())
            ->pluck('room_id')
            ->toArray();

        if (empty($assignedRoomIds)) {
            // No assignments - show empty stats
            return view('ob.dashboard', [
                'pekerjaanSelesai' => 0,
                'ruanganBersih' => 0,
                'totalTugas' => 0,
                'tugasTersisa' => 0,
                'hasAssignment' => false
            ]);
        }

        // Pekerjaan yang diselesaikan oleh OB ini hari ini (hanya untuk ruangan yang dialokasikan)
        $pekerjaanSelesai = CleaningRecordTask::where('completed_by_user_id', Auth::id())
            ->whereDate('completed_at', $today)
            ->whereHas('cleaningRecord', function($q) use ($assignedRoomIds) {
                $q->whereIn('room_id', $assignedRoomIds);
            })
            ->count();

        // Ruangan yang dibersihkan oleh OB ini hari ini (hanya yang dialokasikan)
        $ruanganBersih = CleaningRecord::where('user_id', Auth::id())
            ->whereDate('date', $today)
            ->whereIn('room_id', $assignedRoomIds)
            ->where('room_cleaned', true)
            ->distinct('room_id')
            ->count('room_id');

        // Total tugas untuk ruangan yang dialokasikan
        $totalTugas = CleaningRecordTask::whereHas('cleaningRecord', function($q) use ($today, $assignedRoomIds) {
            $q->whereDate('date', $today)
              ->whereIn('room_id', $assignedRoomIds);
        })->count();

        // Tugas tersisa untuk OB ini hari ini (hanya untuk ruangan yang dialokasikan)
        $tugasTersisa = CleaningRecordTask::whereHas('cleaningRecord', function($q) use ($today, $assignedRoomIds) {
            $q->whereDate('date', $today)
              ->whereIn('room_id', $assignedRoomIds);
        })->where('is_done', false)->count();

        return view('ob.dashboard', [
            'pekerjaanSelesai' => $pekerjaanSelesai,
            'ruanganBersih' => $ruanganBersih,
            'totalTugas' => $totalTugas,
            'tugasTersisa' => $tugasTersisa,
            'hasAssignment' => true
        ]);
    }
}