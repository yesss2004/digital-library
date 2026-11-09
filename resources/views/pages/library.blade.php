@extends('layouts.app')

@section('title', 'My Library')

@section('content')
    <div class="page-wrap">
        <div class="page-head">
            <div>
                <h1 class="page-title">My Library</h1>
                <p class="page-subtitle">Seluruh koleksi yang tersedia di perpustakaan digital.</p>
            </div>
            <a class="btn btn-secondary" href="{{ route('discover') }}">&larr; Kembali ke Discover</a>
        </div>

        <section class="bookcase-section" aria-labelledby="anyflip-bookcase-title">
            <div class="section-head">
                <div>
                    <p class="eyebrow">RAK DIGITAL</p>
                    <h2 class="section-title" id="anyflip-bookcase-title">Koleksi AnyFlip</h2>
                    <p class="page-subtitle">Jelajahi {{ count($anyflipBooks) }} buku yang tersedia di rak digital.</p>
                </div>
                <a class="btn btn-secondary" href="{{ config('library.anyflip_bookcase_url') }}">
                    Buka rak AnyFlip
                </a>
            </div>
            <section class="publish-section" aria-label="Buku dari rak AnyFlip">
                @if (count($anyflipBooks))
                    <ul class="publish-list">
                        @foreach ($anyflipBooks as $book)
                            <li class="publish-item">
                                <a class="publish-cover" href="{{ $book['url'] }}" tabindex="-1" aria-hidden="true">
                                    <img src="{{ $book['cover'] }}" alt="" loading="lazy">
                                </a>
                                <div class="publish-info">
                                    <a class="publish-title" href="{{ $book['url'] }}">{{ $book['title'] }}</a>
                                    <span class="publish-meta">
                                        {{ $categories[$book['category']]['name'] ?? 'Publikasi' }}
                                        <span aria-hidden="true">&middot;</span> AnyFlip
                                    </span>
                                </div>
                                <a class="btn btn-sm publish-action" href="{{ $book['url'] }}">Baca</a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="empty-state">
                        <strong>Belum ada koleksi</strong>
                        Daftar buku AnyFlip belum tersedia. Coba muat ulang halaman ini.
                    </div>
                @endif
            </section>
        </section>
    </div>
@endsection
