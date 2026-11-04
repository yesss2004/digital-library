@extends('layouts.app')

@section('title', 'Favorit')

@section('content')
    <div class="page-wrap">
        <div class="page-head">
            <div>
                <h1 class="page-title">Favorit</h1>
                <p class="page-subtitle">Buku yang Anda tandai sebagai favorit tersimpan pada sesi perangkat ini.</p>
            </div>
            <a class="btn btn-secondary" href="{{ route('discover') }}">&larr; Kembali ke Discover</a>
        </div>

        @if (count($books))
            <section class="book-grid" aria-label="Buku favorit">
                @foreach ($books as $book)
                    <div class="favorite-entry">
                        @include('partials.book-card', ['book' => $book])
                        <form method="POST" action="{{ route('favorites.toggle', $book['slug']) }}">
                            @csrf
                            <button class="btn btn-secondary btn-sm" type="submit">Hapus dari favorit</button>
                        </form>
                    </div>
                @endforeach
            </section>
        @else
            <div class="empty-state">
                <strong>Belum ada favorit</strong>
                Buka <a href="{{ route('library') }}">koleksi perpustakaan</a>, lalu pilih
                <strong>Tambah ke favorit</strong> pada halaman detail buku.
            </div>
        @endif
    </div>
@endsection
