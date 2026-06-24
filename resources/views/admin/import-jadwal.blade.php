@extends('layouts.app')
@section('title', 'Import Jadwal')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto; width: 100%;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <h2 class="section-title" style="margin: 0; display: flex; align-items: center; gap: 0.5rem;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            Import Jadwal Peserta
        </h2>
        <a href="{{ route('admin.settings') }}" class="btn-primary" style="background: var(--text-muted); padding: 0.5rem 1rem; font-size: 0.875rem; text-decoration: none;">Kembali</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success" style="background: var(--success-light); color: var(--success); border: 1px solid var(--success); padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
            {{ session('success') }}
        </div>
    @endif

    <div style="background: var(--bg-page); padding: 1.5rem; border-radius: var(--radius-md); border: 1px solid var(--border);">
        <p style="margin-top: 0;">Copy tabel dari Excel/Spreadsheet (minimal terdiri dari 3 kolom: <strong>Siswa ID, Hari, Jam</strong>) dan Paste ke dalam kotak di bawah ini.</p>
        
        <form action="{{ route('admin.importJadwal.process') }}" method="POST">
            @csrf
            <div style="margin-bottom: 1rem;">
                <textarea name="jadwal_data" rows="15" style="width: 100%; width: -moz-available; width: -webkit-fill-available; width: fill-available; padding: 1rem; border-radius: 4px; border: 1px solid var(--border); font-family: monospace; font-size: 0.9rem; resize: vertical;" placeholder="Contoh:&#10;12010&#9;Kamis&#9;07.00&#10;UMM-763068&#9;Jum'at&#9;08.00" required></textarea>
            </div>
            
            <button type="submit" class="btn-primary" style="width: 100%; display: flex; justify-content: center; align-items: center; gap: 0.5rem; padding: 0.75rem;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                Proses Import Jadwal
            </button>
        </form>
    </div>
</div>
@endsection
