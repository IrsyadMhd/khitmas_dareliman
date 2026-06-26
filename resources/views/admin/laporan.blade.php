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
            <a href="{{ route('admin.hadiah.diambil') }}" class="btn-primary" style="background: #7c3aed; padding: 0.5rem 1rem; font-size: 0.875rem; text-decoration: none; margin-right: 0.5rem;">Hadiah Diambil</a>
            <a href="{{ route('admin.settings') }}" class="btn-primary" style="background: var(--text-secondary); padding: 0.5rem 1rem; font-size: 0.875rem; text-decoration: none; margin-right: 0.5rem;">Kembali</a>
            <a href="{{ route('admin.logout') }}" class="btn-primary" style="background: var(--danger); padding: 0.5rem 1rem; font-size: 0.875rem; text-decoration: none;">Keluar</a>
        </div>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 1rem;">
        <div style="color: var(--text-secondary);">
            Total Data: <strong>{{ $pendaftarans->count() }}</strong> anak
        </div>
        
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <a href="{{ route('admin.laporan', array_filter(['jenkel' => $jenkelFilter])) }}" class="btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; text-decoration: none; {{ empty($statusFilter) ? '' : 'background: white; color: var(--primary); border: 1px solid var(--primary);' }}">Semua</a>
            <a href="{{ route('admin.laporan', array_filter(['status' => 'belum_pesan', 'jenkel' => $jenkelFilter])) }}" class="btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; text-decoration: none; {{ $statusFilter == 'belum_pesan' ? 'background: #25D366; border: 1px solid #25D366;' : 'background: white; color: #25D366; border: 1px solid #25D366;' }}">Belum Di-WA</a>
            <a href="{{ route('admin.laporan', array_filter(['status' => 'hadir', 'jenkel' => $jenkelFilter])) }}" class="btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; text-decoration: none; {{ $statusFilter == 'hadir' ? 'background: var(--success);' : 'background: white; color: var(--success); border: 1px solid var(--success);' }}">Sudah Hadir</a>
            <a href="{{ route('admin.laporan', array_filter(['status' => 'belum_hadir', 'jenkel' => $jenkelFilter])) }}" class="btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; text-decoration: none; {{ $statusFilter == 'belum_hadir' ? 'background: var(--danger);' : 'background: white; color: var(--danger); border: 1px solid var(--danger);' }}">Belum Hadir</a>
            
            @if(session('admin_role') === 'superadmin')
            <a href="{{ route('admin.laporan', array_filter(['status' => 'ganda', 'jenkel' => $jenkelFilter])) }}" class="btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; text-decoration: none; {{ $statusFilter == 'ganda' ? 'background: var(--warning); border: 1px solid var(--warning);' : 'background: white; color: var(--warning); border: 1px solid var(--warning);' }}">Indikasi Ganda</a>
            @endif
        </div>

        <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
            <span style="color: var(--text-secondary); font-size: 0.8rem; font-weight: 600;">Jenkel:</span>
            <a href="{{ route('admin.laporan', array_filter(['status' => $statusFilter])) }}" class="btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; text-decoration: none; {{ empty($jenkelFilter) ? 'background: var(--primary);' : 'background: white; color: var(--primary); border: 1px solid var(--primary);' }}">Semua</a>
            <a href="{{ route('admin.laporan', array_filter(['status' => $statusFilter, 'jenkel' => 'Laki-laki'])) }}" class="btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; text-decoration: none; {{ $jenkelFilter === 'Laki-laki' ? 'background: #2563eb; border: 1px solid #2563eb;' : 'background: white; color: #2563eb; border: 1px solid #2563eb;' }}">Laki-laki</a>
            <a href="{{ route('admin.laporan', array_filter(['status' => $statusFilter, 'jenkel' => 'Perempuan'])) }}" class="btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; text-decoration: none; {{ $jenkelFilter === 'Perempuan' ? 'background: #db2777; border: 1px solid #db2777;' : 'background: white; color: #db2777; border: 1px solid #db2777;' }}">Perempuan</a>
        </div>

        <div style="flex-grow: 1; max-width: 300px; min-width: 200px;">
            <input type="text" id="searchInput" onkeyup="filterLaporan()" class="form-input" placeholder="Cari nama, hp, kode..." style="padding: 0.5rem 1rem; border-radius: 20px;">
        </div>
    </div>

    <!-- Tampilan Desktop (Table Full Width) -->
    <div class="desktop-view" style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem; min-width: 800px;">
            <thead>
                <tr style="background-color: var(--primary-light); color: var(--primary-darker);">
                    <th style="padding: 0.75rem 1rem; border-bottom: 2px solid var(--border);">No</th>
                    <th style="padding: 0.75rem 1rem; border-bottom: 2px solid var(--border);">Kode Reg.</th>
                    <th style="padding: 0.75rem 1rem; border-bottom: 2px solid var(--border);">Jalur</th>
                    <th style="padding: 0.75rem 1rem; border-bottom: 2px solid var(--border);">Jadwal</th>
                    <th style="padding: 0.75rem 1rem; border-bottom: 2px solid var(--border);">Kehadiran</th>
                    <th style="padding: 0.75rem 1rem; border-bottom: 2px solid var(--border);">Hadiah</th>
                    <th style="padding: 0.75rem 1rem; border-bottom: 2px solid var(--border);">Nama Anak</th>
                    <th style="padding: 0.75rem 1rem; border-bottom: 2px solid var(--border);">Wali & HP</th>
                    <th style="padding: 0.75rem 1rem; border-bottom: 2px solid var(--border);">Waktu Daftar</th>
                    @if(session('admin_role') === 'superadmin')
                    <th style="padding: 0.75rem 1rem; border-bottom: 2px solid var(--border);">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($pendaftarans as $index => $p)
                <tr class="laporan-row" data-search="{{ strtolower($p->nama_lengkap . ' ' . $p->kode_registrasi . ' ' . $p->nama_wali . ' ' . $p->hp_wali . ' ' . $p->email) }}" style="border-bottom: 1px solid var(--border);">
                    <td style="padding: 0.75rem 1rem;">{{ $index + 1 }}</td>
                    <td style="padding: 0.75rem 1rem; font-family: monospace; font-weight: bold; color: var(--primary);">{{ $p->kode_registrasi }}</td>
                    <td style="padding: 0.75rem 1rem;">
                        @if($p->is_umum)
                            <span style="background-color: var(--warning-light); color: var(--warning); padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: bold;">UMUM</span>
                        @else
                            <span style="background-color: var(--success-light); color: var(--success); padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: bold;">DARELIMAN</span>
                        @endif
                    </td>
                    <td style="padding: 0.75rem 1rem; font-weight: bold; font-size: 0.8rem; white-space: nowrap;">
                        {{ $p->jadwal_hari ? $p->jadwal_hari : '-' }}<br>
                        <span style="color: var(--primary);">{{ $p->jadwal_jam ? $p->jadwal_jam : '-' }}</span>
                    </td>
                    <td style="padding: 0.75rem 1rem;">
                        @if($p->status_kehadiran == 'hadir')
                            <span style="color: var(--success); font-weight: bold;">HADIR</span><br>
                            <small style="color: var(--text-muted);">{{ $p->waktu_checkin ? $p->waktu_checkin->format('H:i') : '' }}</small>
                        @else
                            <span style="color: var(--danger); font-weight: bold;">BELUM</span>
                        @endif
                    </td>
                    <td style="padding: 0.75rem 1rem;">
                        @if($p->status_hadiah == 'sudah')
                            <span style="color: var(--success); font-weight: bold;">✔ DIAMBIL</span><br>
                            <small style="color: var(--text-muted);">{{ $p->waktu_ambil_hadiah ? $p->waktu_ambil_hadiah->format('H:i') : '' }}</small>
                        @else
                            <span style="color: var(--text-muted); font-weight: bold;">BELUM</span>
                        @endif
                    </td>
                    <td style="padding: 0.75rem 1rem;">
                        <strong style="display: flex; align-items: center; gap: 0.5rem;">
                            {{ $p->nama_lengkap }}
                            @if(session('admin_role') === 'superadmin')
                                @if($p->duplicate_status === 'red')
                                    <span title="{{ $p->duplicate_reason }}" style="display:inline-block; width:12px; height:12px; border-radius:50%; background:var(--danger); cursor:help;"></span>
                                @elseif($p->duplicate_status === 'yellow')
                                    <span title="{{ $p->duplicate_reason }}" style="display:inline-block; width:12px; height:12px; border-radius:50%; background:var(--warning); cursor:help;"></span>
                                @else
                                    <span title="{{ $p->duplicate_reason }}" style="display:inline-block; width:12px; height:12px; border-radius:50%; background:var(--success); cursor:help;"></span>
                                @endif
                            @endif
                        </strong>
                        <small style="color: var(--text-muted);">{{ $p->tempat_lahir }}, {{ $p->tanggal_lahir->format('d/m/Y') }}</small>
                    </td>
                    <td style="padding: 0.75rem 1rem;">
                        {{ $p->nama_wali }}<br>
                        <small style="color: var(--text-muted);">{{ $p->hp_wali }}</small>
                    </td>
                    <td style="padding: 0.75rem 1rem; color: var(--text-secondary);">
                        {{ $p->created_at->format('d/m/Y H:i') }}
                    </td>
                    @if(session('admin_role') === 'superadmin')
                    <td style="padding: 0.75rem 1rem;">
                        <div style="display: flex; gap: 0.5rem;">
                            <button type="button" onclick="showBarcodeModal('{{ $p->id }}', '{{ $p->kode_registrasi }}', '{{ addslashes($p->nama_lengkap) }}')" class="btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.75rem; border: none; cursor: pointer;">Barcode</button>
                            @if(empty($statusFilter))
                            <button type="button" onclick="showJadwalModal('{{ $p->id }}', '{{ addslashes($p->nama_lengkap) }}', '{{ $p->jadwal_hari }}', '{{ $p->jadwal_jam }}')" class="btn-primary" style="background: var(--text-secondary); color: white; border: none; padding: 0.4rem 0.8rem; border-radius: 4px; font-size: 0.75rem; cursor: pointer; font-weight: bold;">Input Jadwal</button>
                            @endif
                            @php
                                $waText = "Assalamu'alaikum warahmatullahi wabarakatuh,\n\nBapak/Ibu wali dari ananda *{$p->nama_lengkap}*.\nBerikut adalah informasi jadwal Khitanan Massal Dareliman:\n\n📅 Hari/Tanggal: *" . ($p->jadwal_hari ?: 'Menunggu Jadwal') . "*\n⏰ Jam Kedatangan: *" . ($p->jadwal_jam ?: '-') . "*\n\nUntuk melihat barcode pendaftaran, silakan login ke sistem melalui link berikut:\n\n";
                                if ($p->is_umum) {
                                    $waText .= "🔗 Link Login: " . route('umum.login') . "\n👤 Akun (No WA): " . $p->hp_wali . "\n🔑 Password: " . substr(preg_replace('/[^0-9]/', '', $p->hp_wali), -4) . " (4 digit terakhir no HP)\n";
                                } else {
                                    $waText .= "🔗 Link Login: " . route('login') . "\n👤 Akun: Email atau NIS Siswa\n🔑 Password: Password Portal Dareliman\n";
                                }
                                $waText .= "\nMohon hadir tepat waktu sesuai jadwal. Terima kasih.";
                                $waNumber = preg_replace('/[^0-9]/', '', $p->hp_wali);
                                if (str_starts_with($waNumber, '0')) { $waNumber = '62' . substr($waNumber, 1); }
                                $waUrl = "https://wa.me/{$waNumber}?text=" . urlencode($waText);
                            @endphp
                            <button type="button" onclick="kirimPesan(this, '{{ $p->id }}', '{{ $waUrl }}')" class="btn-primary" style="background: #25D366; color: white; border: none; padding: 0.4rem 0.8rem; border-radius: 4px; font-size: 0.75rem; cursor: pointer; font-weight: bold; display: inline-flex; align-items: center; justify-content: center;">Pesan</button>
                            @if($p->status_kehadiran === 'hadir')
                            <form action="{{ route('admin.laporan.batal', $p->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan kehadiran {{ $p->nama_lengkap }}?');" style="margin: 0;">
                                @csrf
                                <button type="submit" style="background: var(--warning); color: white; border: none; padding: 0.4rem 0.8rem; border-radius: 4px; font-size: 0.75rem; cursor: pointer; font-weight: bold;">Batal Hadir</button>
                            </form>
                            @endif
                            <form action="{{ route('admin.laporan.hapus', $p->id) }}" method="POST" onsubmit="return confirm('HAPUS PERMANEN: Apakah Anda yakin ingin menghapus data {{ $p->nama_lengkap }} secara permanen?');" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: var(--danger); color: white; border: none; padding: 0.4rem 0.8rem; border-radius: 4px; font-size: 0.75rem; cursor: pointer; font-weight: bold;">Hapus</button>
                            </form>
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="{{ session('admin_role') === 'superadmin' ? '8' : '7' }}" style="padding: 2rem 1rem; text-align: center; color: var(--text-muted);">Belum ada data pendaftar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Tampilan Mobile (Card View) -->
    <div class="mobile-view">
        @forelse($pendaftarans as $p)
        <div class="card laporan-card" data-search="{{ strtolower($p->nama_lengkap . ' ' . $p->kode_registrasi . ' ' . $p->nama_wali . ' ' . $p->hp_wali . ' ' . $p->email) }}" style="margin-bottom: 1rem; padding: 1rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border); padding-bottom: 0.75rem; margin-bottom: 0.75rem;">
                <div style="font-family: monospace; font-weight: bold; color: var(--primary); font-size: 1.1rem;">{{ $p->kode_registrasi }}</div>
                <div>
                    @if($p->is_umum)
                        <span style="background-color: var(--warning-light); color: var(--warning); padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: bold;">UMUM</span>
                    @else
                        <span style="background-color: var(--success-light); color: var(--success); padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: bold;">DARELIMAN</span>
                    @endif
                </div>
            </div>
            
            <div style="margin-bottom: 0.5rem;">
                <div style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase;">Nama Anak</div>
                <div style="font-weight: bold; display: flex; align-items: center; gap: 0.5rem;">
                    {{ $p->nama_lengkap }}
                    @if(session('admin_role') === 'superadmin')
                        @if($p->duplicate_status === 'red')
                            <span title="{{ $p->duplicate_reason }}" style="display:inline-block; width:12px; height:12px; border-radius:50%; background:var(--danger);"></span>
                        @elseif($p->duplicate_status === 'yellow')
                            <span title="{{ $p->duplicate_reason }}" style="display:inline-block; width:12px; height:12px; border-radius:50%; background:var(--warning);"></span>
                        @else
                            <span title="{{ $p->duplicate_reason }}" style="display:inline-block; width:12px; height:12px; border-radius:50%; background:var(--success);"></span>
                        @endif
                    @endif
                </div>
                <div style="font-size: 0.85rem; color: var(--text-muted);">{{ $p->tempat_lahir }}, {{ $p->tanggal_lahir->format('d/m/Y') }}</div>
            </div>

            <div style="margin-bottom: 0.5rem;">
                <div style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase;">Wali & Kontak</div>
                <div style="font-weight: 500;">{{ $p->nama_wali }}</div>
                <div style="font-size: 0.85rem; color: var(--text-muted);">{{ $p->hp_wali }}</div>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1rem;">
                <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                    <div>
                        <span style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase;">Kehadiran:</span>
                        @if($p->status_kehadiran == 'hadir')
                            <span style="color: var(--success); font-weight: bold; font-size: 0.85rem;">HADIR</span>
                            <span style="color: var(--text-muted); font-size: 0.85rem; margin-left: 0.25rem;">({{ $p->waktu_checkin ? $p->waktu_checkin->format('H:i') : '' }})</span>
                        @else
                            <span style="color: var(--danger); font-weight: bold; font-size: 0.85rem;">BELUM</span>
                        @endif
                    </div>
                    <div>
                        <span style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase;">Hadiah:</span>
                        @if($p->status_hadiah == 'sudah')
                            <span style="color: var(--success); font-weight: bold; font-size: 0.85rem;">✔ DIAMBIL</span>
                        @else
                            <span style="color: var(--text-muted); font-weight: bold; font-size: 0.85rem;">BELUM</span>
                        @endif
                    </div>
                </div>
                
                @if(session('admin_role') === 'superadmin')
                <div style="display: flex; gap: 0.5rem; flex-direction: column; align-items: flex-end;">
                    <button type="button" onclick="showBarcodeModal('{{ $p->id }}', '{{ $p->kode_registrasi }}', '{{ addslashes($p->nama_lengkap) }}')" class="btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.75rem; border: none; cursor: pointer;">Barcode</button>
                    @if(empty($statusFilter))
                    <button type="button" onclick="showJadwalModal('{{ $p->id }}', '{{ addslashes($p->nama_lengkap) }}', '{{ $p->jadwal_hari }}', '{{ $p->jadwal_jam }}')" class="btn-primary" style="background: var(--text-secondary); color: white; border: none; padding: 0.4rem 0.8rem; border-radius: 4px; font-size: 0.75rem; cursor: pointer; font-weight: bold;">Input Jadwal</button>
                    @endif
                    @php
                        $waTextMobile = "Assalamu'alaikum warahmatullahi wabarakatuh,\n\nBapak/Ibu wali dari ananda *{$p->nama_lengkap}*.\nBerikut adalah informasi jadwal Khitanan Massal Dareliman:\n\n📅 Hari/Tanggal: *" . ($p->jadwal_hari ?: 'Menunggu Jadwal') . "*\n⏰ Jam Kedatangan: *" . ($p->jadwal_jam ?: '-') . "*\n\nUntuk melihat barcode pendaftaran, silakan login ke sistem melalui link berikut:\n\n";
                        if ($p->is_umum) {
                            $waTextMobile .= "🔗 Link Login: " . route('umum.login') . "\n👤 Akun (No WA): " . $p->hp_wali . "\n🔑 Password: " . substr(preg_replace('/[^0-9]/', '', $p->hp_wali), -4) . " (4 digit terakhir no HP)\n";
                        } else {
                            $waTextMobile .= "🔗 Link Login: " . route('login') . "\n👤 Akun: Email atau NIS Siswa\n🔑 Password: Password Portal Dareliman\n";
                        }
                        $waTextMobile .= "\nMohon hadir tepat waktu sesuai jadwal. Terima kasih.";
                        $waNumberMobile = preg_replace('/[^0-9]/', '', $p->hp_wali);
                        if (str_starts_with($waNumberMobile, '0')) { $waNumberMobile = '62' . substr($waNumberMobile, 1); }
                        $waUrlMobile = "https://wa.me/{$waNumberMobile}?text=" . urlencode($waTextMobile);
                    @endphp
                    <button type="button" onclick="kirimPesan(this, '{{ $p->id }}', '{{ $waUrlMobile }}')" class="btn-primary" style="background: #25D366; color: white; border: none; padding: 0.4rem 0.8rem; border-radius: 4px; font-size: 0.75rem; cursor: pointer; font-weight: bold; display: inline-flex; align-items: center; justify-content: center; width: 100%; margin-top: 0.5rem; margin-bottom: 0.5rem;">Kirim Pesan WA</button>
                    @if($p->status_kehadiran === 'hadir')
                    <form action="{{ route('admin.laporan.batal', $p->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan kehadiran {{ $p->nama_lengkap }}?');" style="margin: 0;">
                        @csrf
                        <button type="submit" style="background: var(--warning); color: white; border: none; padding: 0.4rem 0.8rem; border-radius: 4px; font-size: 0.75rem; cursor: pointer; font-weight: bold;">Batal Hadir</button>
                    </form>
                    @endif
                    <form action="{{ route('admin.laporan.hapus', $p->id) }}" method="POST" onsubmit="return confirm('HAPUS PERMANEN: Apakah Anda yakin ingin menghapus data {{ $p->nama_lengkap }} secara permanen?');" style="margin: 0;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background: var(--danger); color: white; border: none; padding: 0.4rem 0.8rem; border-radius: 4px; font-size: 0.75rem; cursor: pointer; font-weight: bold;">Hapus</button>
                    </form>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div style="padding: 2rem 1rem; text-align: center; color: var(--text-muted); border: 1px solid var(--border); border-radius: var(--radius-sm);">
            Belum ada data pendaftar.
        </div>
        @endforelse
    </div>

    <!-- Modal Barcode -->
    <div id="barcodeModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; padding: 1rem;">
        <div style="background: white; padding: 2rem; border-radius: 8px; text-align: center; max-width: 100%; width: 400px; position: relative; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <button onclick="closeBarcodeModal()" style="position: absolute; top: 10px; right: 15px; border: none; background: transparent; font-size: 1.5rem; cursor: pointer; color: var(--text-muted);">&times;</button>
            <h3 style="margin-top: 0; color: var(--primary);">Barcode Kehadiran</h3>
            <p id="modalKode" style="font-family: monospace; font-weight: bold; font-size: 1.2rem; color: var(--primary);"></p>
            
            <div id="barcodeLoading" style="padding: 2rem; color: var(--text-muted);">Memuat Barcode...</div>
            <div id="barcodeImgContainer" style="display: none; margin-bottom: 1rem;"></div>
            
            <p id="modalNama" style="margin-bottom: 0; font-weight: bold;"></p>
        </div>
    </div>

