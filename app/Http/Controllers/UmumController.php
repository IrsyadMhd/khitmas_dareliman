<?php

namespace App\Http\Controllers;

use App\Mail\PendaftaranBerhasil;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class UmumController extends Controller
{
    /**
     * Tampilkan form pendaftaran umum.
     */
    public function showForm(\App\Services\SettingsService $settingsService)
    {
        if (!$settingsService->isUmumOpen()) {
            return view('closed');
        }
        return view('umum.daftar');
    }

    /**
     * Proses pendaftaran umum.
     */
    public function submitForm(Request $request, \App\Services\SettingsService $settingsService)
    {
        if (!$settingsService->isUmumOpen()) {
            return view('closed');
        }

        // Bersihkan dan format nomor HP sebelum validasi
        $hp = $request->input('hp_wali');
        if ($hp) {
            $hp = preg_replace('/[^0-9]/', '', $hp);
            if (str_starts_with($hp, '62')) {
                $hp = '0' . substr($hp, 2);
            }
            $request->merge(['hp_wali' => $hp]);
        }

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|string',
            'nama_wali' => 'required|string|max:255',
            'hp_wali' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'alamat' => 'required|string|max:1000',
            'consent_wali' => 'required|accepted',
        ], [
            'nama_lengkap.required' => 'Nama lengkap anak wajib diisi.',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'nama_wali.required' => 'Nama orang tua/wali wajib diisi.',
            'hp_wali.required' => 'Nomor HP wali wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'alamat.required' => 'Alamat domisili wajib diisi.',
            'consent_wali.required' => 'Anda harus menyetujui pernyataan persetujuan wali.',
        ]);

        // Generate ID Siswa unik untuk Umum
        $siswaId = 'UMM-' . rand(100000, 999999);
        while (Pendaftaran::where('siswa_id', $siswaId)->exists()) {
            $siswaId = 'UMM-' . rand(100000, 999999);
        }

        $kodeRegistrasi = 'KHT-' . strtoupper(Str::random(8));

        $pendaftaran = Pendaftaran::create([
            'is_umum' => true,
            'siswa_id' => $siswaId,
            'nama_lengkap' => $request->input('nama_lengkap'),
            'tempat_lahir' => $request->input('tempat_lahir'),
            'tanggal_lahir' => $request->input('tanggal_lahir'),
            'jenis_kelamin' => $request->input('jenis_kelamin'),
            'email' => $request->input('email'),
            'nomor_wa' => $request->input('hp_wali'), // simpan no hp wali ke nomor_wa juga
            'status_siswa' => 'UMUM',
            'jenjang' => 'UMUM',
            'nama_wali' => $request->input('nama_wali'),
            'hp_wali' => $request->input('hp_wali'),
            'alamat' => $request->input('alamat'),
            'consent_wali' => true,
            'kode_registrasi' => $kodeRegistrasi,
            'status_kehadiran' => 'belum_hadir',
        ]);

        // Generate QR Code
        try {
            $qrDirectory = storage_path('app/public/qrcodes');
            if (!is_dir($qrDirectory)) {
                mkdir($qrDirectory, 0755, true);
            }

            $qrPath = $qrDirectory . '/' . $kodeRegistrasi . '.png';
            QrCode::format('png')
                ->size(400)
                ->errorCorrection('H')
                ->generate($kodeRegistrasi, $qrPath);
        } catch (\Exception $e) {
            Log::warning('QR Code generation failed, will use inline SVG', [
                'error' => $e->getMessage(),
                'kode' => $kodeRegistrasi,
            ]);
            $qrPath = null;
        }

        // Kirim email
        if ($pendaftaran->email) {
            try {
                Mail::to($pendaftaran->email)->send(
                    new PendaftaranBerhasil($pendaftaran, $qrPath)
                );
            } catch (\Exception $e) {
                Log::error('Failed to send confirmation email', [
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Kirim notifikasi Telegram
        try {
            $botToken = '7368857259:AAHfe-OLHEUUSYBO_JU0_OWmDashEJsXR4k';
            $chatId = '-5142709062';
            $message = "🎉 *Pendaftaran Baru Khitanan Massal (JALUR UMUM)*\n\n"
                     . "Nama Anak: {$pendaftaran->nama_lengkap}\n"
                     . "Nama Wali: {$pendaftaran->nama_wali}\n"
                     . "No HP Wali: {$pendaftaran->hp_wali}\n"
                     . "Kode Registrasi: *{$pendaftaran->kode_registrasi}*\n\n"
                     . "Waktu: " . now()->format('d/m/Y H:i');
                     
            Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'Markdown'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send Telegram notification', ['error' => $e->getMessage()]);
        }

        // Set session login untuk Umum otomatis
        session(['umum_hp_wali' => $pendaftaran->hp_wali]);

        return redirect()->route('umum.dashboard')->with('success', 'Pendaftaran berhasil!');
    }

    /**
     * Tampilkan form login umum.
     */
    public function showLogin()
    {
        if (session()->has('umum_hp_wali')) {
            return redirect()->route('umum.dashboard');
        }
        return view('umum.login');
    }

    /**
     * Proses login umum.
     */
    public function processLogin(Request $request)
    {
        $request->validate([
            'hp_wali' => 'required|string',
            'password' => 'required|string',
        ]);

        $hp = $request->input('hp_wali');
        $hp = preg_replace('/[^0-9]/', '', $hp);
        if (str_starts_with($hp, '62')) {
            $hp = '0' . substr($hp, 2);
        }

        $password = $request->input('password');
        
        // Pengecekan 4 digit terakhir
        if (strlen($hp) < 4) {
            return back()->withErrors(['login' => 'Nomor HP tidak valid.'])->withInput();
        }

        $last4 = substr($hp, -4);
        if ($password !== $last4) {
            return back()->withErrors(['login' => 'Password salah. Gunakan 4 digit terakhir nomor HP Anda.'])->withInput();
        }

        // Cek apakah ada pendaftaran dengan nomor HP ini
        $exists = Pendaftaran::where('is_umum', true)->where('hp_wali', $hp)->exists();
        if (!$exists) {
            return back()->withErrors(['login' => 'Tidak ditemukan data pendaftaran dengan nomor HP ini.'])->withInput();
        }

        // Login sukses
        session(['umum_hp_wali' => $hp]);

        return redirect()->route('umum.dashboard');
    }

    /**
     * Tampilkan dashboard wali (berisi daftar anak).
     */
    public function dashboard()
    {
        $hp = session('umum_hp_wali');
        if (!$hp) {
            return redirect()->route('umum.login');
        }

        $pendaftarans = Pendaftaran::where('is_umum', true)->where('hp_wali', $hp)->get();

        if ($pendaftarans->isEmpty()) {
            session()->forget('umum_hp_wali');
            return redirect()->route('umum.login')->withErrors(['login' => 'Data tidak ditemukan.']);
        }

        return view('umum.dashboard', compact('pendaftarans'));
    }

    /**
     * Logout umum.
     */
    public function logout()
    {
        session()->forget('umum_hp_wali');
        return redirect()->route('landing');
    }
}
