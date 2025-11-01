<?php

namespace App\Http\Controllers\Ob;

use App\Http\Controllers\Controller;
use App\Models\CleaningRecord;
use App\Models\CleaningRecordTask;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskCompletionController extends Controller
{
    public function toggleTaskComplete(Request $request, $recordId, $taskId)
    {
        try {
            $task = CleaningRecordTask::where('cleaning_record_id', $recordId)
                ->where('task_id', $taskId)
                ->firstOrFail();

            $task->is_done = !$task->is_done;
            
            if ($task->is_done) {
                $task->completed_by_user_id = Auth::id();
                $task->completed_at = Carbon::now();
            } else {
                $task->completed_by_user_id = null;
                $task->completed_at = null;
            }
            
            $task->save();

            return response()->json([
                'status' => 'success',
                'is_done' => $task->is_done,
                'completed_by' => $task->is_done ? Auth::user()->name : null,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal toggle tugas'
            ], 500);
        }
    }

    public function toggleRoomCleaned(Request $request, $id)
    {
        try {
            $record = CleaningRecord::findOrFail($id);
            $record->room_cleaned = !$record->room_cleaned;
            $record->save();

            return response()->json([
                'status' => 'success',
                'room_cleaned' => $record->room_cleaned
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal toggle status ruangan'
            ], 500);
        }
    }
}