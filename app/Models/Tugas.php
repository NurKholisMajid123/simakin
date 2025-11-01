<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    protected $table = 'tugas';
    
    protected $fillable = [
        'name',
        'description',
    ];

    public function cleaningRecordTasks()
    {
        return $this->hasMany(CleaningRecordTask::class, 'task_id');
    }
}