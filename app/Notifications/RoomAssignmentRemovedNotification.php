<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;

class RoomAssignmentRemovedNotification extends Notification
{
    // use Queueable;

    protected $roomAssignment;
    protected $removedBy;

    public function __construct($roomAssignment, $removedBy)
    {
        $this->roomAssignment = $roomAssignment;
        $this->removedBy = $removedBy;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Alokasi Ruangan Dihapus',
            'message' => "Alokasi ruangan {$this->roomAssignment->ruangan->name} untuk tanggal {$this->roomAssignment->assigned_date->format('d/m/Y')} telah dihapus oleh {$this->removedBy->name}",
            'room_id' => $this->roomAssignment->room_id,
            'assigned_date' => $this->roomAssignment->assigned_date->format('Y-m-d'),
            'removed_by' => $this->removedBy->name,
            'icon' => 'bi-x-circle',
            'color' => 'danger'
        ];
    }
}