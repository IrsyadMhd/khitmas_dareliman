@extends('layouts.app')
@section('title', 'Pendaftaran Ditutup')

@section('content')
<div class="card text-center" style="padding: 3rem 2rem;">
    <div style="margin-bottom: 1.5rem; color: var(--danger);">
        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    </div>
    
    <h2 class="header-title" style="color: var(--text-primary); margin-bottom: 1rem;">Mohon Maaf</h2>
    <p style="font-size: 1.125rem; color: var(--text-secondary); margin-bottom: 2rem;">
        Pendaftaran saat ini sedang ditutup.
    </p>

    <div class="mt-4">
        <a href="{{ route('landing') }}" class="btn-primary" style="background-color: var(--text-secondary); text-decoration: none; padding: 0.75rem 2rem;">Kembali ke Halaman Utama</a>
    </div>
</div>
@endsection
