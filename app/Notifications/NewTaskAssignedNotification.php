<?php
// app/Notifications/NewTaskAssignedNotification.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\CleaningRecord;

class NewTaskAssignedNotification extends Notification
{
    use Queueable;

    protected $cleaningRecord;
    protected $taskCount;

    public function __construct(CleaningRecord $cleaningRecord, $taskCount)
    {
        $this->cleaningRecord = $cleaningRecord;
        $this->taskCount = $taskCount;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Tugas Baru',
            'message' => "Anda mendapat {$this->taskCount} tugas baru untuk membersihkan {$this->cleaningRecord->ruangan->name}",
            'cleaning_record_id' => $this->cleaningRecord->id,
            'room_name' => $this->cleaningRecord->ruangan->name,
            'task_count' => $this->taskCount,
            'date' => $this->cleaningRecord->date,
            'type' => 'new_task',
            'url' => route('ob.cleaning-records.show', $this->cleaningRecord->id)
        ];
    }
}