@extends('layouts.app')
@section('title', 'Pilih Jalur Pendaftaran')

@section('content')
<div class="card text-center" style="padding: 3rem 2rem;">
    <h2 class="header-title" style="color: var(--primary-dark); margin-bottom: 2rem;">Pilih Jalur Pendaftaran</h2>
    
    <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;">
        <a href="{{ route('login') }}" class="btn-primary" style="padding: 1.5rem; display: flex; flex-direction: column; gap: 0.5rem; text-decoration: none;">
            <span style="font-size: 1.25rem; font-weight: bold;">Siswa Dareliman</span>
            <span style="font-size: 0.875rem; font-weight: normal; opacity: 0.9;">Untuk siswa yang sudah terdaftar di Jenjang TAUD / TK / MITSAQU / SDIT Dareliman</span>
        </a>
        
        <a href="{{ route('umum.daftar') }}" class="btn-primary" style="background-color: var(--warning); padding: 1.5rem; display: flex; flex-direction: column; gap: 0.5rem; text-decoration: none;">
            <span style="font-size: 1.25rem; font-weight: bold;">Masyarakat Umum</span>
            <span style="font-size: 0.875rem; font-weight: normal; opacity: 0.9;">Untuk masyarakat umum / bukan siswa Dareliman</span>
        </a>
    </div>

    <div class="mt-4" style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px dashed var(--border);">
        <p class="small text-muted">Sudah mendaftar di jalur Umum?</p>
        <a href="{{ route('umum.login') }}" style="color: var(--primary); font-weight: bold; text-decoration: none;">Cek Barcode Pendaftaran Anda</a>
    </div>
</div>
@endsection
