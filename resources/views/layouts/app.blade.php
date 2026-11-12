<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', 'Perpustakaan Digital Jogja Tangguh Bencana')</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div class="site-shell">
            <header class="site-header">
                <div class="header-bar">
                    <div class="site-brand">
                        <button class="brand-logo-button" type="button" aria-label="Perbesar logo BPBD Kota Yogyakarta" aria-haspopup="dialog" aria-controls="bpbd-logo-dialog">
                            <img class="brand-mark" src="{{ asset('images/logo-bpbd-yogyakarta.png') }}" alt="">
                        </button>
                        <a class="site-brand-copy" href="{{ route('discover') }}">
                            <strong>PERPUSTAKAAN DIGITAL</strong>
                            <small>JOGJA TANGGUH BENCANA</small>
                        </a>
                    </div>
                    <nav class="site-nav" aria-label="Navigasi utama">
                        <a href="{{ route('discover') }}" @if (request()->routeIs('discover')) aria-current="page" @endif>Beranda</a>
                        <a href="{{ route('discover') }}#tentang">Profil</a>
                        <a href="{{ route('categories.index') }}" @if (request()->routeIs('categories.*')) aria-current="page" @endif>Informasi</a>
                        <a href="{{ route('library') }}" @if (request()->routeIs('library', 'books.*')) aria-current="page" @endif>Publikasi</a>
                    </nav>
                </div>

                <div class="header-intro" id="tentang">
                    <span class="official-badge">Pusat Literasi Kebencanaan Resmi Kota Yogyakarta</span>
                </div>
                <form class="header-search" role="search" method="GET" action="{{ route('search') }}">
                    <svg class="search-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle cx="11" cy="11" r="6.5" stroke="currentColor" stroke-width="1.8"/>
                        <path d="m16.2 16.2 4 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari judul buku AnyFlip..." aria-label="Cari koleksi di rak AnyFlip">
                    <button type="submit">Cari</button>
                </form>
            </header>

            @if (session('status'))
                <div class="site-flash" role="status">{{ session('status') }}</div>
            @endif

            <dialog class="logo-dialog" id="bpbd-logo-dialog" aria-label="Logo BPBD Kota Yogyakarta">
                <button class="logo-dialog-close" type="button" aria-label="Tutup logo">&times;</button>
                <img src="{{ asset('images/logo-bpbd-yogyakarta.png') }}" alt="Logo BPBD Kota Yogyakarta ukuran besar">
            </dialog>

            <main class="content">
                @yield('content')
            </main>

            <footer class="site-footer">
                <p>Perpustakaan Digital Jogja Tangguh Bencana <span aria-hidden="true">&middot;</span> BPBD Kota Yogyakarta</p>
                <address>
                    Jl. Gambiran No. 26, Pandeyan, Kec. Umbulharjo, Kota Yogyakarta, Daerah Istimewa Yogyakarta 55161
                </address>
                <nav class="footer-links" aria-label="Navigasi sekunder">
                    <a href="{{ route('library') }}">Semua koleksi</a>
                    <a href="{{ route('settings') }}">Pengaturan</a>
                </nav>
            </footer>
        </div>
    </body>
</html>
