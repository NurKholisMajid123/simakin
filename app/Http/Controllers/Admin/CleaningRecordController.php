<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ruangan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CleaningRecordsExport;

class CleaningRecordController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date') 
            ? Carbon::parse($request->input('start_date')) 
            : Carbon::today();
        
        $endDate = $request->input('end_date') 
            ? Carbon::parse($request->input('end_date')) 
            : Carbon::today();

        $ruangans = Ruangan::with(['cleaningRecords' => function($query) use ($startDate, $endDate) {
            $query->whereBetween('date', [$startDate->startOfDay(), $endDate->endOfDay()])
                  ->orderBy('date', 'desc');
        }, 'cleaningRecords.tasks.task', 'cleaningRecords.tasks.completedBy', 'cleaningRecords.user'])
            ->get();

        return view('admin.cleaning_records.index', compact('ruangans', 'startDate', 'endDate'));
    }

    public function export(Request $request)
    {
        $startDate = $request->input('start_date') 
            ? Carbon::parse($request->input('start_date')) 
            : Carbon::today();
        
        $endDate = $request->input('end_date') 
            ? Carbon::parse($request->input('end_date')) 
            : Carbon::today();

        $ruangans = Ruangan::with(['cleaningRecords' => function($query) use ($startDate, $endDate) {
            $query->whereBetween('date', [$startDate->startOfDay(), $endDate->endOfDay()])
                  ->orderBy('date', 'desc');
        }, 'cleaningRecords.tasks.task', 'cleaningRecords.tasks.completedBy', 'cleaningRecords.user'])
            ->get();

        $filename = 'Laporan_Kebersihan_' . $startDate->format('d-m-Y') . '_sampai_' . $endDate->format('d-m-Y') . '.xlsx';

        return Excel::download(
            new CleaningRecordsExport($ruangans, $startDate, $endDate), 
            $filename
        );
    }
}