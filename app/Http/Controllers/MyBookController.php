<?php

namespace App\Http\Controllers;

use App\Models\BookingClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyBookController extends Controller
{
    public function index()
    {
        $pageTitle = 'Peminjaman Saya';
        $booking = BookingClass::where('user_id', Auth::id())->orderBy('start_time', 'desc')->get();

        return view('booking.mybook', compact('pageTitle', 'booking'));
    }
}
