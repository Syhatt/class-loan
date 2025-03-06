<?php

namespace App\Http\Controllers;

use App\Models\BookingItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyBookItemController extends Controller
{
    public function index()
    {
        $pageTitle = 'Peminjaman Saya';
        $bookitem = BookingItem::where('user_id', Auth::id())->orderBy('id', 'desc')->get();

        return view('bookingitem.mybook', compact('pageTitle', 'bookitem'));
    }
}
