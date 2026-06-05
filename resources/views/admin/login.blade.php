@extends('layouts.app')
@section('title', 'Admin Panel - Login')

@section('content')
<div class="card" style="max-width: 400px; margin: 0 auto;">
    <h2 class="section-title text-center" style="justify-content: center;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
        Admin Panel Rahasia
    </h2>
    
    @error('password')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <form action="{{ route('admin.login.process') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="password" class="form-label">Password Akses</label>
            <input type="password" name="password" id="password" class="form-input" required autofocus>
        </div>

        <button type="submit" class="btn-primary btn-block mt-4">
            Masuk
        </button>
    </form>
</div>
@endsection
