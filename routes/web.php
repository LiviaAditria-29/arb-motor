<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SparePartController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\SparePartController as AdminSparePartController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ReportController;
use Illuminate\Support\Facades\Route;
// ── PUBLIC ────────────────────────────────────────────────────
Route::get('/',                  [HomeController::class,          'index'])->name('home');
Route::get('/services',          [ServiceController::class,       'index'])->name('services.index');
Route::get('/services/{id}',     [ServiceController::class,       'show'])->name('services.show');
Route::get('/spare-parts',       [SparePartController::class,     'index'])->name('spare-parts.index');
Route::get('/spare-parts/{id}',  [SparePartController::class,     'show'])->name('spare-parts.show');

// ── ADMIN ─────────────────────────────────────────────────────
Route::middleware(['auth', 'verified'])
     ->prefix('admin')
     ->name('admin.')
     ->group(function () {

    Route::get('/',          [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Booking
    Route::get('/bookings',                [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create',         [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings',               [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{id}',           [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{id}/edit',      [BookingController::class, 'edit'])->name('bookings.edit');
    Route::put('/bookings/{id}',           [BookingController::class, 'update'])->name('bookings.update');
    Route::delete('/bookings/{id}',        [BookingController::class, 'destroy'])->name('bookings.destroy');
    Route::post('/bookings/{id}/status',   [BookingController::class, 'updateStatus'])->name('bookings.update-status');

    // Layanan
    Route::get('/services',               [AdminServiceController::class, 'index'])->name('services.index');
    Route::get('/services/create',        [AdminServiceController::class, 'create'])->name('services.create');
    Route::post('/services',              [AdminServiceController::class, 'store'])->name('services.store');
    Route::get('/services/{id}/edit',     [AdminServiceController::class, 'edit'])->name('services.edit');
    Route::put('/services/{id}',          [AdminServiceController::class, 'update'])->name('services.update');
    Route::delete('/services/{id}',       [AdminServiceController::class, 'destroy'])->name('services.destroy');

    // Spare Part
    Route::get('/spare-parts',            [AdminSparePartController::class, 'index'])->name('spare-parts.index');
    Route::get('/spare-parts/create',     [AdminSparePartController::class, 'create'])->name('spare-parts.create');
    Route::post('/spare-parts',           [AdminSparePartController::class, 'store'])->name('spare-parts.store');
    Route::get('/spare-parts/{id}/edit',  [AdminSparePartController::class, 'edit'])->name('spare-parts.edit');
    Route::put('/spare-parts/{id}',       [AdminSparePartController::class, 'update'])->name('spare-parts.update');
    Route::delete('/spare-parts/{id}',    [AdminSparePartController::class, 'destroy'])->name('spare-parts.destroy');

    // Pelanggan
    Route::get('/customers',              [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{id}',         [CustomerController::class, 'show'])->name('customers.show');

    // Laporan
    Route::get('/reports',                [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export',         [ReportController::class, 'exportPdf'])->name('reports.export');
    Route::get('/reports/preview',        [ReportController::class, 'previewPdf'])->name('reports.preview');
});

// ── AUTH ──────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile',    [ProfileController::class,'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class,'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class,'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
