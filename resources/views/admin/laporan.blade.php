@extends('layouts.app')
@section('title', 'Laporan Pendaftaran')

@section('content')
<div class="card" style="max-width: 1000px; margin: 0 auto; width: 100%;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <h2 class="section-title" style="margin: 0;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
            Laporan Data Pendaftar
        </h2>
        <div>
            <a href="{{ route('admin.settings') }}" class="btn-primary" style="background: var(--text-secondary); padding: 0.5rem 1rem; font-size: 0.875rem; text-decoration: none; margin-right: 0.5rem;">Kembali</a>
            <a href="{{ route('admin.logout') }}" class="btn-primary" style="background: var(--danger); padding: 0.5rem 1rem; font-size: 0.875rem; text-decoration: none;">Keluar</a>
        </div>
    </div>

    <div style="margin-bottom: 1rem; color: var(--text-secondary);">
        Total Pendaftar: <strong>{{ $pendaftarans->count() }}</strong> anak
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem; min-width: 700px;">
            <thead>
                <tr style="background-color: var(--primary-light); color: var(--primary-darker);">
                    <th style="padding: 0.75rem 1rem; border-bottom: 2px solid var(--border);">No</th>
                    <th style="padding: 0.75rem 1rem; border-bottom: 2px solid var(--border);">Kode Reg.</th>
                    <th style="padding: 0.75rem 1rem; border-bottom: 2px solid var(--border);">Jalur</th>
                    <th style="padding: 0.75rem 1rem; border-bottom: 2px solid var(--border);">Nama Anak</th>
                    <th style="padding: 0.75rem 1rem; border-bottom: 2px solid var(--border);">Wali & HP</th>
                    <th style="padding: 0.75rem 1rem; border-bottom: 2px solid var(--border);">Waktu Daftar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendaftarans as $index => $p)
                <tr style="border-bottom: 1px solid var(--border);">
                    <td style="padding: 0.75rem 1rem;">{{ $index + 1 }}</td>
                    <td style="padding: 0.75rem 1rem; font-family: monospace; font-weight: bold; color: var(--primary);">{{ $p->kode_registrasi }}</td>
                    <td style="padding: 0.75rem 1rem;">
                        @if($p->is_umum)
                            <span style="background-color: var(--warning-light); color: var(--warning); padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: bold;">UMUM</span>
                        @else
                            <span style="background-color: var(--success-light); color: var(--success); padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: bold;">DARELIMAN</span>
                        @endif
                    </td>
                    <td style="padding: 0.75rem 1rem;">
                        {{ $p->nama_lengkap }}<br>
                        <small style="color: var(--text-muted);">{{ $p->tempat_lahir }}, {{ $p->tanggal_lahir->format('d/m/Y') }}</small>
                    </td>
                    <td style="padding: 0.75rem 1rem;">
                        {{ $p->nama_wali }}<br>
                        <small style="color: var(--text-muted);">{{ $p->hp_wali }}</small>
                    </td>
                    <td style="padding: 0.75rem 1rem; color: var(--text-secondary);">
                        {{ $p->created_at->format('d/m/Y H:i') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding: 2rem 1rem; text-align: center; color: var(--text-muted);">Belum ada data pendaftar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
