<?php
// app/Console/Commands/SendCleaningReminders.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CleaningRecord;
use App\Notifications\CleaningReminderNotification;
use Carbon\Carbon;

class SendCleaningReminders extends Command
{
    protected $signature = 'cleaning:remind';
    protected $description = 'Kirim reminder untuk tugas yang belum selesai';

    public function handle()
    {
        $today = Carbon::today();
        
        // Ambil cleaning records hari ini yang belum selesai
        $records = CleaningRecord::where('date', $today)
            ->where('room_cleaned', false)
            ->with(['user', 'tasks'])
            ->get();

        foreach ($records as $record) {
            $pendingTasks = $record->tasks()->where('is_done', false)->count();
            
            if ($pendingTasks > 0) {
                $record->user->notify(new CleaningReminderNotification($record, $pendingTasks));
            }
        }

        $this->info("Reminder dikirim ke {$records->count()} OB");
    }
}