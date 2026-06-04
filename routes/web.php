<?php

use App\Http\Controllers\PendaftaranController;
use Illuminate\Support\Facades\Route;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [PendaftaranController::class, 'showLogin'])->name('login');
Route::post('/login', [PendaftaranController::class, 'processLogin'])
    ->name('login.process')
    ->middleware('throttle:5,1'); // Limit login attempts: 5 requests per 1 minute

Route::get('/daftar', [PendaftaranController::class, 'showForm'])->name('daftar');
Route::post('/daftar', [PendaftaranController::class, 'submitForm'])->name('daftar.submit');

Route::get('/sukses/{kode}', [PendaftaranController::class, 'showSuccess'])->name('sukses');
Route::get('/ineligible', [PendaftaranController::class, 'showIneligible'])->name('ineligible');

Route::get('/logout', [PendaftaranController::class, 'logout'])->name('logout');

// Check-in API for event staff (could be protected by auth/middleware in the future)
Route::post('/api/checkin/{kode}', [PendaftaranController::class, 'checkin'])->name('api.checkin');
