@extends('layouts.app')
@section('title', 'Login Scanner Panitia')

@section('content')
<div class="card" style="max-width: 400px; margin: 0 auto;">
    <h2 class="section-title text-center" style="justify-content: center; color: var(--primary);">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7V5a2 2 0 0 1 2-2h2"/><path d="M17 3h2a2 2 0 0 1 2 2v2"/><path d="M21 17v2a2 2 0 0 1-2 2h-2"/><path d="M7 21H5a2 2 0 0 1-2-2v-2"/><rect x="7" y="7" width="10" height="10" rx="1"/></svg>
        Akses Scanner
    </h2>
    <p class="text-center text-muted small mb-4">Silakan masukkan password panitia untuk mengakses fitur scanner kehadiran.</p>
    
    @error('password')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <form action="{{ route('scanner.login.process') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="password" class="form-label">Password Akses</label>
            <input type="password" name="password" id="password" class="form-input" required autofocus>
        </div>

        <button type="submit" class="btn-primary btn-block mt-4">Masuk Scanner</button>
    </form>
</div>
@endsection
