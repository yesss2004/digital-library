@extends('layouts.app')

@section('title', 'Publikasi')

@section('content')
    <div class="page-wrap">
        <div class="page-head">
            <div>
                <h1 class="page-title">Publikasi</h1>
                <p class="page-subtitle">Seluruh koleksi yang tersedia di perpustakaan digital.</p>
            </div>
            <a class="btn btn-secondary" href="{{ route('discover') }}">&larr; Kembali ke Discover</a>
        </div>

        <section class="bookcase-section" aria-labelledby="anyflip-bookcase-title">
            <div class="section-head">
                <div>
                    <p class="eyebrow">RAK DIGITAL</p>
                    <h2 class="section-title" id="anyflip-bookcase-title">Koleksi AnyFlip</h2>
                    <p class="page-subtitle">Jelajahi {{ count($anyflipBooks) }} buku yang tersedia, dikelompokkan berdasarkan kategori.</p>
                </div>
                <a class="btn btn-secondary" href="{{ config('library.anyflip_bookcase_url') }}">
                    Buka rak AnyFlip
                </a>
            </div>
            <div class="publish-groups">
                @forelse ($groups as $group)
                    <section class="publish-group" aria-labelledby="publish-{{ $group['category']['slug'] }}">
                        <div class="publish-group-head">
                            <div class="publish-group-title-wrap">
                                <span class="publish-group-index" aria-hidden="true">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                <h2 class="publish-group-title" id="publish-{{ $group['category']['slug'] }}">
                                    {{ $group['category']['name'] }}
                                </h2>
                            </div>
                            <span class="publish-group-count">{{ count($group['books']) }} buku</span>
                        </div>
                        <section class="book-grid anyflip-book-grid" aria-label="Buku {{ $group['category']['name'] }}">
                            @foreach ($group['books'] as $book)
                                @include('partials.anyflip-card', ['book' => $book])
                            @endforeach
                        </section>
                    </section>
                @empty
                    <div class="empty-state">
                        <strong>Belum ada koleksi</strong>
                        Daftar buku AnyFlip belum tersedia. Coba muat ulang halaman ini.
                    </div>
                @endforelse
            </div>
        </section>
    </div>
@endsection
