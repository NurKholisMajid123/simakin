<?php
// app/Notifications/TaskCompletedNotification.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\CleaningRecord;
use App\Models\User;

class TaskCompletedNotification extends Notification
{
    use Queueable;

    protected $cleaningRecord;
    protected $completedBy;
    protected $tasksCompleted;

    public function __construct(CleaningRecord $cleaningRecord, User $completedBy, $tasksCompleted)
    {
        $this->cleaningRecord = $cleaningRecord;
        $this->completedBy = $completedBy;
        $this->tasksCompleted = $tasksCompleted;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Tugas Kebersihan Selesai',
            'message' => "{$this->completedBy->name} telah menyelesaikan {$this->tasksCompleted} tugas di {$this->cleaningRecord->ruangan->name}",
            'cleaning_record_id' => $this->cleaningRecord->id,
            'room_name' => $this->cleaningRecord->ruangan->name,
            'completed_by' => $this->completedBy->name,
            'date' => $this->cleaningRecord->date,
            'type' => 'task_completed',
            'url' => route('admin.cleaning-records.show', $this->cleaningRecord->id)
        ];
    }

    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}