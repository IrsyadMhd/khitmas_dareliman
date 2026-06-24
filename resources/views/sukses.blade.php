@extends('layouts.app')
@section('title', 'Pendaftaran Berhasil')

@section('content')
<div class="card text-center">
    <div class="success-icon">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
    </div>
    
    <h2 class="header-title" style="color: var(--success); margin-bottom: 0.5rem;">Pendaftaran Berhasil!</h2>
    <p class="text-muted mb-4">Data pendaftaran telah tersimpan dan email konfirmasi telah dikirim.</p>

    <div class="qr-container">
        <div class="kode-badge">{{ $pendaftaran->kode_registrasi }}</div>
        
        <div style="background: white; padding: 1rem; display: inline-block; border-radius: 8px;">
            {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->margin(4)->generate($pendaftaran->kode_registrasi) !!}
        </div>
        
        <p class="small mt-4" style="color: var(--text-secondary); line-height: 1.4;">
            <strong>Simpan QR Code ini.</strong><br>
            Tunjukkan kepada panitia saat registrasi kehadiran di lokasi acara.
        </p>

        <div style="margin-top: 1.5rem; margin-bottom: 0.5rem;">
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
            <div class="biodata-label">Jenjang</div>
            <div class="biodata-value">{{ $pendaftaran->jenjang }} {{ $pendaftaran->kelas ? '- Kelas ' . $pendaftaran->kelas : '' }}</div>
        </div>
        <div class="biodata-item">
            <div class="biodata-label">Waktu Pendaftaran</div>
            <div class="biodata-value">{{ $pendaftaran->created_at->isoFormat('D MMMM Y, HH:mm') }}</div>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('logout') }}" class="btn-primary" style="background-color: var(--text-secondary);">Selesai</a>
    </div>
</div>
@endsection
