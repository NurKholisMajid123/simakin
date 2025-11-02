<?php
// app/Notifications/CleaningReminderNotification.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\CleaningRecord;

class CleaningReminderNotification extends Notification
{
    use Queueable;

    protected $cleaningRecord;
    protected $pendingTasks;

    public function __construct(CleaningRecord $cleaningRecord, $pendingTasks)
    {
        $this->cleaningRecord = $cleaningRecord;
        $this->pendingTasks = $pendingTasks;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Reminder: Tugas Belum Selesai',
            'message' => "Anda masih memiliki {$this->pendingTasks} tugas yang belum selesai di {$this->cleaningRecord->ruangan->name}",
            'cleaning_record_id' => $this->cleaningRecord->id,
            'room_name' => $this->cleaningRecord->ruangan->name,
            'pending_tasks' => $this->pendingTasks,
            'date' => $this->cleaningRecord->date,
            'type' => 'reminder',
            'url' => route('ob.cleaning-records.show', $this->cleaningRecord->id)
        ];
    }
}