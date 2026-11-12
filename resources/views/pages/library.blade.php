@extends('layouts.app')

@section('title', 'Publikasi')

@section('content')
    <div class="page-wrap">
        <header class="library-hero">
            <div class="library-hero-copy">
                <h1 class="page-title">My Library</h1>
                <p class="page-subtitle">Seluruh koleksi yang tersedia di perpustakaan digital.</p>
            </div>
            <a class="library-summary" href="{{ config('library.anyflip_bookcase_url') }}" aria-label="Buka rak AnyFlip">
                <span class="library-summary-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M12 6.5c-1.6-1.6-4.9-2-8-1.2v13c3.1-.8 6.4-.4 8 1.2m0-13c1.6-1.6 4.9-2 8-1.2v13c-3.1-.8-6.4-.4-8 1.2m0-13v13" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                <span class="library-summary-copy">
                    <span class="library-summary-label">RAK DIGITAL &mdash; ANYFLIP</span>
                    <span class="library-summary-count">{{ $totalBooks }} Buku Tersedia</span>
                </span>
            </a>
        </header>

        <section class="bookcase-section" aria-labelledby="anyflip-bookcase-title">
            <h2 class="visually-hidden" id="anyflip-bookcase-title">Koleksi AnyFlip</h2>
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
                                @if (($book['source'] ?? null) === 'database')
                                    @include('partials.book-card', ['book' => $book])
                                @else
                                    @include('partials.anyflip-card', ['book' => $book])
                                @endif
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
