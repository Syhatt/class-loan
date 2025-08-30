<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nodin extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function bookingClass()
    {
        return $this->belongsTo(BookingClass::class);
    }
}
