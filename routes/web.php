<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentTransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;

// Halaman awal mengarahkan pengguna ke dashboard.
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Semua halaman utama KosManager wajib login.
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('rooms', RoomController::class)
        ->except(['show']);

    Route::resource('tenants', TenantController::class)
        ->except(['show']);

    Route::resource('payments', PaymentController::class)
        ->except(['show']);

    Route::get(
        '/payments/{payment}/transactions',
        [PaymentTransactionController::class, 'index']
    )->name('payments.transactions.index');

    Route::get(
        '/payments/{payment}/transactions/create',
        [PaymentTransactionController::class, 'create']
    )->name('payments.transactions.create');

    Route::post(
        '/payments/{payment}/transactions',
        [PaymentTransactionController::class, 'store']
    )->name('payments.transactions.store');

    Route::get('/reports', [ReportController::class, 'index'])
        ->name('reports.index');

    // Pengaturan profil dari Breeze.
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__.'/auth.php';
