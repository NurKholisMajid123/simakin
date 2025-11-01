<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CleaningRecordTask extends Model
{
    protected $fillable = [
        'cleaning_record_id',
        'task_id',
        'is_done',
        'note',
        'completed_by_user_id',
        'completed_at',
    ];

    protected $casts = [
        'is_done' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function cleaningRecord()
    {
        return $this->belongsTo(CleaningRecord::class);
    }

    public function task()
    {
        return $this->belongsTo(Tugas::class, 'task_id');
    }

    public function completedBy()
    {
        return $this->belongsTo(User::class, 'completed_by_user_id');
    }
}