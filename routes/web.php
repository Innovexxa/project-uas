<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Admin\AdminAppointmentController;

// 1. Redirect Home ke Login
Route::get('/', function () {
    return redirect()->route('login');
});

// 2. Auth Routes
Auth::routes();

// 3. Gabungan Route User & Admin
Route::middleware(['auth'])->group(function () {

    // --- KHUSUS USER (PASIEN) ---
    Route::get('/dashboard', [AppointmentController::class, 'index'])->name('dashboard');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::delete('/appointments/{id}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');

    // --- KHUSUS ADMIN (PERAWAT) ---
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        // Halaman utama admin
        Route::get('/appointments', [AdminAppointmentController::class, 'index'])->name('admin.appointments.index');

        // Update status manual (Waiting -> Called -> Done)
        Route::patch('/appointments/{id}/status', [AdminAppointmentController::class, 'updateStatus'])->name('admin.appointments.update');

        // Panggil antrian selanjutnya berdasarkan ID Dokter
        Route::post('/appointments/call-next/{doctorId}', [AdminAppointmentController::class, 'callNext'])->name('admin.appointments.callNext');
    });
});
