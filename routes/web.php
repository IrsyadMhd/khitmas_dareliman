<?php

use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\UmumController;
use Illuminate\Support\Facades\Route;

// Landing page atau smart redirect berdasarkan status buka/tutup pendaftaran
Route::get('/', function (\App\Services\SettingsService $settings) {
    $darelimanOpen = $settings->isDarelimanOpen();
    $umumOpen = $settings->isUmumOpen();

    if ($darelimanOpen && $umumOpen) {
        return view('landing');
    } elseif ($darelimanOpen && !$umumOpen) {
        return redirect()->route('login');
    } elseif (!$darelimanOpen && $umumOpen) {
        return redirect()->route('umum.daftar');
    } else {
        return view('closed');
    }
})->name('landing');

// Jalur Internal Dareliman
Route::get('/dareliman/login', [PendaftaranController::class, 'showLogin'])->name('login');
Route::post('/dareliman/login', [PendaftaranController::class, 'processLogin'])
    ->name('login.process')
    ->middleware('throttle:5,1'); // Limit login attempts: 5 requests per 1 minute

Route::get('/dareliman/daftar', [PendaftaranController::class, 'showForm'])->name('daftar');
Route::post('/dareliman/daftar', [PendaftaranController::class, 'submitForm'])->name('daftar.submit');

Route::get('/sukses/{kode}', [PendaftaranController::class, 'showSuccess'])->name('sukses');
Route::get('/ineligible', [PendaftaranController::class, 'showIneligible'])->name('ineligible');
Route::get('/logout', [PendaftaranController::class, 'logout'])->name('logout');

// Jalur Umum
Route::prefix('umum')->name('umum.')->group(function () {
    Route::get('/daftar', [UmumController::class, 'showForm'])->name('daftar');
    Route::post('/daftar', [UmumController::class, 'submitForm'])->name('daftar.submit');
    
    Route::get('/login', [UmumController::class, 'showLogin'])->name('login');
    Route::post('/login', [UmumController::class, 'processLogin'])->name('login.process');
    
    Route::get('/dashboard', [UmumController::class, 'dashboard'])->name('dashboard');
    Route::get('/logout', [UmumController::class, 'logout'])->name('logout');
});

// Admin Panel Rahasia
Route::prefix('panel-rahasia')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\AdminController::class, 'showLogin'])->name('login');
    Route::post('/', [\App\Http\Controllers\AdminController::class, 'processLogin'])->name('login.process');
    
    Route::get('/settings', [\App\Http\Controllers\AdminController::class, 'showSettings'])->name('settings');
    Route::post('/settings', [\App\Http\Controllers\AdminController::class, 'updateSettings'])->name('settings.update');

    Route::get('/laporan', [\App\Http\Controllers\AdminController::class, 'showLaporan'])->name('laporan');
    Route::get('/laporan/{id}/barcode', [\App\Http\Controllers\AdminController::class, 'showBarcode'])->name('laporan.barcode');
    Route::post('/laporan/{id}/batal', [\App\Http\Controllers\AdminController::class, 'batalHadir'])->name('laporan.batal');
    Route::delete('/laporan/{id}/hapus', [\App\Http\Controllers\AdminController::class, 'hapusData'])->name('laporan.hapus');
    Route::post('/laporan/{id}/jadwal', [\App\Http\Controllers\AdminController::class, 'updateJadwal'])->name('laporan.jadwal');
    
    Route::get('/import-jadwal', [\App\Http\Controllers\AdminController::class, 'showImportJadwal'])->name('importJadwal');
    Route::post('/import-jadwal', [\App\Http\Controllers\AdminController::class, 'processImportJadwal'])->name('importJadwal.process');
    Route::get('/migrate-db', [\App\Http\Controllers\AdminController::class, 'runMigration'])->name('migrate');
    
    Route::get('/logout', [\App\Http\Controllers\AdminController::class, 'logout'])->name('logout');
});

// Scanner Kehadiran Panitia
Route::prefix('scan-kehadiran')->name('scanner.')->group(function () {
    Route::get('/login', [\App\Http\Controllers\CheckinController::class, 'showLogin'])->name('login');
    Route::post('/login', [\App\Http\Controllers\CheckinController::class, 'processLogin'])->name('login.process');
    
    Route::get('/', [\App\Http\Controllers\CheckinController::class, 'showScanner'])->name('index');
    Route::post('/process', [\App\Http\Controllers\CheckinController::class, 'processScan'])->name('process');
});

// Scanner Hadiah/Merchandise Panitia
Route::prefix('scan-hadiah')->name('scanner.hadiah.')->group(function () {
    Route::get('/', [\App\Http\Controllers\CheckinController::class, 'showMerchandiseScanner'])->name('index');
    Route::post('/process', [\App\Http\Controllers\CheckinController::class, 'processMerchandise'])->name('process');
});

// Check-in API for event staff (Legacy/Fallback API if needed)
Route::post('/api/checkin/{kode}', [PendaftaranController::class, 'checkin'])->name('api.checkin');

// Database Export API (Protected by API Key)
Route::get('/api/pendaftar', [\App\Http\Controllers\ApiController::class, 'getPendaftar'])->name('api.pendaftar');

