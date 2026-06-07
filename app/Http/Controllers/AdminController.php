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

        // Password statis rahasia
        if ($request->password === '111213') {
            session(['admin_logged_in' => true]);
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

    public function showLaporan()
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $pendaftarans = \App\Models\Pendaftaran::orderBy('created_at', 'desc')->get();
        return view('admin.laporan', compact('pendaftarans'));
    }

    public function logout()
    {
        session()->forget('admin_logged_in');
        return redirect()->route('landing');
    }
}
