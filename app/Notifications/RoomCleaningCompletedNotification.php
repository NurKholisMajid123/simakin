<?php
// app/Notifications/RoomCleaningCompletedNotification.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\CleaningRecord;
use App\Models\User;

class RoomCleaningCompletedNotification extends Notification
{
    use Queueable;

    protected $cleaningRecord;
    protected $completedBy;

    public function __construct(CleaningRecord $cleaningRecord, User $completedBy)
    {
        $this->cleaningRecord = $cleaningRecord;
        $this->completedBy = $completedBy;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Ruangan Selesai Dibersihkan',
            'message' => "{$this->completedBy->name} telah menyelesaikan semua tugas pembersihan di {$this->cleaningRecord->ruangan->name}",
            'cleaning_record_id' => $this->cleaningRecord->id,
            'room_name' => $this->cleaningRecord->ruangan->name,
            'completed_by' => $this->completedBy->name,
            'date' => $this->cleaningRecord->date,
            'type' => 'room_completed',
            'url' => route('admin.cleaning-records.show', $this->cleaningRecord->id)
        ];
    }
}