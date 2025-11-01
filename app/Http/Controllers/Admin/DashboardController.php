<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CleaningRecord;
use App\Models\CleaningRecordTask;
use App\Models\Ruangan;
use App\Models\Tugas;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Ruangan yang sudah dibersihkan hari ini (semua tugas selesai)
        $roomsCleanedToday = Ruangan::whereHas('cleaningRecords', function ($query) use ($today) {
            $query->whereDate('date', $today)
                ->whereHas('tasks')
                ->whereDoesntHave('tasks', function ($subQuery) {
                    $subQuery->where('is_done', false);
                });
        })->count();

        // Tugas yang sudah dikerjakan hari ini
        $tasksDoneToday = CleaningRecordTask::where('is_done', true)
            ->whereHas('cleaningRecord', function ($q) use ($today) {
                $q->whereDate('date', $today);
            })->count();

        // Tugas yang tersisa hari ini
        $tasksRemainingToday = CleaningRecordTask::where('is_done', false)
            ->whereHas('cleaningRecord', function ($q) use ($today) {
                $q->whereDate('date', $today);
            })->count();

        // Data untuk chart
        $chartLabels = ['Ruangan Dibersihkan', 'Tugas Selesai', 'Tugas Tersisa'];
        $chartData = [$roomsCleanedToday, $tasksDoneToday, $tasksRemainingToday];

        return view('admin.dashboard', compact(
            'roomsCleanedToday',
            'tasksDoneToday',
            'tasksRemainingToday',
            'chartLabels',
            'chartData'
        ));
    }
}