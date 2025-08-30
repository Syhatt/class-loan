<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyPrograms extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
