<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ruangan;
use App\Models\User;
use App\Models\CleaningRecord;
use App\Models\Tugas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CleaningRecordsExport;
use App\Notifications\NewTaskAssignedNotification;

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

    /**
     * Assign tugas ke OB tertentu
     */
    public function assignTask(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'room_id' => 'required|exists:ruangan,id',
            'date' => 'required|date',
        ]);

        // Cek apakah sudah ada cleaning record
        $cleaningRecord = CleaningRecord::where('room_id', $request->room_id)
            ->where('date', $request->date)
            ->first();

        if ($cleaningRecord) {
            // Update user yang bertanggung jawab
            $cleaningRecord->update(['user_id' => $request->user_id]);
        } else {
            // Buat cleaning record baru
            $cleaningRecord = CleaningRecord::create([
                'user_id' => $request->user_id,
                'room_id' => $request->room_id,
                'date' => $request->date,
                'room_cleaned' => false,
            ]);

            // Buat tasks untuk cleaning record
            $tasks = Tugas::all();
            foreach ($tasks as $task) {
                \App\Models\CleaningRecordTask::create([
                    'cleaning_record_id' => $cleaningRecord->id,
                    'task_id' => $task->id,
                    'is_done' => false,
                ]);
            }

            // Kirim notifikasi ke OB yang ditugaskan
            $ob = User::find($request->user_id);
            $ob->notify(new NewTaskAssignedNotification($cleaningRecord, $tasks->count()));
        }

        return back()->with('success', 'Tugas berhasil ditugaskan ke ' . $cleaningRecord->user->name);
    }

    /**
     * Mark all notifications as read
     */
    public function markNotificationsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        
        return back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca');
    }

    public function show($id)
{
    $cleaningRecord = CleaningRecord::with(['ruangan', 'tasks.task', 'tasks.completedBy', 'user'])
        ->findOrFail($id);
    
    // Mark notification as read jika ada
    if (Auth::check()) {
        $notification = Auth::user()->notifications()
            ->where('data->cleaning_record_id', $id)
            ->whereNull('read_at')
            ->first();
        
        if ($notification) {
            $notification->markAsRead();
        }
    }
    
    return view('admin.cleaning_records.show', compact('cleaningRecord'));
}
}