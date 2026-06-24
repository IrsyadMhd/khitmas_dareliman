@extends('layouts.app')
@section('title', 'Lihat Barcode - Admin')

@section('content')
<div class="card text-center" style="max-width: 500px; margin: 0 auto;">
    <h2 class="section-title" style="justify-content: center; margin-bottom: 0.5rem; color: var(--primary);">
        Barcode Kehadiran
    </h2>
    <p class="text-muted" style="margin-bottom: 2rem;">Kode: <strong>{{ $pendaftaran->kode_registrasi }}</strong></p>

    <div style="background: white; padding: 2rem; display: inline-block; border-radius: 8px; border: 1px dashed var(--border);">
        {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(250)->generate($pendaftaran->kode_registrasi) !!}
    </div>

    <div style="margin-top: 2rem; text-align: left; background: var(--bg-page); padding: 1rem; border-radius: 8px;">
        <div style="margin-bottom: 0.5rem;"><strong>Nama:</strong> {{ $pendaftaran->nama_lengkap }}</div>
        <div style="margin-bottom: 0.5rem;"><strong>Wali:</strong> {{ $pendaftaran->nama_wali }} ({{ $pendaftaran->hp_wali }})</div>
        <div style="margin-bottom: 0.5rem;"><strong>Jalur:</strong> {{ $pendaftaran->is_umum ? 'UMUM' : 'DARELIMAN' }}</div>
    </div>

    <div style="margin-top: 2rem;">
        <button onclick="window.close()" class="btn-primary" style="background: var(--text-secondary);">Tutup Jendela Ini</button>
    </div>
</div>
@endsection
