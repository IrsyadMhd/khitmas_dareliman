@extends('layouts.app')
@section('title', 'Dashboard Pendaftaran Umum')

@section('content')
<div class="card text-center" style="margin-bottom: 1.5rem;">
    <div class="success-icon" style="margin: 0 auto 1rem; width: 48px; height: 48px;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
    </div>
    
    <h2 class="header-title" style="color: var(--primary-dark); font-size: 1.25rem;">Data Pendaftaran Anda</h2>
    <p class="text-muted" style="margin-bottom: 0;">No HP: {{ session('umum_hp_wali') }}</p>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@foreach($pendaftarans as $index => $pendaftaran)
<div class="card" style="margin-bottom: 1.5rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border); padding-bottom: 1rem; margin-bottom: 1rem;">
        <h3 style="margin: 0; font-size: 1.125rem;">Anak ke-{{ $index + 1 }}</h3>
        <span class="kode-badge" style="margin: 0; padding: 0.25rem 0.75rem; font-size: 0.875rem;">{{ $pendaftaran->kode_registrasi }}</span>
    </div>

    <div class="biodata-section" style="text-align: left;">
        <div class="biodata-item">
            <div class="biodata-label">Nama Peserta</div>
            <div class="biodata-value">{{ $pendaftaran->nama_lengkap }}</div>
        </div>
        <div class="biodata-item">
            <div class="biodata-label">Tempat, Tanggal Lahir</div>
            <div class="biodata-value">{{ $pendaftaran->tempat_lahir }}, {{ $pendaftaran->tanggal_lahir->format('d/m/Y') }}</div>
        </div>
    </div>

    <div class="qr-container" style="text-align: center; margin-top: 1.5rem;">
        <div style="background: white; padding: 1rem; display: inline-block; border-radius: 8px;">
            {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(150)->generate($pendaftaran->kode_registrasi) !!}
        </div>
        <p class="small mt-2" style="color: var(--text-secondary); line-height: 1.4;">
            Tunjukkan QR Code ini saat registrasi kehadiran.
        </p>
    </div>
</div>
@endforeach

<div class="text-center" style="margin-bottom: 2rem;">
    <a href="{{ route('umum.daftar') }}" class="btn-primary" style="background-color: var(--warning); margin-bottom: 1rem; display: inline-flex;">Daftarkan Anak Lainnya</a>
    <br>
    <a href="{{ route('umum.logout') }}" class="btn-primary" style="background-color: var(--text-secondary);">Keluar (Selesai)</a>
</div>
@endsection
