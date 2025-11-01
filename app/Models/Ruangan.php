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
}