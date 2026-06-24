@extends('layouts.app')
@section('title', 'Scanner Pengambilan Hadiah')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto; width: 100%; border-top: 4px solid var(--warning);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <h2 class="section-title" style="margin: 0; color: var(--warning);">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 12v10H4V12"/><path d="M2 7h20v5H2z"/><line x1="12" y1="22" x2="12" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>
            Scanner Pengambilan Hadiah
        </h2>
        <div style="font-weight: bold; color: var(--warning); background: var(--warning-light); padding: 0.5rem 1rem; border-radius: 20px;">
            Sudah Ambil: <span id="counterHadir">{{ $totalHadiah }}</span> Anak
        </div>
    </div>

    <!-- Alert Area (Dynamically Populated) -->
    <div id="alertArea" style="display: none; padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem; text-align: center;">
        <h3 id="alertTitle" style="margin-top: 0; margin-bottom: 0.5rem;"></h3>
        <p id="alertMessage" style="margin: 0; font-size: 1.1rem;"></p>
        <div id="alertSubMessage" style="margin-top: 0.5rem; font-weight: bold; font-size: 1.25rem;"></div>
    </div>

    <div style="display: flex; flex-direction: column; gap: 2rem;">
        
        <!-- Input Manual (Untuk Barcode Gun / Scanner Fisik) -->
        <div style="background: var(--bg-page); padding: 1.5rem; border-radius: var(--radius-sm); border: 1px dashed var(--warning);">
            <h3 style="margin-top: 0; font-size: 1rem;">Gunakan Barcode Scanner Laser:</h3>
            <form id="scanForm" onsubmit="handleManualScan(event)">
                <div style="display: flex; gap: 0.5rem;">
                    <input type="text" id="manualInput" class="form-input" placeholder="Arahkan kursor ke sini lalu tembak barcode..." autofocus autocomplete="off" style="font-family: monospace; font-size: 1.25rem; font-weight: bold;">
                    <button type="submit" class="btn-primary" style="background: var(--warning); border-color: var(--warning); white-space: nowrap;">Proses</button>
                </div>
            </form>
            <p class="text-muted small" style="margin-bottom: 0; margin-top: 0.5rem;">Pastikan kursor berkedip di dalam kotak di atas sebelum menembak barcode.</p>
        </div>

        <!-- Kamera HP (Html5Qrcode) -->
        <div style="background: #000; border-radius: var(--radius-sm); overflow: hidden; position: relative; min-height: 300px;">
            <!-- Tombol Switch Kamera (Posisi Kanan Atas) -->
            <button type="button" onclick="switchCamera()" style="position: absolute; top: 10px; right: 10px; z-index: 5; background: rgba(0,0,0,0.5); color: white; border: 1px solid white; padding: 0.5rem 0.75rem; border-radius: 20px; font-size: 0.8rem; cursor: pointer; display: flex; align-items: center; gap: 0.25rem;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.5 2v6h-6M2.5 22v-6h6M2 11.5a10 10 0 0 1 18.8-4.3M22 12.5a10 10 0 0 1-18.8 4.2"/></svg>
                Ganti Kamera
            </button>
            <div id="reader" style="width: 100%; border: none; min-height: 300px;"></div>
            <div id="cameraOverlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.8); display: flex; align-items: center; justify-content: center; z-index: 10;">
                <button onclick="startCamera()" class="btn-primary" style="background: var(--warning); border-color: var(--warning); cursor: pointer; padding: 1rem 2rem; font-size: 1.1rem;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 8px;"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                    Buka Kamera HP
                </button>
            </div>
        </div>

    </div>
</div>
@endsection

@stack('styles')
<style>
    @media (min-width: 1024px) {
        main.container { max-width: 900px !important; }
    }
</style>

