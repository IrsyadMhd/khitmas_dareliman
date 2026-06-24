<?php

namespace App\Http\Controllers;

use App\Services\SettingsService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct(
        private SettingsService $settingsService
    ) {}

    public function showLogin()
    {
        if (session('admin_logged_in')) {
            return redirect()->route('admin.settings');
        }
        return view('admin.login');
    }

    public function processLogin(Request $request)
    {
        $request->validate(['password' => 'required']);

        // Password Read-only
        if ($request->password === '111213') {
            session(['admin_logged_in' => true, 'admin_role' => 'readonly']);
            return redirect()->route('admin.settings');
        }
        
        // Password Superadmin (Bisa Hapus)
        if ($request->password === '121314') {
            session(['admin_logged_in' => true, 'admin_role' => 'superadmin']);
            return redirect()->route('admin.settings');
        }

        return back()->withErrors(['password' => 'Password salah.']);
    }

    public function showSettings()
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $settings = $this->settingsService->getSettings();
        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $request->validate([
            'dareliman_mode' => 'required|in:manual_open,manual_closed,schedule',
            'dareliman_start' => 'nullable|date',
            'dareliman_end' => 'nullable|date',
            'umum_mode' => 'required|in:manual_open,manual_closed,schedule',
            'umum_start' => 'nullable|date',
            'umum_end' => 'nullable|date',
        ]);

        $this->settingsService->updateSettings([
            'dareliman_mode' => $request->dareliman_mode,
            'dareliman_start' => $request->dareliman_start,
            'dareliman_end' => $request->dareliman_end,
            'umum_mode' => $request->umum_mode,
            'umum_start' => $request->umum_start,
            'umum_end' => $request->umum_end,
        ]);

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }

    public function showLaporan(\Illuminate\Http\Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $query = \App\Models\Pendaftaran::orderBy('created_at', 'desc');

        if ($request->has('status') && in_array($request->status, ['hadir', 'belum_hadir'])) {
            $query->where('status_kehadiran', $request->status);
        }

        $pendaftarans = $query->get();
        $statusFilter = $request->status;

        if (session('admin_role') === 'superadmin') {
            $allRecords = \App\Models\Pendaftaran::all();
            
            foreach ($pendaftarans as $p) {
                $p->duplicate_status = 'green';
                $p->duplicate_reason = 'Aman, tidak ada indikasi ganda.';

                foreach ($allRecords as $other) {
                    if ($p->id === $other->id) continue;

                    $name1 = strtolower(trim($p->nama_lengkap));
                    $name2 = strtolower(trim($other->nama_lengkap));
                    
                    if ($name1 === $name2) {
                        if ($p->tanggal_lahir->equalTo($other->tanggal_lahir)) {
                            $p->duplicate_status = 'red';
                            $p->duplicate_reason = 'Sangat Identik: Nama & Tanggal Lahir sama persis dengan pendaftar lain.';
                            break; 
                        } else {
                            if ($p->duplicate_status !== 'red') {
                                $p->duplicate_status = 'yellow';
                                $p->duplicate_reason = 'Perlu Perhatian: Nama sama persis dengan pendaftar lain, tetapi Tanggal Lahir berbeda.';
                            }
                        }
                    } else {
                        if ($p->duplicate_status === 'green') {
                            $words1 = explode(' ', $name1);
                            $words2 = explode(' ', $name2);
                            $intersect = array_intersect($words1, $words2);
                            if (count($intersect) >= 2 && count($words1) > 1 && count($words2) > 1) {
                                $p->duplicate_status = 'yellow';
                                $p->duplicate_reason = 'Perlu Perhatian: Nama memiliki kemiripan kata (' . implode(' ', $intersect) . ') dengan pendaftar lain.';
                            }
                        }
                    }
                }
            }

            if ($request->status === 'ganda') {
                $pendaftarans = $pendaftarans->filter(function($p) {
                    return $p->duplicate_status === 'red' || $p->duplicate_status === 'yellow';
                });
            }
        }

        return view('admin.laporan', compact('pendaftarans', 'statusFilter'));
    }

    public function batalHadir($id)
    {
        if (!session('admin_logged_in') || session('admin_role') !== 'superadmin') {
            return back()->with('error', 'Anda tidak memiliki akses untuk membatalkan kehadiran. Silakan login menggunakan password superadmin.');
        }

        $pendaftar = \App\Models\Pendaftaran::findOrFail($id);
        $pendaftar->status_kehadiran = 'belum_hadir';
        $pendaftar->waktu_checkin = null;
        $pendaftar->status_hadiah = 'belum';
        $pendaftar->waktu_ambil_hadiah = null;
        $pendaftar->save();

        return back()->with('success', 'Status kehadiran berhasil dibatalkan.');
    }

    public function hapusData($id)
    {
        if (!session('admin_logged_in') || session('admin_role') !== 'superadmin') {
            return back()->with('error', 'Anda tidak memiliki akses untuk menghapus data. Silakan login menggunakan password superadmin.');
        }

        $pendaftar = \App\Models\Pendaftaran::findOrFail($id);
        $pendaftar->delete();

        return back()->with('success', 'Data pendaftaran ganda berhasil dihapus secara permanen.');
    }

    public function showBarcode($id)
    {
        if (!session('admin_logged_in') || session('admin_role') !== 'superadmin') {
            abort(403, 'Akses ditolak.');
        }

        $pendaftaran = \App\Models\Pendaftaran::findOrFail($id);
        $svg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(250)->margin(4)->generate($pendaftaran->kode_registrasi);
        
        return response($svg)->header('Content-Type', 'image/svg+xml');
    }

    public function logout()
    {
        session()->forget('admin_logged_in');
        session()->forget('admin_role');
        return redirect()->route('landing');
    }

    public function showImportJadwal()
    {
        if (!session('admin_logged_in') || session('admin_role') !== 'superadmin') {
            return redirect()->route('admin.login')->with('error', 'Silakan login sebagai superadmin.');
        }

        return view('admin.import-jadwal');
    }

    public function processImportJadwal(Request $request)
    {
        if (!session('admin_logged_in') || session('admin_role') !== 'superadmin') {
            abort(403);
        }

        $request->validate([
            'jadwal_data' => 'required|string',
        ]);

        $lines = explode("\n", trim($request->jadwal_data));
        $countSuccess = 0;
        $countNotFound = 0;

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;

            $parts = explode("\t", $line);
            if (count($parts) >= 3) {
                $siswaId = trim($parts[0]);
                $hariInput = trim($parts[1]);
                $jamInput = trim($parts[2]);

                // Mapping hari
                $hariMapped = $hariInput;
                if (strtolower($hariInput) === 'kamis') {
                    $hariMapped = 'Kamis, 25/06/2026';
                } elseif (strtolower($hariInput) === 'jumaat' || strtolower($hariInput) === 'jum\'at' || strtolower($hariInput) === 'jumat') {
                    $hariMapped = 'Jumat, 26/06/2026';
                }

                $updated = \App\Models\Pendaftaran::where('siswa_id', $siswaId)->update([
                    'jadwal_hari' => $hariMapped,
                    'jadwal_jam' => $jamInput
                ]);

                if ($updated) {
                    $countSuccess++;
                } else {
                    $countNotFound++;
                }
            }
        }

        return redirect()->route('admin.importJadwal')->with('success', "Selesai! Berhasil update jadwal untuk $countSuccess peserta. (Gagal / Tidak ditemukan: $countNotFound)");
    }

    public function runMigration()
    {
        if (!session('admin_logged_in') || session('admin_role') !== 'superadmin') {
            abort(403);
        }

        try {
            \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            $output = \Illuminate\Support\Facades\Artisan::output();
            return back()->with('success', 'Migrasi database berhasil dijalankan di server production! Output: ' . $output);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menjalankan migrasi: ' . $e->getMessage());
        }
    }
}
