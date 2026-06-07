@extends('layouts.app')
@section('title', 'Pengaturan Pendaftaran')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 class="section-title" style="margin: 0;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
            Pengaturan Pendaftaran
        </h2>
        <div>
            <a href="{{ route('admin.laporan') }}" class="btn-primary" style="background: var(--success); padding: 0.5rem 1rem; font-size: 0.875rem; text-decoration: none; margin-right: 0.5rem;">Lihat Laporan Data</a>
            <a href="{{ route('admin.logout') }}" class="btn-primary" style="background: var(--danger); padding: 0.5rem 1rem; font-size: 0.875rem; text-decoration: none;">Keluar</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf

        <!-- JALUR DARELIMAN -->
        <div style="background: var(--bg-page); padding: 1.5rem; border-radius: var(--radius-sm); margin-bottom: 1.5rem; border: 1px solid var(--border);">
            <h3 style="margin-top: 0; color: var(--primary-dark);">Jalur Siswa Dareliman</h3>
            
            <div class="form-group">
                <label class="form-label">Status Pendaftaran</label>
                <select name="dareliman_mode" class="form-select" id="dareliman_mode" onchange="toggleSchedule('dareliman')">
                    <option value="manual_open" {{ $settings['dareliman_mode'] == 'manual_open' ? 'selected' : '' }}>Manual: BUKA</option>
                    <option value="manual_closed" {{ $settings['dareliman_mode'] == 'manual_closed' ? 'selected' : '' }}>Manual: TUTUP</option>
                    <option value="schedule" {{ $settings['dareliman_mode'] == 'schedule' ? 'selected' : '' }}>Otomatis Sesuai Jadwal</option>
                </select>
            </div>

            <div id="dareliman_schedule_group" style="display: {{ $settings['dareliman_mode'] == 'schedule' ? 'block' : 'none' }}; border-left: 3px solid var(--primary); padding-left: 1rem; margin-top: 1rem;">
                <div class="form-group">
                    <label class="form-label">Jadwal Buka</label>
                    <input type="datetime-local" name="dareliman_start" class="form-input" value="{{ $settings['dareliman_start'] }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Jadwal Tutup</label>
                    <input type="datetime-local" name="dareliman_end" class="form-input" value="{{ $settings['dareliman_end'] }}">
                </div>
                <small class="text-muted">Kosongkan jadwal batas akhir jika pendaftaran terus dibuka sejak waktu mulai.</small>
            </div>
        </div>

        <!-- JALUR UMUM -->
        <div style="background: var(--warning-light); padding: 1.5rem; border-radius: var(--radius-sm); margin-bottom: 1.5rem; border: 1px solid var(--warning);">
            <h3 style="margin-top: 0; color: var(--warning);">Jalur Masyarakat Umum</h3>
            
            <div class="form-group">
                <label class="form-label">Status Pendaftaran</label>
                <select name="umum_mode" class="form-select" id="umum_mode" onchange="toggleSchedule('umum')">
                    <option value="manual_open" {{ $settings['umum_mode'] == 'manual_open' ? 'selected' : '' }}>Manual: BUKA</option>
                    <option value="manual_closed" {{ $settings['umum_mode'] == 'manual_closed' ? 'selected' : '' }}>Manual: TUTUP</option>
                    <option value="schedule" {{ $settings['umum_mode'] == 'schedule' ? 'selected' : '' }}>Otomatis Sesuai Jadwal</option>
                </select>
            </div>

            <div id="umum_schedule_group" style="display: {{ $settings['umum_mode'] == 'schedule' ? 'block' : 'none' }}; border-left: 3px solid var(--warning); padding-left: 1rem; margin-top: 1rem;">
                <div class="form-group">
                    <label class="form-label">Jadwal Buka</label>
                    <input type="datetime-local" name="umum_start" class="form-input" value="{{ $settings['umum_start'] }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Jadwal Tutup</label>
                    <input type="datetime-local" name="umum_end" class="form-input" value="{{ $settings['umum_end'] }}">
                </div>
                <small class="text-muted">Waktu mengikuti jam server (WIB).</small>
            </div>
        </div>

        <button type="submit" class="btn-primary btn-block">Simpan Pengaturan</button>
    </form>
</div>
@endsection

@stack('scripts')
<script>
    function toggleSchedule(type) {
        var mode = document.getElementById(type + '_mode').value;
        var group = document.getElementById(type + '_schedule_group');
        if (mode === 'schedule') {
            group.style.display = 'block';
        } else {
            group.style.display = 'none';
        }
    }
</script>
