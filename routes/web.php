<?php

use App\Http\Controllers\admin\BookClassController;
use App\Http\Controllers\admin\BookItemController;
use App\Http\Controllers\admin\ClassController;
use App\Http\Controllers\admin\ItemController;
use App\Http\Controllers\admin\ReportClassController;
use App\Http\Controllers\admin\ReportItemController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingItemController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MyBookController;
use App\Http\Controllers\MyBookItemController;
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

Route::middleware(['auth', 'role:admin_ruangan'])->group(function () {
    Route::resource('class', ClassController::class);
    Route::resource('bookclass', BookClassController::class);
    Route::resource('booking', BookingController::class);
    Route::get('booking/tambah/{id}', [BookingController::class, 'create'])->name('booking.tambah');
    Route::get('mybook', [MyBookController::class, 'index'])->name('mybook');
    Route::get('/reports/class', [ReportClassController::class, 'index'])->name('report.class.index');
    Route::get('/reports/class/generate', [ReportClassController::class, 'generate'])->name('report.class.generate');
    Route::get('/reports/class/download', [ReportClassController::class, 'download'])->name('report.class.download');
});

Route::middleware(['auth', 'role:admin_barang'])->group(function () {
    Route::resource('item', ItemController::class);
    Route::resource('bookitem', BookItemController::class);
    Route::resource('bookingitem', BookingItemController::class);
    Route::get('bookingitem/tambah/{id}', [BookingItemController::class, 'create'])->name('bookingitem.tambah');
    Route::get('mybookitem', [MyBookItemController::class, 'index'])->name('mybookitem');
    Route::get('/reports/item', [ReportItemController::class, 'index'])->name('report.item.index');
    Route::get('/reports/item/generate', [ReportItemController::class, 'generate'])->name('report.item.generate');
    Route::get('/reports/item/download', [ReportItemController::class, 'download'])->name('report.item.download');
});

Route::middleware(['auth', 'role:user,dosen,admin_ruangan,admin_barang'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/databooking', [DashboardController::class, 'databooking'])->name('bookings.data');

    // Peminjaman Ruangan
    Route::resource('booking', BookingController::class);
    Route::get('booking/tambah/{id}', [BookingController::class, 'create'])->name('booking.tambah');
    Route::get('mybook', [MyBookController::class, 'index'])->name('mybook');

    // peminjaman barang
    Route::resource('bookingitem', BookingItemController::class);
    Route::get('bookingitem/tambah/{id}', [BookingItemController::class, 'create'])->name('bookingitem.tambah');
    Route::get('mybookitem', [MyBookItemController::class, 'index'])->name('mybookitem');
});
