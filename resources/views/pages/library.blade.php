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
                            <h2 class="publish-group-title" id="publish-{{ $group['category']['slug'] }}">
                                {{ $group['category']['name'] }}
                            </h2>
                            <span class="publish-group-count">{{ count($group['books']) }} buku</span>
                        </div>
                        <ul class="publish-list">
                            @foreach ($group['books'] as $book)
                                <li class="publish-item">
                                    <a class="publish-cover" href="{{ $book['url'] }}" tabindex="-1" aria-hidden="true">
                                        <img src="{{ $book['cover'] }}" alt="" loading="lazy">
                                    </a>
                                    <div class="publish-info">
                                        <a class="publish-title" href="{{ $book['url'] }}">{{ $book['title'] }}</a>
                                        <span class="publish-meta">
                                            {{ $group['category']['name'] }}
                                            <span aria-hidden="true">&middot;</span> AnyFlip
                                        </span>
                                    </div>
                                    <a class="btn btn-sm publish-action" href="{{ $book['url'] }}">Baca</a>
                                </li>
                            @endforeach
                        </ul>
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
