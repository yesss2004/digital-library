<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', 'Panel Admin')</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div class="admin-shell">
            <header class="admin-header">
                <div class="admin-header-inner">
                    <div>
                        <p class="eyebrow">PANEL ADMIN</p>
                        <h1 class="admin-brand">Perpustakaan Digital</h1>
                    </div>
                    <nav class="admin-topbar" aria-label="Navigasi admin">
                        <a href="{{ route('discover') }}" class="admin-topbar-link">Lihat website</a>
                        @auth
                            <form method="POST" action="{{ route('admin.logout') }}" class="admin-inline-form">
                                @csrf
                                <button type="submit" class="btn btn-secondary btn-sm">Keluar</button>
                            </form>
                        @endauth
                    </nav>
                </div>
            </header>

            @if (session('status'))
                <div class="site-flash" role="status">{{ session('status') }}</div>
            @endif

            <main class="admin-main">
                @yield('content')
            </main>
        </div>
    </body>
</html>
