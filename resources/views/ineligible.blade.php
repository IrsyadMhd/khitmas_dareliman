@extends('layouts.app')
@section('title', 'Tidak Memenuhi Syarat')

@section('content')
<div class="card text-center">
    <div style="color: var(--warning); margin-bottom: 1rem;">
        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
    </div>
    
    <h2 class="header-title" style="color: var(--warning); margin-bottom: 1.5rem;">Maaf, Tidak Dapat Mendaftar</h2>
    
    <div class="alert alert-warning" style="text-align: left; margin-bottom: 2rem;">
        <strong>Keterangan:</strong><br>
        {{ $reason }}
    </div>

    <div style="background: var(--bg-page); padding: 1rem; border-radius: var(--radius-sm); font-size: 0.875rem; color: var(--text-secondary); text-align: left; margin-bottom: 2rem;">
        <p style="margin-top:0;"><strong>Syarat Pendaftaran Khitanan Massal:</strong></p>
        <ul style="margin-bottom:0; padding-left: 1.5rem;">
            <li>Merupakan siswa internal Dareliman.</li>
            <li>Status siswa aktif.</li>
            <li>Berada pada jenjang pendidikan TAUD, TK, atau SD.</li>
        </ul>
    </div>

    <a href="{{ route('login') }}" class="btn-primary btn-block">Kembali ke Halaman Login</a>
</div>
@endsection
