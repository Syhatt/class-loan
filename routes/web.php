<?php

use App\Http\Controllers\admin\BookClassController;
use App\Http\Controllers\admin\ClassController;
use App\Http\Controllers\admin\ItemController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingItemController;
use App\Http\Controllers\MyBookController;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', fn() => redirect()->route('login'));

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('layouts.dashboard');
    })->name('dashboard');
});

// Route::group(['middleware' => 'auth'], function () {
//     Route::resource('class', ClassController::class);
//     Route::resource('bookclass', BookClassController::class);
//     Route::resource('item', ItemController::class);
//     Route::resource('booking', BookingController::class);
//     Route::get('booking/tambah/{id}', [BookingController::class, 'create'])->name('booking.tambah');
//     Route::get('mybook', [MyBookController::class, 'index'])->name('mybook');
//     Route::resource('bookingitem', BookingItemController::class);
//     Route::get('bookingitem/tambah/{id}', [BookingItemController::class, 'create'])->name('bookingitem.tambah');
// });

Route::middleware(['auth', 'role:admin_ruangan'])->group(function(){
    Route::resource('class', ClassController::class);
    Route::resource('bookclass', BookClassController::class);
    Route::resource('booking', BookingController::class);
    Route::get('booking/tambah/{id}', [BookingController::class, 'create'])->name('booking.tambah');
    Route::get('mybook', [MyBookController::class, 'index'])->name('mybook');
});

Route::middleware(['auth', 'role:admin_barang'])->group(function(){
    Route::resource('item', ItemController::class);
    Route::resource('bookingitem', BookingItemController::class);
    Route::get('bookingitem/tambah/{id}', [BookingItemController::class, 'create'])->name('bookingitem.tambah');
});

Route::middleware(['auth', 'role:user,admin_ruangan'])->group(function(){
    Route::resource('booking', BookingController::class);
    Route::get('booking/tambah/{id}', [BookingController::class, 'create'])->name('booking.tambah');
    Route::get('mybook', [MyBookController::class, 'index'])->name('mybook');
});
