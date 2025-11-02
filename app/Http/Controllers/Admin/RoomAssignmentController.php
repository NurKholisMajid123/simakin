<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoomAssignment;
use App\Models\Ruangan;
use App\Models\User;
use App\Notifications\RoomAssignedNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomAssignmentController extends Controller
{
    public function index()
    {
        $date = request()->get('date', Carbon::today()->toDateString());
        $today = Carbon::today()->toDateString();
        
        $assignments = RoomAssignment::with(['user', 'ruangan'])
            ->where('assigned_date', $date)
            ->get()
            ->groupBy('user_id');

        $users = User::where('role', 'ob')->get();
        $ruangans = Ruangan::all();

        return view('admin.room_assignments.index', compact('assignments', 'users', 'ruangans', 'today', 'date'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'room_ids' => 'required|array',
            'room_ids.*' => 'exists:ruangan,id',
            'assigned_date' => 'required|date',
        ]);

        $userId = $request->user_id;
        $roomIds = $request->room_ids;
        $assignedDate = $request->assigned_date;
        $assignedBy = Auth::user();

        // Get existing assignments for comparison
        $existingAssignments = RoomAssignment::where('user_id', $userId)
            ->where('assigned_date', $assignedDate)
            ->pluck('room_id')
            ->toArray();

        // Delete existing assignments for this user and date
        RoomAssignment::where('user_id', $userId)
            ->where('assigned_date', $assignedDate)
            ->delete();

        // Create new assignments and send notifications
        $createdAssignments = [];
        foreach ($roomIds as $roomId) {
            $assignment = RoomAssignment::create([
                'user_id' => $userId,
                'room_id' => $roomId,
                'assigned_date' => $assignedDate,
            ]);
            $createdAssignments[] = $assignment;
        }

        // Send notification to OB (only for new assignments)
        $user = User::find($userId);
        $newAssignmentsCount = 0;
        foreach ($createdAssignments as $assignment) {
            if (!in_array($assignment->room_id, $existingAssignments)) {
                $user->notify(new RoomAssignedNotification($assignment, $assignedBy));
                $newAssignmentsCount++;
            }
        }

        // Determine action and message
        $isUpdate = count($existingAssignments) > 0;
        $action = $isUpdate ? 'diperbarui' : 'disimpan';
        
        // Create detailed success message
        $message = "Alokasi ruangan berhasil {$action}.";
        if ($isUpdate) {
            $totalRooms = count($roomIds);
            $removedRooms = count(array_diff($existingAssignments, $roomIds));
            $addedRooms = $newAssignmentsCount;
            
            if ($removedRooms > 0 && $addedRooms > 0) {
                $message .= " {$addedRooms} ruangan ditambahkan, {$removedRooms} ruangan dihapus.";
            } elseif ($removedRooms > 0) {
                $message .= " {$removedRooms} ruangan dihapus.";
            } elseif ($addedRooms > 0) {
                $message .= " {$addedRooms} ruangan baru ditambahkan.";
            }
            
            if ($newAssignmentsCount > 0) {
                $message .= " Notifikasi telah dikirim ke {$user->name}.";
            }
        } else {
            $message .= " Notifikasi telah dikirim ke {$user->name}.";
        }

        return redirect()->route('admin.room-assignments.index')
            ->with('success', $message);
    }

    public function destroy($id)
    {
        $assignment = RoomAssignment::findOrFail($id);
        $user = $assignment->user;
        $assignedBy = Auth::user();
        
        // Delete the assignment
        $assignment->delete();

        // Send notification about assignment removal
        $user->notify(new \App\Notifications\RoomAssignmentRemovedNotification($assignment, $assignedBy));

        return redirect()->route('admin.room-assignments.index')
            ->with('success', "Alokasi ruangan {$assignment->ruangan->name} untuk {$user->name} berhasil dihapus.");
    }
}
