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
        <div class="biodata-item" style="background: var(--primary-light); padding: 0.75rem; border-radius: 6px; border-left: 4px solid var(--primary); margin-bottom: 0.75rem;">
            <div class="biodata-label" style="color: var(--primary-dark); font-weight: bold; margin-bottom: 0.25rem;">📅 Jadwal Kehadiran</div>
            <div class="biodata-value" style="font-weight: bold; font-size: 1.1rem; color: var(--primary);">
                {{ $pendaftaran->jadwal_hari ? $pendaftaran->jadwal_hari : 'Menunggu Jadwal' }} - {{ $pendaftaran->jadwal_jam ? $pendaftaran->jadwal_jam : '-' }}
            </div>
        </div>
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
        
        <div style="margin-top: 1rem; margin-bottom: 0.5rem;">
            <a href="https://whatsapp.com/channel/0029Vb8ZoI4HrDZXhbYLVd2p" target="_blank" class="btn-primary" style="background-color: #25D366; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 8px; width: 100%;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                Silakan Join Saluran Ini!!
            </a>
            
            <div style="background-color: var(--primary-light); color: var(--primary-dark); padding: 0.75rem; border-radius: 8px; border: 1px solid var(--primary); margin-top: 1rem; font-size: 0.875rem; text-align: left; line-height: 1.4;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: text-bottom; margin-right: 4px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                Silakan ikuti saluran ini untuk mendapatkan informasi resmi dan pembaruan terkini mengenai kegiatan Khitanan Massal 2026.
            </div>
        </div>
    </div>
</div>
@endforeach

<div class="text-center" style="margin-bottom: 2rem;">
    <a href="{{ route('umum.daftar') }}" class="btn-primary" style="background-color: var(--warning); margin-bottom: 1rem; display: inline-flex;">Daftarkan Anak Lainnya</a>
    <br>
    <a href="{{ route('umum.logout') }}" class="btn-primary" style="background-color: var(--text-secondary);">Keluar (Selesai)</a>
</div>
@endsection
