<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Form Pendaftaran Khitanan Massal - Dareliman Peduli bekerja sama dengan Surau TV">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Pendaftaran Khitanan Massal') - Dareliman Peduli</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>
    <div class="page-header">
        <div class="header-content">
            <div class="logo-area">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTftfXfbo6h7VVA8V1KcU64xX2Ieu_e6lah-w&s" alt="Dareliman Peduli" style="height: 45px; object-fit: contain; background: white; padding: 2px; border-radius: 4px;">
                <span class="logo-divider">×</span>
                <div class="logo-placeholder">Surau TV</div>
            </div>
            <h1 class="header-title">Pendaftaran Khitanan Massal</h1>
            <p class="header-subtitle">Dareliman Peduli bekerja sama dengan Surau TV</p>
            <div class="header-badge">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                Khusus Siswa Dareliman — Jenjang TAUD / TK / SD
            </div>
        </div>
    </div>
    <main class="container">
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if(session('warning'))
            <div class="alert alert-warning">{{ session('warning') }}</div>
        @endif
        @if(session('info'))
            <div class="alert alert-info">{{ session('info') }}</div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        @yield('content')
        
    </main>
    <footer class="footer">
        <p>&copy; {{ date('Y') }} Dareliman Peduli × Surau TV. Hak cipta dilindungi.</p>
    </footer>
    @stack('scripts')
</body>
</html>
