<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    protected $table = 'ruangan';
    
    protected $fillable = [
        'name',
        'description',
    ];

    public function cleaningRecords()
    {
        return $this->hasMany(CleaningRecord::class, 'room_id');
    }

    public function roomAssignments()
    {
        return $this->hasMany(RoomAssignment::class, 'room_id');
    }

    public function assignedUsers($date = null)
    {
        $date = $date ?? now()->toDateString();
        return $this->belongsToMany(User::class, 'room_assignments')
            ->wherePivot('assigned_date', $date);
    }
}