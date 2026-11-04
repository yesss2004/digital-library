@extends('layouts.app')

@section('title', 'Pengaturan')

@section('content')
    <div class="page-wrap">
        <div class="page-head">
            <div>
                <h1 class="page-title">Pengaturan</h1>
                <p class="page-subtitle">Informasi akun dan preferensi perpustakaan digital.</p>
            </div>
            <a class="btn btn-secondary" href="{{ route('discover') }}">&larr; Kembali ke Discover</a>
        </div>

        <div class="panel">
            <h2>Profil pengguna</h2>
            @auth
                <p>Masuk sebagai <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->email }}).</p>
            @else
                <p>
                    Anda mengakses perpustakaan sebagai tamu. Favorit disimpan pada sesi perangkat ini,
                    sedangkan akun admin diperlukan untuk mengelola katalog.
                </p>
                <p><a class="btn btn-secondary btn-sm" href="{{ route('admin.login') }}">Masuk sebagai admin</a></p>
            @endauth
        </div>

        <div class="panel">
            <h2>Ringkasan koleksi</h2>
            <p>
                {{ $totalBooks }} koleksi tersedia di katalog dan {{ $favoriteCount }} buku ditandai sebagai favorit.
            </p>
        </div>

        <div class="panel">
            <h2>Tampilan</h2>
            <p>Tema gelap aktif secara bawaan agar nyaman digunakan untuk membaca dalam waktu lama.</p>
        </div>

        <div class="panel">
            <h2>Favorit</h2>
            <p>Daftar favorit tersimpan di sesi browser dan akan hilang jika sesi berakhir atau data browser dibersihkan.</p>
            @if ($favoriteCount > 0)
                <form method="POST" action="{{ route('favorites.clear') }}">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-secondary btn-sm" type="submit">Kosongkan daftar favorit</button>
                </form>
            @endif
        </div>

        <div class="panel">
            <h2>Bantuan</h2>
            <p>Pelajari cara mencari, membaca, mengunduh, dan menyimpan koleksi pada <a href="{{ route('help') }}">halaman bantuan</a>.</p>
        </div>
    </div>
@endsection
