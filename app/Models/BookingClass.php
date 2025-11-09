<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingClass extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function bookingItems()
    {
        return $this->hasMany(BookingItem::class, 'booking_classes_id');
    }

    public function classmodel()
    {
        return $this->belongsTo(Classmodel::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function nodin()
    {
        return $this->hasOne(Nodin::class);
    }
}
