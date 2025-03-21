<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classModel()
    {
        return $this->belongsTo(Classmodel::class);
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function bookingClass()
    {
        return $this->belongsTo(BookingClass::class, 'booking_classes_id');
    }
}
