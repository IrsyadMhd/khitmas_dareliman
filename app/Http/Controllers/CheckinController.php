<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use Illuminate\Http\Request;

class CheckinController extends Controller
{
    /**
     * Tampilkan form login untuk halaman scanner
     */
    public function showLogin()
    {
        if (session('scanner_logged_in')) {
            return redirect()->route('scanner.index');
        }
        return view('scanner.login');
    }

    /**
     * Proses login scanner
     */
    public function processLogin(Request $request)
    {
        $request->validate(['password' => 'required']);

        if ($request->password === '111213') {
            session(['scanner_logged_in' => true]);
            return redirect()->route('scanner.index');
        }

        return back()->withErrors(['password' => 'Password salah.']);
    }

    /**
     * Tampilkan antarmuka scanner
     */
    public function showScanner()
    {
        if (!session('scanner_logged_in')) {
            return redirect()->route('scanner.login');
        }
        
        // Count today's check-ins
        $totalHadir = Pendaftaran::where('status_kehadiran', 'hadir')->count();
        
        return view('scanner.index', compact('totalHadir'));
    }

    /**
     * Proses scan via API (AJAX)
     */
    public function processScan(Request $request)
    {
        if (!session('scanner_logged_in')) {
            return response()->json(['success' => false, 'message' => 'Sesi habis, silakan refresh halaman dan login kembali.'], 401);
        }

        $kode = trim($request->input('kode_registrasi'));
        
        if (empty($kode)) {
            return response()->json(['success' => false, 'message' => 'Kode registrasi kosong.']);
        }

        $pendaftaran = Pendaftaran::where('kode_registrasi', $kode)->first();

        if (!$pendaftaran) {
            return response()->json([
                'success' => false, 
                'message' => 'Kode tidak valid atau tidak terdaftar di sistem.'
            ]);
        }

        if ($pendaftaran->status_kehadiran === 'hadir') {
            return response()->json([
                'success' => false,
                'message' => 'sudah scand barcode kehadiran',
                'data' => [
                    'nama' => $pendaftaran->nama_lengkap,
                    'waktu_checkin' => $pendaftaran->waktu_checkin ? $pendaftaran->waktu_checkin->format('H:i:s') : null
                ]
            ]);
        }

        // Tandai hadir
        $pendaftaran->update([
            'status_kehadiran' => 'hadir',
            'waktu_checkin' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kehadiran berhasil dicatat!',
            'data' => [
                'nama' => $pendaftaran->nama_lengkap,
                'jalur' => $pendaftaran->is_umum ? 'UMUM' : 'DARELIMAN',
                'kode' => $pendaftaran->kode_registrasi,
                'jadwal_hari' => $pendaftaran->jadwal_hari,
                'jadwal_jam' => $pendaftaran->jadwal_jam
            ]
        ]);
    }

    public function showMerchandiseScanner()
    {
        if (!session('scanner_logged_in')) {
            return redirect()->route('scanner.login');
        }
        
        $totalHadiah = Pendaftaran::where('status_hadiah', 'sudah')->count();
        
        return view('scanner.merchandise', compact('totalHadiah'));
    }

    public function processMerchandise(Request $request)
    {
        if (!session('scanner_logged_in')) {
            return response()->json(['success' => false, 'message' => 'Sesi habis, silakan refresh halaman dan login kembali.'], 401);
        }

        $kode = trim($request->input('kode_registrasi'));
        
        if (empty($kode)) {
            return response()->json(['success' => false, 'message' => 'Kode registrasi kosong.']);
        }

        $pendaftaran = Pendaftaran::where('kode_registrasi', $kode)->first();

        if (!$pendaftaran) {
            return response()->json([
                'success' => false, 
                'message' => 'Kode tidak valid atau tidak terdaftar di sistem.'
            ]);
        }

        // Syarat 1: Harus sudah check-in (hadir)
        if ($pendaftaran->status_kehadiran !== 'hadir') {
            return response()->json([
                'success' => false,
                'message' => 'belum checkin',
                'submessage' => 'Peserta ini belum mendaftar kehadiran di meja depan!'
            ]);
        }

        // Syarat 2: Belum ambil hadiah
        if ($pendaftaran->status_hadiah === 'sudah') {
            return response()->json([
                'success' => false,
                'message' => 'sudah ambil hadiah',
                'data' => [
                    'nama' => $pendaftaran->nama_lengkap,
                    'waktu_ambil' => $pendaftaran->waktu_ambil_hadiah ? $pendaftaran->waktu_ambil_hadiah->format('H:i:s') : null
                ]
            ]);
        }

        // Tandai sudah ambil
        $pendaftaran->update([
            'status_hadiah' => 'sudah',
            'waktu_ambil_hadiah' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'HADIAH BISA DIBERIKAN!',
            'data' => [
                'nama' => $pendaftaran->nama_lengkap,
                'jalur' => $pendaftaran->is_umum ? 'UMUM' : 'DARELIMAN',
                'kode' => $pendaftaran->kode_registrasi
            ]
        ]);
    }
}
