<?php

namespace App\Notifications;

use App\Models\Ruangan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewRoomAddedNotification extends Notification
{
    use Queueable;

    protected $ruangan;
    protected $tasksCount;

    public function __construct(Ruangan $ruangan, $tasksCount)
    {
        $this->ruangan = $ruangan;
        $this->tasksCount = $tasksCount;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Ruangan Baru Ditambahkan',
            'message' => "Ruangan baru '{$this->ruangan->name}' telah ditambahkan dengan {$this->tasksCount} tugas kebersihan yang perlu diselesaikan",
            'room_id' => $this->ruangan->id,
            'room_name' => $this->ruangan->name,
            'tasks_count' => $this->tasksCount,
            'type' => 'new_room',
            'url' => route('ob.cleaning-records.index')
        ];
    }

    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}