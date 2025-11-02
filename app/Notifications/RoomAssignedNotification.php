<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class RoomAssignedNotification extends Notification
{
    // use Queueable;

    protected $roomAssignment;
    protected $assignedBy;

    public function __construct($roomAssignment, $assignedBy)
    {
        $this->roomAssignment = $roomAssignment;
        $this->assignedBy = $assignedBy;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Alokasi Ruangan Baru',
            'message' => "Anda dialokasikan untuk ruangan {$this->roomAssignment->ruangan->name} pada tanggal {$this->roomAssignment->assigned_date->format('d/m/Y')}",
            'room_assignment_id' => $this->roomAssignment->id,
            'room_id' => $this->roomAssignment->room_id,
            'assigned_date' => $this->roomAssignment->assigned_date->format('Y-m-d'),
            'assigned_by' => $this->assignedBy->name,
            'action_url' => route('ob.cleaning-records.index'),
            'icon' => 'bi-door-open',
            'color' => 'primary'
        ];
    }
}