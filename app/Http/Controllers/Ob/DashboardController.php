<?php

namespace App\Http\Controllers\Ob;

use App\Http\Controllers\Controller;
use App\Models\CleaningRecord;
use App\Models\CleaningRecordTask;
use App\Models\Tugas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Pekerjaan yang diselesaikan oleh OB ini hari ini
        $pekerjaanSelesai = CleaningRecordTask::where('completed_by_user_id', Auth::id())
            ->whereDate('completed_at','=', $today)
            ->count();

        // Ruangan yang dibersihkan oleh OB ini hari ini
        $ruanganBersih = CleaningRecord::where('user_id', Auth::id())
            ->whereDate('date', $today)
            ->distinct('room_id')
            ->count('room_id');

        // Total tugas yang ada
        $totalTugas = Tugas::count();

        // Tugas tersisa untuk OB ini hari ini
        $tugasTersisa = CleaningRecordTask::whereHas('cleaningRecord', function($q) use ($today) {
            $q->where('user_id', Auth::id())
              ->whereDate('date', $today);
        })->where('is_done', false)->count();

        return view('ob.dashboard', compact(
            'pekerjaanSelesai',
            'ruanganBersih',
            'totalTugas',
            'tugasTersisa'
        ));
    }
}