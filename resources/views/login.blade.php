@extends('layouts.app')
@section('title', 'Login')

@section('content')
<div class="card">
    <h2 class="section-title">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
        Masuk dengan Akun SIPDEI Daareliman 
    </h2>
    
    <div class="alert alert-info">
        Gunakan NIS dan password akun SIPDEI Daareliman Anak untuk memverifikasi identitas dan memulai pendaftaran.
    </div>

    @error('login')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <form action="{{ route('login.process') }}" method="POST" id="loginForm">
        @csrf
        
        <div class="form-group">
            <label for="email" class="form-label">NIS</label>
            <input type="email" name="email" id="email" class="form-input" value="{{ old('email') }}" required autofocus autocomplete="email">
            @error('email')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-input" required>
            @error('password')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn-primary btn-block mt-4" id="submitBtn">
            Masuk / Verifikasi
        </button>
        
        <div class="text-center mt-4">
            <p class="text-muted" style="font-size: 0.9rem; margin-bottom: 5px;">
                Jika Lupa Password Silakan Klik 
                <a href="https://sipdei.dareliman.tech/reset_password" target="_blank" style="color: #007bff; text-decoration: underline; font-weight: bold;">Disini</a>
            </p>
        </div>
        
        <div class="text-center mt-2">
            <a href="{{ route('landing') }}" class="text-muted" style="text-decoration: none;">Kembali ke Pilihan Jalur</a>
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