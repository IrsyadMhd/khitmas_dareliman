@extends('layouts.app')
@section('title', 'Pendaftaran Umum')

@section('content')
<div class="card">
    <h2 class="section-title">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
        Form Pendaftaran (Jalur Umum)
    </h2>

    <div class="alert alert-info">
        Mohon isi data dengan benar. Anda dapat mendaftarkan lebih dari satu anak menggunakan Nomor HP Wali yang sama.
    </div>

    <form action="{{ route('umum.daftar.submit') }}" method="POST" id="daftarForm">
        @csrf

        <div style="margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px dashed var(--border);">
            <h3 style="font-size: 1rem; color: var(--text-secondary); margin-bottom: 1rem;">1. Data Anak (Peserta)</h3>
            
            <div class="form-group">
                <label for="nama_lengkap" class="form-label">Nama Lengkap Anak <span class="text-danger">*</span></label>
                <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-input" value="{{ old('nama_lengkap') }}" required>
                @error('nama_lengkap')<span class="error-text">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="tempat_lahir" class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-input" value="{{ old('tempat_lahir') }}" required>
                @error('tempat_lahir')<span class="error-text">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-input" value="{{ old('tanggal_lahir') }}" required>
                @error('tanggal_lahir')<span class="error-text">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                <select name="jenis_kelamin" class="form-select" required>
                    <option value="Laki-laki">Laki-laki</option>
                </select>
                <small class="text-muted mt-1" style="display:block">Hanya untuk peserta Laki-laki.</small>
            </div>
        </div>

        <div>
            <h3 style="font-size: 1rem; color: var(--text-secondary); margin-bottom: 1rem;">2. Data Orang Tua / Wali</h3>

            <div class="form-group">
                <label for="nama_wali" class="form-label">Nama Orang Tua / Wali <span class="text-danger">*</span></label>
                <input type="text" name="nama_wali" id="nama_wali" class="form-input" value="{{ old('nama_wali', session('umum_hp_wali') ? \App\Models\Pendaftaran::where('hp_wali', session('umum_hp_wali'))->value('nama_wali') : '') }}" required>
                @error('nama_wali')<span class="error-text">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="hp_wali" class="form-label">Nomor HP / WhatsApp Wali <span class="text-danger">*</span></label>
                <input type="tel" name="hp_wali" id="hp_wali" class="form-input" value="{{ old('hp_wali', session('umum_hp_wali') ?? '') }}" required placeholder="Contoh: 081234567890">
                <small class="text-muted mt-1" style="display:block">4 digit terakhir nomor HP ini akan digunakan sebagai Password untuk cek Barcode.</small>
                @error('hp_wali')<span class="error-text">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" id="email" class="form-input" value="{{ old('email', session('umum_hp_wali') ? \App\Models\Pendaftaran::where('hp_wali', session('umum_hp_wali'))->value('email') : '') }}" required placeholder="contoh@email.com">
                <small class="text-muted mt-1" style="display:block">QR Code pendaftaran akan dikirim ke email ini.</small>
                @error('email')<span class="error-text">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="alamat" class="form-label">Alamat Domisili <span class="text-danger">*</span></label>
                <textarea name="alamat" id="alamat" class="form-textarea" required>{{ old('alamat', session('umum_hp_wali') ? \App\Models\Pendaftaran::where('hp_wali', session('umum_hp_wali'))->value('alamat') : '') }}</textarea>
                @error('alamat')<span class="error-text">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="consent-group">
            <input type="checkbox" name="consent_wali" id="consent_wali" value="1" {{ old('consent_wali') ? 'checked' : '' }} required>
            <label for="consent_wali">
                Saya menyetujui anak saya mengikuti kegiatan Khitanan Massal yang diselenggarakan oleh Dareliman Peduli dan Surau TV. Data yang saya isi adalah benar dan dapat dipertanggungjawabkan.
            </label>
        </div>
        @error('consent_wali')<span class="error-text">{{ $message }}</span>@enderror

        <button type="submit" class="btn-primary btn-block mt-4" id="submitBtn">
            Daftarkan Peserta
        </button>
        
        <div class="text-center mt-4">
            <a href="{{ route('landing') }}" class="text-muted" style="text-decoration: none;">Kembali</a>
        </div>
    </form>
</div>
@endsection

@stack('scripts')
<script>
    document.getElementById('daftarForm').addEventListener('submit', function(e) {
        if(!document.getElementById('consent_wali').checked) {
            e.preventDefault();
            alert('Anda harus menyetujui pernyataan persetujuan wali sebelum mendaftar.');
            return;
        }
        var btn = document.getElementById('submitBtn');
        btn.classList.add('btn-loading');
        btn.textContent = 'Menyimpan Pendaftaran...';
    });
</script>
