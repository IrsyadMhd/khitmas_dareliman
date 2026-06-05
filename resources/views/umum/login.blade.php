@extends('layouts.app')
@section('title', 'Login Pendaftar Umum')

@section('content')
<div class="card">
    <h2 class="section-title">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
        Cek Pendaftaran Anda
    </h2>
    
    <div class="alert alert-info">
        Silakan masukkan Nomor HP yang Anda gunakan saat mendaftar, beserta password (4 digit terakhir nomor HP Anda).
    </div>

    @error('login')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <form action="{{ route('umum.login.process') }}" method="POST" id="loginForm">
        @csrf
        
        <div class="form-group">
            <label for="hp_wali" class="form-label">Nomor HP / WhatsApp Wali</label>
            <input type="tel" name="hp_wali" id="hp_wali" class="form-input" value="{{ old('hp_wali') }}" required autofocus placeholder="0812...">
            @error('hp_wali')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Password (4 Digit Terakhir No HP)</label>
            <input type="password" name="password" id="password" class="form-input" required maxlength="4">
            @error('password')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn-primary btn-block mt-4" id="submitBtn">
            Cek Barcode
        </button>
        
        <div class="text-center mt-4">
            <a href="{{ route('landing') }}" class="text-muted" style="text-decoration: none;">Kembali</a>
        </div>
    </form>
</div>
@endsection

@stack('scripts')
<script>
    document.getElementById('loginForm').addEventListener('submit', function() {
        var btn = document.getElementById('submitBtn');
        btn.classList.add('btn-loading');
        btn.textContent = 'Memverifikasi...';
    });
</script>