@stack('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Pustaka Html5Qrcode dari CDN -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    let isProcessing = false;
    let html5QrcodeScanner = null;

    // Fungsi tambahan untuk tombol Switch Kamera
    function switchCamera() {
        const selectBox = document.getElementById('html5-qrcode-select-camera');
        if (selectBox && selectBox.options.length > 1) {
            let currentIndex = selectBox.selectedIndex;
            let nextIndex = (currentIndex + 1) % selectBox.options.length;
            selectBox.selectedIndex = nextIndex;
            selectBox.dispatchEvent(new Event('change'));
        } else {
            Swal.fire({
                icon: 'info',
                title: 'Info',
                text: 'Hanya ada 1 kamera yang terdeteksi di perangkat ini.'
            });
        }
    }

    async function sendScanData(kode) {
        if (isProcessing) return;
        isProcessing = true;

        // Freeze camera if scanning
        if (html5QrcodeScanner) {
            try { html5QrcodeScanner.pause(); } catch(e) {}
        }

        try {
            const response = await fetch('{{ route("scanner.hadiah.process") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ kode_registrasi: kode })
            });

            const data = await response.json();

            // Clear input
            document.getElementById('manualInput').value = '';

            if (data.success) {
                // Increment counter
                const counter = document.getElementById('counterHadir');
                counter.innerText = parseInt(counter.innerText) + 1;

                await Swal.fire({
                    title: '🎁 SILAKAN BERIKAN HADIAH!',
                    html: `<b>Nama:</b> ${data.data.nama}<br><b>Jalur:</b> ${data.data.jalur}`,
                    icon: 'success',
                    confirmButtonText: 'Lanjut Scan',
                    confirmButtonColor: 'var(--success)'
                });
            } else {
                if (data.message === 'sudah ambil hadiah') {
                    await Swal.fire({
                        title: '❌ SUDAH DIAMBIL',
                        html: `Peserta ini SUDAH MENGAMBIL HADIAH sebelumnya!<br><br><b>Nama:</b> ${data.data ? data.data.nama : '-'}<br><b>Jam Ambil:</b> ${data.data && data.data.waktu_ambil ? data.data.waktu_ambil : '-'}`,
                        icon: 'error',
                        confirmButtonText: 'Tutup',
                        confirmButtonColor: 'var(--danger)'
                    });
                } else if (data.message === 'belum checkin') {
                    await Swal.fire({
                        title: '⚠️ BELUM CHECK-IN',
                        text: data.submessage + ' Minta peserta lapor ke meja depan dahulu.',
                        icon: 'warning',
                        confirmButtonText: 'Tutup',
                        confirmButtonColor: 'var(--warning)'
                    });
                } else {
                    await Swal.fire({
                        title: '❌ ERROR',
                        text: data.message,
                        icon: 'error',
                        confirmButtonText: 'Tutup',
                        confirmButtonColor: 'var(--danger)'
                    });
                }
            }
        } catch (error) {
            await Swal.fire({
                title: '❌ KONEKSI GAGAL',
                text: 'Tidak dapat menghubungi server. Periksa jaringan Anda.',
                icon: 'error',
                confirmButtonText: 'Tutup',
                confirmButtonColor: 'var(--danger)'
            });
        } finally {
            isProcessing = false;
            // Resume camera if scanning
            if (html5QrcodeScanner) {
                try { html5QrcodeScanner.resume(); } catch(e) {}
            }
            document.getElementById('manualInput').focus();
        }
    }

    function handleManualScan(e) {
        e.preventDefault();
        const input = document.getElementById('manualInput');
        const kode = input.value.trim();
        if (kode) {
            sendScanData(kode);
        }
    }

    function startCamera() {
        document.getElementById('cameraOverlay').style.display = 'none';
        html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", { 
                fps: 5, 
                qrbox: { width: 300, height: 300 }, 
                rememberLastUsedCamera: true,
                formatsToSupport: [ Html5QrcodeSupportedFormats.QR_CODE ],
                videoConstraints: {
                    width: { min: 640, ideal: 1280 },
                    height: { min: 480, ideal: 720 }
                }
            }, false
        );
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    }

    function onScanSuccess(decodedText, decodedResult) {
        // Prevent multiple rapid fires
        if (!isProcessing) {
            // Optional: Pause scanning until processed? 
            // html5QrcodeScanner.pause();
            sendScanData(decodedText);
        }
    }

    function onScanFailure(error) {
        // handle scan failure, usually better to ignore and keep scanning
    }
    
    // Auto focus input on load
    window.onload = function() {
        document.getElementById('manualInput').focus();
    };
</script>
</script>
