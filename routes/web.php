<?php

use App\Http\Controllers\admin\ClassController;
use App\Http\Controllers\admin\ItemController;
use App\Http\Controllers\BookingController;
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

Route::group(['middleware' => 'auth'], function () {
    Route::resource('class', ClassController::class);
    Route::resource('item', ItemController::class);
    Route::resource('booking', BookingController::class);
    Route::get('booking/tambah/{id}', [BookingController::class, 'create'])->name('booking.tambah');
});
