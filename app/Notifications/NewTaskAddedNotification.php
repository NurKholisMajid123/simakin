<?php
// app/Notifications/NewTaskAddedNotification.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Tugas;
use App\Models\Ruangan;

class NewTaskAddedNotification extends Notification
{
    use Queueable;

    protected $tugas;
    protected $ruangan;

    public function __construct(Tugas $tugas, ?Ruangan $ruangan = null)
    {
        $this->tugas = $tugas;
        $this->ruangan = $ruangan;
    }


    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $message = "Tugas baru '{$this->tugas->name}' telah ditambahkan";

        if ($this->ruangan) {
            $message .= " untuk ruangan {$this->ruangan->name}";
        }

        return [
            'title' => 'Tugas Baru Ditambahkan',
            'message' => $message,
            'task_id' => $this->tugas->id,
            'task_name' => $this->tugas->name,
            'room_name' => $this->ruangan ? $this->ruangan->name : null,
            'type' => 'new_task_added',
            'url' => route('ob.cleaning-records.index')
        ];
    }
}
