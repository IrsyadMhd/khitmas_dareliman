@extends('layouts.app')
@section('title', 'Peserta Sudah Ambil Hadiah')

@section('content')
<div class="card" style="max-width: 1100px; margin: 0 auto; width: 100%;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <h2 class="section-title" style="margin: 0;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><line x1="12" y1="22" x2="12" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>
            Peserta Sudah Ambil Hadiah
        </h2>
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; justify-content: flex-end;">
            <a href="{{ route('scanner.hadiah.index') }}" target="_blank" class="btn-primary" style="background: var(--primary); padding: 0.5rem 1rem; font-size: 0.875rem; text-decoration: none;">Scanner Hadiah</a>
            <a href="{{ route('admin.laporan') }}" class="btn-primary" style="background: var(--success); padding: 0.5rem 1rem; font-size: 0.875rem; text-decoration: none;">Laporan Data</a>
            <a href="{{ route('admin.settings') }}" class="btn-primary" style="background: var(--text-secondary); padding: 0.5rem 1rem; font-size: 0.875rem; text-decoration: none;">Kembali</a>
            <a href="{{ route('admin.logout') }}" class="btn-primary" style="background: var(--danger); padding: 0.5rem 1rem; font-size: 0.875rem; text-decoration: none;">Keluar</a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
        <div style="background: var(--success-light, #ecfdf5); border: 1px solid var(--success); border-radius: var(--radius-sm); padding: 1rem;">
            <div style="color: var(--text-secondary); font-size: 0.85rem;">Total Diambil</div>
            <div style="font-size: 1.75rem; font-weight: 700; color: var(--success);">{{ $totalSemua }}</div>
        </div>
        <div style="background: var(--bg-page); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 1rem;">
            <div style="color: var(--text-secondary); font-size: 0.85rem;">Dareliman</div>
            <div style="font-size: 1.75rem; font-weight: 700; color: var(--primary);">{{ $totalDareliman }}</div>
        </div>
        <div style="background: var(--warning-light, #fff7ed); border: 1px solid var(--warning); border-radius: var(--radius-sm); padding: 1rem;">
            <div style="color: var(--text-secondary); font-size: 0.85rem;">Umum</div>
            <div style="font-size: 1.75rem; font-weight: 700; color: var(--warning);">{{ $totalUmum }}</div>
        </div>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 1rem;">
        <div style="color: var(--text-secondary);">
            Ditampilkan: <strong>{{ $pendaftarans->count() }}</strong> peserta
        </div>
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <a href="{{ route('admin.hadiah.diambil') }}" class="btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; text-decoration: none; {{ empty($jalurFilter) ? '' : 'background: white; color: var(--primary); border: 1px solid var(--primary);' }}">Semua</a>
            <a href="{{ route('admin.hadiah.diambil', ['jalur' => 'dareliman']) }}" class="btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; text-decoration: none; {{ $jalurFilter === 'dareliman' ? 'background: var(--primary);' : 'background: white; color: var(--primary); border: 1px solid var(--primary);' }}">Dareliman</a>
            <a href="{{ route('admin.hadiah.diambil', ['jalur' => 'umum']) }}" class="btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; text-decoration: none; {{ $jalurFilter === 'umum' ? 'background: var(--warning);' : 'background: white; color: var(--warning); border: 1px solid var(--warning);' }}">Umum</a>
        </div>
        <div style="flex-grow: 1; max-width: 320px; min-width: 220px;">
            <input type="text" id="searchInput" onkeyup="filterHadiah()" class="form-input" placeholder="Cari nama, kode, wali, hp..." style="padding: 0.5rem 1rem; border-radius: 20px;">
        </div>
    </div>

    <div class="desktop-view" style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem; min-width: 760px;">
            <thead>
                <tr style="background-color: var(--primary-light); color: var(--primary-darker);">
                    <th style="padding: 0.75rem 1rem; border-bottom: 2px solid var(--border);">No</th>
                    <th style="padding: 0.75rem 1rem; border-bottom: 2px solid var(--border);">Waktu Ambil</th>
                    <th style="padding: 0.75rem 1rem; border-bottom: 2px solid var(--border);">Kode Reg.</th>
                    <th style="padding: 0.75rem 1rem; border-bottom: 2px solid var(--border);">Nama Anak</th>
                    <th style="padding: 0.75rem 1rem; border-bottom: 2px solid var(--border);">Jalur</th>
                    <th style="padding: 0.75rem 1rem; border-bottom: 2px solid var(--border);">Wali & HP</th>
                    <th style="padding: 0.75rem 1rem; border-bottom: 2px solid var(--border);">Check-in</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendaftarans as $p)
                <tr class="hadiah-row" data-search="{{ strtolower($p->nama_lengkap . ' ' . $p->kode_registrasi . ' ' . $p->nama_wali . ' ' . $p->hp_wali . ' ' . $p->email) }}" style="border-bottom: 1px solid var(--border);">
                    <td style="padding: 0.75rem 1rem;">{{ $loop->iteration }}</td>
                    <td style="padding: 0.75rem 1rem; font-weight: 600; color: var(--success);">
                        {{ $p->waktu_ambil_hadiah ? $p->waktu_ambil_hadiah->format('d/m/Y H:i') : '-' }}
                    </td>
                    <td style="padding: 0.75rem 1rem; font-family: monospace;">{{ $p->kode_registrasi }}</td>
                    <td style="padding: 0.75rem 1rem; font-weight: 600;">{{ $p->nama_lengkap }}</td>
                    <td style="padding: 0.75rem 1rem;">
                        <span style="padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 700; background: {{ $p->is_umum ? 'var(--warning-light, #fff7ed)' : 'var(--primary-light)' }}; color: {{ $p->is_umum ? 'var(--warning)' : 'var(--primary)' }};">
                            {{ $p->is_umum ? 'UMUM' : 'DARELIMAN' }}
                        </span>
                    </td>
                    <td style="padding: 0.75rem 1rem;">{{ $p->nama_wali }}<br><small>{{ $p->hp_wali }}</small></td>
                    <td style="padding: 0.75rem 1rem;">{{ $p->waktu_checkin ? $p->waktu_checkin->format('d/m/Y H:i') : '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 2rem; text-align: center; color: var(--text-muted);">Belum ada peserta yang mengambil hadiah.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mobile-view">
        @forelse($pendaftarans as $p)
        <div class="card hadiah-card" data-search="{{ strtolower($p->nama_lengkap . ' ' . $p->kode_registrasi . ' ' . $p->nama_wali . ' ' . $p->hp_wali . ' ' . $p->email) }}" style="margin-bottom: 1rem; padding: 1rem; border-left: 4px solid var(--success);">
            <div style="display: flex; justify-content: space-between; gap: 0.75rem; align-items: start;">
                <div>
                    <strong>{{ $p->nama_lengkap }}</strong><br>
                    <small style="font-family: monospace; color: var(--text-secondary);">{{ $p->kode_registrasi }}</small>
                </div>
                <span style="padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 700; background: {{ $p->is_umum ? 'var(--warning-light, #fff7ed)' : 'var(--primary-light)' }}; color: {{ $p->is_umum ? 'var(--warning)' : 'var(--primary)' }}; white-space: nowrap;">
                    {{ $p->is_umum ? 'UMUM' : 'DARELIMAN' }}
                </span>
            </div>
            <div style="margin-top: 0.75rem; color: var(--text-secondary); font-size: 0.875rem;">
                <div><strong>Ambil hadiah:</strong> {{ $p->waktu_ambil_hadiah ? $p->waktu_ambil_hadiah->format('d/m/Y H:i') : '-' }}</div>
                <div><strong>Wali:</strong> {{ $p->nama_wali }} ({{ $p->hp_wali }})</div>
                <div><strong>Check-in:</strong> {{ $p->waktu_checkin ? $p->waktu_checkin->format('d/m/Y H:i') : '-' }}</div>
            </div>
        </div>
        @empty
        <div style="padding: 2rem; text-align: center; color: var(--text-muted);">Belum ada peserta yang mengambil hadiah.</div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script>
    function filterHadiah() {
        const filter = document.getElementById('searchInput').value.toLowerCase();

        document.querySelectorAll('.hadiah-row').forEach(row => {
            row.style.display = row.dataset.search.includes(filter) ? '' : 'none';
        });

        document.querySelectorAll('.hadiah-card').forEach(card => {
            card.style.display = card.dataset.search.includes(filter) ? 'block' : 'none';
        });
    }
</script>
@endpush

@push('styles')
<style>
    @media (min-width: 1024px) {
        main.container { max-width: 95% !important; padding: 2rem !important; }
    }

    .mobile-view { display: none; }
    .desktop-view { display: block; }

    @media (max-width: 768px) {
        .mobile-view { display: block; }
        .desktop-view { display: none; }
        .card { padding: 1rem !important; }
        .section-title { font-size: 1.1rem; flex-wrap: wrap; }
    }
</style>
@endpush
