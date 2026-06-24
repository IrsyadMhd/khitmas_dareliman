@extends('layouts.app')
@section('title', 'Pendaftaran Ditutup')

@section('content')
<div class="card text-center" style="padding: 3rem 2rem;">
    <div style="margin-bottom: 1.5rem; color: var(--danger);">
        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    </div>
    
    <h2 class="header-title" style="color: var(--text-primary); margin-bottom: 1rem;">Mohon Maaf</h2>
    <p style="font-size: 1.125rem; color: var(--text-secondary); margin-bottom: 2rem;">
        Pendaftaran baru saat ini sedang ditutup atau <strong style="color: var(--danger);">Kuota Penuh</strong>.
    </p>

    <div style="background-color: var(--bg-page); padding: 1.5rem; border-radius: var(--radius-sm); border: 1px dashed var(--border); margin-bottom: 2rem; text-align: center;">
        <p style="margin-top: 0; margin-bottom: 1rem; color: var(--text-primary); font-weight: 500;">
            Sudah mendaftar sebelumnya? Cek bukti pendaftaran Anda:
        </p>
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <a href="{{ route('login') }}" class="btn-primary" style="text-decoration: none;">Cek Barcode (Siswa Dareliman)</a>
            <a href="{{ route('umum.login') }}" class="btn-primary" style="background-color: var(--warning); text-decoration: none;">Cek Barcode (Masyarakat Umum)</a>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('landing') }}" class="text-muted" style="text-decoration: none; font-size: 0.875rem;">Kembali ke Halaman Utama</a>
    </div>
</div>
@endsection
