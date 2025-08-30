<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classmodel extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function bookingClasses()
    {
        return $this->hasMany(BookingClass::class);
    }

    public function bookingItem()
    {
        return $this->belongsTo(BookingItem::class);
    }
}