</div>
<!-- Modal Jadwal -->
<div id="jadwalModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); align-items: center; justify-content: center;">
    <div style="background-color: var(--bg-card); padding: 1.5rem; border-radius: var(--radius); width: 90%; max-width: 400px; text-align: left; position: relative;">
        <h3 id="jadwalModalTitle" style="margin-top: 0; color: var(--primary-dark); font-size: 1.125rem;">Input Jadwal</h3>
        
        <form id="jadwalForm" method="POST" action="">
            @csrf
            <div class="form-group">
                <label class="form-label" for="jadwal_hari">Hari / Tanggal</label>
                <input type="text" id="jadwal_hari" name="jadwal_hari" class="form-input" placeholder="Contoh: Jumat, 26/06/2026">
            </div>
            
            <div class="form-group">
                <label class="form-label" for="jadwal_jam">Jam</label>
                <input type="text" id="jadwal_jam" name="jadwal_jam" class="form-input" placeholder="Contoh: 09.00">
            </div>
            
            <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1rem;">
                <button type="button" onclick="closeJadwalModal()" style="padding: 0.5rem 1rem; border: none; border-radius: 4px; background: var(--text-muted); color: white; cursor: pointer; font-size: 0.875rem;">Batal</button>
                <button type="submit" class="btn-primary" style="padding: 0.5rem 1rem; font-size: 0.875rem;">Simpan Jadwal</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    async function kirimPesan(btn, id, url) {
        try {
            await fetch(`/panel-rahasia/laporan/${id}/tandai-pesan`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            window.open(url, '_blank');
            // If in "belum pesan" tab, remove the row visually so it disappears
            @if($statusFilter === 'belum_pesan')
                const tr = btn.closest('tr');
                if (tr) tr.remove();
                const card = btn.closest('.card');
                if (card) card.remove();
            @endif
        } catch (error) {
            console.error('Error marking as sent:', error);
            window.open(url, '_blank');
        }
    }

    function showJadwalModal(id, nama, hari, jam) {
        document.getElementById('jadwalModal').style.display = 'flex';
        document.getElementById('jadwalModalTitle').innerText = 'Input Jadwal: ' + nama;
        
        document.getElementById('jadwal_hari').value = hari || '';
        document.getElementById('jadwal_jam').value = jam || '';
        
        let form = document.getElementById('jadwalForm');
        form.action = '/panel-rahasia/laporan/' + id + '/jadwal';
    }

    function closeJadwalModal() {
        document.getElementById('jadwalModal').style.display = 'none';
    }

    function showBarcodeModal(id, kode, nama) {
        document.getElementById('modalKode').innerText = kode;
        document.getElementById('modalNama').innerText = nama;
        document.getElementById('barcodeModal').style.display = 'flex';
        
        const imgContainer = document.getElementById('barcodeImgContainer');
        const loading = document.getElementById('barcodeLoading');
        
        imgContainer.style.display = 'none';
        loading.style.display = 'block';
        imgContainer.innerHTML = '';
        
        fetch(`/panel-rahasia/laporan/${id}/barcode`)
            .then(response => response.text())
            .then(svg => {
                imgContainer.innerHTML = svg;
                loading.style.display = 'none';
                imgContainer.style.display = 'block';
            })
            .catch(err => {
                loading.innerText = 'Gagal memuat barcode.';
            });
    }

    function closeBarcodeModal() {
        document.getElementById('barcodeModal').style.display = 'none';
    }

    function filterLaporan() {
        let input = document.getElementById('searchInput');
        let filter = input.value.toLowerCase();

        // Desktop rows
        let rows = document.querySelectorAll('.laporan-row');
        rows.forEach(row => {
            let searchData = row.getAttribute('data-search') || '';
            if (searchData.includes(filter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });

        // Mobile cards
        let cards = document.querySelectorAll('.laporan-card');
        cards.forEach(card => {
            let searchData = card.getAttribute('data-search') || '';
            if (searchData.includes(filter)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }
</script>
@endpush
@endsection

@stack('styles')
<style>
    /* Desktop Full Width Override */
    @media (min-width: 1024px) {
        main.container { max-width: 95% !important; padding: 2rem !important; }
        .card { max-width: 100% !important; }
    }

    /* Responsive View Toggles */
    .mobile-view { display: none; }
    .desktop-view { display: block; }

    @media (max-width: 768px) {
        .mobile-view { display: block; }
        .desktop-view { display: none; }
        .card { padding: 1rem !important; }
        .section-title { font-size: 1.1rem; flex-wrap: wrap; }
    }
</style>

