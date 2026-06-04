@extends('layouts.app')
@section('title', 'Form Pendaftaran')

@section('content')
<!-- Biodata Card -->
<div class="card">
    <h2 class="section-title">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        Data Peserta
    </h2>

    <div class="biodata-section">
        <div class="biodata-item">
            <div class="biodata-label">ID Siswa</div>
            <div class="biodata-value">{{ $siswa['id'] }}</div>
        </div>
        <div class="biodata-item">
            <div class="biodata-label">Nama Lengkap</div>
            <div class="biodata-value">{{ $siswa['nama_lengkap'] }}</div>
        </div>
        <div class="biodata-item">
            <div class="biodata-label">Tempat, Tanggal Lahir</div>
            <div class="biodata-value">{{ $siswa['tempat_lahir'] }}, {{ \Carbon\Carbon::parse($siswa['tanggal_lahir'])->isoFormat('D MMMM Y') }}</div>
        </div>
        <div class="biodata-item">
            <div class="biodata-label">Jenis Kelamin</div>
            <div class="biodata-value">{{ $siswa['jenis_kelamin'] }}</div>
        </div>
        <div class="biodata-item">
            <div class="biodata-label">Status Siswa</div>
            <div class="biodata-value" style="color: var(--success);">{{ $siswa['status_siswa'] }}</div>
        </div>
    </div>
</div>

<!-- Additional Data Form -->
<div class="card">
    <h2 class="section-title">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
        Data Tambahan & Wali
    </h2>

    <form action="{{ route('daftar.submit') }}" method="POST" id="daftarForm">
        @csrf
        
        <!-- Hidden required fields from API data -->
        <input type="hidden" name="siswa_id" value="{{ $siswa['id'] }}">
        <input type="hidden" name="nama_lengkap" value="{{ $siswa['nama_lengkap'] }}">
        <input type="hidden" name="tempat_lahir" value="{{ $siswa['tempat_lahir'] }}">
        <input type="hidden" name="tanggal_lahir" value="{{ $siswa['tanggal_lahir'] }}">
        <input type="hidden" name="jenis_kelamin" value="{{ $siswa['jenis_kelamin'] }}">
        <input type="hidden" name="email" value="{{ $siswa['email'] }}">
        <input type="hidden" name="nomor_wa" value="{{ $siswa['nomor_wa'] ?? '' }}">
        <input type="hidden" name="foto" value="{{ $siswa['foto'] ?? '' }}">
        <input type="hidden" name="id_jenis_sekolah" value="{{ $siswa['id_jenis_sekolah'] ?? '' }}">
        <input type="hidden" name="status_siswa" value="{{ $siswa['status_siswa'] }}">

        <div class="form-group">
            <label for="nama_wali" class="form-label">Nama Orang Tua / Wali <span class="text-danger">*</span></label>
            <input type="text" name="nama_wali" id="nama_wali" class="form-input" value="{{ old('nama_wali') }}" required>
            @error('nama_wali')<span class="error-text">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="hp_wali" class="form-label">Nomor HP / WhatsApp Wali (Opsional jika ingin dirubah)</label>
            <input type="tel" name="hp_wali" id="hp_wali" class="form-input" value="{{ old('hp_wali', $siswa['nomor_wa'] ?? '') }}" placeholder="Contoh: 081234567890">
            @error('hp_wali')<span class="error-text">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="email_edit" class="form-label">Email (Opsional jika ingin dirubah)</label>
            <input type="email" name="email_edit" id="email_edit" class="form-input" value="{{ old('email_edit', $siswa['email'] ?? '') }}" placeholder="contoh@email.com">
            @error('email_edit')<span class="error-text">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="alamat" class="form-label">Alamat Domisili <span class="text-danger">*</span></label>
            <textarea name="alamat" id="alamat" class="form-textarea" required>{{ old('alamat') }}</textarea>
            @error('alamat')<span class="error-text">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="kelas" class="form-label">Kelas (Opsional)</label>
            <input type="text" name="kelas" id="kelas" class="form-input" value="{{ old('kelas') }}" placeholder="Contoh: 1, 2, 3, A, B">
            @error('kelas')<span class="error-text">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="riwayat_kesehatan" class="form-label">Riwayat Kesehatan Singkat (Opsional)</label>
            <textarea name="riwayat_kesehatan" id="riwayat_kesehatan" class="form-textarea" placeholder="Sebutkan jika ada alergi obat, hemofilia, atau kondisi medis khusus lainnya.">{{ old('riwayat_kesehatan') }}</textarea>
            @error('riwayat_kesehatan')<span class="error-text">{{ $message }}</span>@enderror
        </div>

        <div class="consent-group">
            <input type="checkbox" name="consent_wali" id="consent_wali" value="1" {{ old('consent_wali') ? 'checked' : '' }} required>
            <label for="consent_wali">
                Saya sebagai wali menyetujui anak saya mengikuti kegiatan Khitanan Massal yang diselenggarakan oleh Dareliman Peduli dan Surau TV. Data yang saya isi adalah benar dan dapat dipertanggungjawabkan.
            </label>
        </div>
        @error('consent_wali')<span class="error-text">{{ $message }}</span>@enderror

        <button type="submit" class="btn-primary btn-block mt-4" id="submitBtn">
            Daftarkan Peserta
        </button>
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
