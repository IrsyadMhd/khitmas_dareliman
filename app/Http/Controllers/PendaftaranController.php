<?php

namespace App\Http\Controllers;

use App\Http\Requests\PendaftaranRequest;
use App\Mail\PendaftaranBerhasil;
use App\Models\Pendaftaran;
use App\Services\DarelimanAuthService;
use App\Services\EligibilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PendaftaranController extends Controller
{
    public function __construct(
        private DarelimanAuthService $authService,
        private EligibilityService $eligibilityService,
    ) {}

    /**
     * Show the login page.
     */
    public function showLogin()
    {
        // If already authenticated in session, redirect to form
        if (session()->has('siswa_data')) {
            return redirect()->route('daftar');
        }

        return view('login');
    }

    /**
     * Process login via Dareliman API.
     */
    public function processLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Authenticate via Dareliman API
        $result = $this->authService->authenticate(
            $request->input('email'),
            $request->input('password')
        );

        if (!$result['success']) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['login' => $result['message']]);
        }

        $user = $result['user'];

        // Check for existing registration (duplicate check)
        $existing = Pendaftaran::getExistingRegistration($user['id']);
        if ($existing) {
            // Store in session for success page display
            session(['siswa_data' => $user]);
            return redirect()->route('sukses', $existing->kode_registrasi)
                ->with('info', 'Anda sudah terdaftar sebelumnya. Berikut adalah bukti pendaftaran Anda.');
        }

        // Check eligibility
        $eligibility = $this->eligibilityService->checkEligibility($user);
        if (!$eligibility['eligible']) {
            return redirect()->route('ineligible')
                ->with('reason', $eligibility['reason']);
        }

        // Store user data in session (NOT password)
        session([
            'siswa_data' => $user,
            'dareliman_token' => $result['token'] ?? null,
        ]);

        return redirect()->route('daftar');
    }

    /**
     * Show the registration form with auto-filled biodata.
     */
    public function showForm(\App\Services\SettingsService $settingsService)
    {
        if (!$settingsService->isDarelimanOpen()) {
            return view('closed');
        }

        $siswa = session('siswa_data');

        if (!$siswa) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // Check for existing registration (prevent direct access to form if already registered)
        $existing = \App\Models\Pendaftaran::getExistingRegistration($siswa['id']);
        if ($existing) {
            return redirect()->route('sukses', $existing->kode_registrasi)
                ->with('info', 'Anda sudah terdaftar sebelumnya. Berikut adalah bukti pendaftaran Anda.');
        }

        // Resolve jenjang from API data
        $resolvedJenjang = $this->eligibilityService->resolveJenjang(
            $siswa['id_jenis_sekolah'] ?? null
        );

        return view('daftar', [
            'siswa' => $siswa,
            'resolvedJenjang' => $resolvedJenjang,
        ]);
    }

    /**
     * Submit the registration form.
     */
    public function submitForm(PendaftaranRequest $request, \App\Services\SettingsService $settingsService)
    {
        if (!$settingsService->isDarelimanOpen()) {
            return view('closed');
        }

        $siswa = session('siswa_data');

        if (!$siswa) {
            return redirect()->route('login')
                ->with('error', 'Sesi telah berakhir. Silakan login ulang.');
        }

        $siswaId = (int) $request->input('siswa_id');

        // Double-check: prevent duplicate registration
        $existing = Pendaftaran::getExistingRegistration($siswaId);
        if ($existing) {
            return redirect()->route('sukses', $existing->kode_registrasi)
                ->with('info', 'Anda sudah terdaftar sebelumnya.');
        }

        // Verify the siswa_id matches the session data
        if ($siswaId !== (int) $siswa['id']) {
            return redirect()->route('login')
                ->with('error', 'Data tidak konsisten. Silakan login ulang.');
        }

        // Generate unique registration code
        $kodeRegistrasi = 'KHT-' . strtoupper(Str::random(8));

        // Create the registration record
        try {
            $pendaftaran = Pendaftaran::create([
                'siswa_id' => $siswaId,
                'nama_lengkap' => $request->input('nama_lengkap'),
                'tempat_lahir' => $request->input('tempat_lahir'),
                'tanggal_lahir' => $request->input('tanggal_lahir'),
                'jenis_kelamin' => $request->input('jenis_kelamin'),
                'email' => $request->input('email_edit') ?: $request->input('email'),
                'nomor_wa' => $request->input('nomor_wa'),
                'foto' => $request->input('foto'),
                'id_jenis_sekolah' => $request->input('id_jenis_sekolah'),
                'jenjang' => $request->input('id_jenis_sekolah') ? $this->eligibilityService->resolveJenjang($request->input('id_jenis_sekolah')) : '-',
                'kelas' => $request->input('kelas'),
                'status_siswa' => $request->input('status_siswa'),
                'nama_wali' => $request->input('nama_wali'),
                'hp_wali' => $request->input('hp_wali'),
                'alamat' => $request->input('alamat'),
                'riwayat_kesehatan' => $request->input('riwayat_kesehatan'),
                'ukuran_baju' => null,
                'catatan' => null,
                'consent_wali' => true,
                'kode_registrasi' => $kodeRegistrasi,
                'status_kehadiran' => 'belum_hadir',
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle unique constraint violation (race condition)
            if ($e->getCode() === '23000') {
                $existing = Pendaftaran::getExistingRegistration($siswaId);
                if ($existing) {
                    return redirect()->route('sukses', $existing->kode_registrasi)
                        ->with('info', 'Anda sudah terdaftar sebelumnya.');
                }
            }

            Log::error('Database error during registration', [
                'error' => $e->getMessage(),
                'siswa_id' => $siswaId,
            ]);

            return back()->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }

        // Generate QR Code and save as file for email attachment
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

        // Send confirmation email if email exists
        if ($pendaftaran->email) {
            try {
                Mail::to($pendaftaran->email)->send(
                    new PendaftaranBerhasil($pendaftaran, $qrPath)
                );
            } catch (\Exception $e) {
                Log::error('Failed to send confirmation email', [
                    'error' => $e->getMessage(),
                    'email' => $pendaftaran->email,
                    'kode' => $kodeRegistrasi,
                ]);
                // Don't fail the registration if email fails
            }
        }

        // Send Telegram Notification
        try {
            $botToken = '7368857259:AAHfe-OLHEUUSYBO_JU0_OWmDashEJsXR4k';
            $chatId = '-5142709062';
            $message = "🎉 *Pendaftaran Baru Khitanan Massal*\n\n"
                     . "Nama: {$pendaftaran->nama_lengkap}\n"
                     . "Jenjang: {$pendaftaran->jenjang} " . ($pendaftaran->kelas ? "- Kelas {$pendaftaran->kelas}" : "") . "\n"
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

        // Clear session data
        session()->forget(['siswa_data', 'dareliman_token']);

        return redirect()->route('sukses', $kodeRegistrasi);
    }

    /**
     * Show the success page with QR code.
     */
    public function showSuccess(string $kode)
    {
        $pendaftaran = Pendaftaran::where('kode_registrasi', $kode)->firstOrFail();

        return view('sukses', [
            'pendaftaran' => $pendaftaran,
        ]);
    }

    /**
     * Show the ineligible page.
     */
    public function showIneligible()
    {
        $reason = session('reason', 'Anda tidak memenuhi syarat untuk mendaftar Khitanan Massal.');

        return view('ineligible', [
            'reason' => $reason,
        ]);
    }

    /**
     * Check-in endpoint for event staff to mark attendance via QR code.
     */
    public function checkin(string $kode)
    {
        $pendaftaran = Pendaftaran::where('kode_registrasi', $kode)->first();

        if (!$pendaftaran) {
            return response()->json([
                'success' => false,
                'message' => 'Kode registrasi tidak ditemukan.',
            ], 404);
        }

        if ($pendaftaran->status_kehadiran === 'hadir') {
            return response()->json([
                'success' => true,
                'message' => 'Peserta sudah di-check-in sebelumnya.',
                'data' => [
                    'nama' => $pendaftaran->nama_lengkap,
                    'jenjang' => $pendaftaran->jenjang,
                    'kelas' => $pendaftaran->kelas,
                    'waktu_checkin' => $pendaftaran->waktu_checkin?->format('d/m/Y H:i'),
                ],
            ]);
        }

        $pendaftaran->update([
            'status_kehadiran' => 'hadir',
            'waktu_checkin' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Check-in berhasil!',
            'data' => [
                'nama' => $pendaftaran->nama_lengkap,
                'jenjang' => $pendaftaran->jenjang,
                'kelas' => $pendaftaran->kelas,
                'waktu_checkin' => $pendaftaran->waktu_checkin->format('d/m/Y H:i'),
            ],
        ]);
    }

    /**
     * Logout / clear session.
     */
    public function logout()
    {
        session()->forget(['siswa_data', 'dareliman_token']);
        return redirect()->route('login');
    }
}
