@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <div class="discover-wrap">
        <div class="section-head collection-head">
            <div>
                <p class="eyebrow">KOLEKSI PILIHAN</p>
                <h2 class="section-title">Jelajahi koleksi</h2>
            </div>
            <a class="view-all" href="{{ route('library') }}">Semua koleksi <span aria-hidden="true">→</span></a>
        </div>

        <section class="recommendations" aria-label="Koleksi pilihan">
            @forelse ($anyflipBooks as $book)
                @include('partials.anyflip-card', ['book' => $book])
            @empty
                <div class="empty-state">
                    <strong>Belum ada koleksi</strong>
                    Koleksi perpustakaan akan tampil di sini setelah ditambahkan.
                </div>
            @endforelse
        </section>

        <div class="section-head category-block" id="struktur-perpustakaan">
            <div>
                <p class="eyebrow">TEMUKAN INFORMASI LEBIH CEPAT</p>
                <h2 class="section-title">Struktur perpustakaan</h2>
            </div>
            <a class="view-all" href="{{ route('categories.index') }}">Lihat semua kategori <span aria-hidden="true">→</span></a>
        </div>

        <section class="category-grid" aria-label="Kategori koleksi perpustakaan">
            @foreach ($categories as $category)
                @include('partials.category-item', ['category' => $category])
            @endforeach
        </section>
    </div>
@endsection
