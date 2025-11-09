<?php

use App\Http\Controllers\admin\BookClassController;
use App\Http\Controllers\admin\BookItemController;
use App\Http\Controllers\admin\ClassController;
use App\Http\Controllers\admin\FacultyController;
use App\Http\Controllers\admin\ItemController;
use App\Http\Controllers\admin\ReportClassController;
use App\Http\Controllers\admin\ReportController;
use App\Http\Controllers\admin\ReportItemController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingItemController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingController;
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
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/landing', [LandingController::class, 'index'])->name('landing');

Route::middleware(['auth', 'role:admin_fakultas,superadmin'])->group(function () {
    // Manajemen kelas & barang
    Route::resource('class', ClassController::class);
    Route::patch('/class/{id}/toggle', [ClassController::class, 'toggleStatus'])->name('class.toggle');
    Route::patch('/class/{id}/delete-image/{index}', [ClassController::class, 'deleteImage'])->name('class.deleteImage');
    Route::resource('item', ItemController::class);
    Route::resource('faculty', FacultyController::class);
    Route::resource('user', UserController::class);

    // Approval dan pengelolaan peminjaman
    Route::resource('bookclass', BookClassController::class);
    Route::resource('bookitem', BookItemController::class);

    // Data booking user
    Route::resource('booking', BookingController::class);
    Route::get('booking/tambah/{id}', [BookingController::class, 'create'])->name('booking.tambah');
    Route::get('mybook', [MyBookController::class, 'index'])->name('mybook');

    Route::resource('bookingitem', BookingItemController::class);
    Route::get('bookingitem/tambah/{id}', [BookingItemController::class, 'create'])->name('bookingitem.tambah');
    Route::get('mybookitem', [MyBookItemController::class, 'index'])->name('mybookitem');

    // Laporan
    Route::get('/reports/class', [ReportClassController::class, 'index'])->name('report.class.index');
    Route::get('/reports/class/generate', [ReportClassController::class, 'generate'])->name('report.class.generate');
    Route::get('/reports/class/download', [ReportClassController::class, 'download'])->name('report.class.download');

    Route::get('/reports/item', [ReportItemController::class, 'index'])->name('report.item.index');
    Route::get('/reports/item/generate', [ReportItemController::class, 'generate'])->name('report.item.generate');
    Route::get('/reports/item/download', [ReportItemController::class, 'download'])->name('report.item.download');

    Route::get('/report', [ReportController::class, 'index'])->name('report.index');
    Route::get('/report/pdf', [ReportController::class, 'exportPdf'])->name('report.export.pdf');
});

// Dashboard user, dosen, admin_fakultas
Route::middleware(['auth', 'role:user,dosen,admin_fakultas,superadmin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/databooking', [DashboardController::class, 'databooking'])->name('bookings.data');

    // Peminjaman Ruangan
    Route::resource('booking', BookingController::class);
    Route::get('booking/tambah/{id}', [BookingController::class, 'create'])->name('booking.tambah');
    Route::get('mybook', [MyBookController::class, 'index'])->name('mybook');

    // Peminjaman Barang
    Route::resource('bookingitem', BookingItemController::class);
    Route::get('bookingitem/tambah/{id}', [BookingItemController::class, 'create'])->name('bookingitem.tambah');
    Route::get('mybookitem', [MyBookItemController::class, 'index'])->name('mybookitem');
});

// Superadmin
// Route::middleware(['auth', 'role:superadmin,admin_fakultas'])->group(function () {
//     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
//     Route::resource('user', UserController::class);
//     Route::resource('class', ClassController::class);
//     Route::patch('/class/{id}/toggle', [ClassController::class, 'toggleStatus'])->name('class.toggle');
//     Route::patch('/class/{id}/delete-image/{index}', [ClassController::class, 'deleteImage'])->name('class.deleteImage');
//     Route::resource('item', ItemController::class);
//     Route::resource('faculty', FacultyController::class);
// });
