<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CleaningRecord extends Model
{
    protected $fillable = [
        'user_id',
        'room_id',
        'date',
        'room_cleaned',
    ];

    protected $casts = [
        'date' => 'date',
        'room_cleaned' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Ruangan::class, 'room_id');
    }

    public function tasks()
    {
        return $this->hasMany(CleaningRecordTask::class);
    }
}