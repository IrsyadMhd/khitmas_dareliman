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
            {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate($pendaftaran->kode_registrasi) !!}
        </div>
        
        <p class="small mt-4" style="color: var(--text-secondary); line-height: 1.4;">
            <strong>Simpan QR Code ini.</strong><br>
            Tunjukkan kepada panitia saat registrasi kehadiran di lokasi acara.
        </p>
    </div>

    <div class="biodata-section" style="text-align: left;">
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
