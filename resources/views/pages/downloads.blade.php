@extends('layouts.app')

@section('title', 'Unduhan')

@section('content')
    <div class="page-wrap">
        <div class="page-head">
            <div>
                <h1 class="page-title">Unduhan</h1>
                <p class="page-subtitle">Dokumen PDF yang dapat diunduh untuk dibaca secara offline.</p>
            </div>
            <a class="btn btn-secondary" href="{{ route('discover') }}">&larr; Kembali ke Discover</a>
        </div>

        @if (count($books))
            @foreach ($books as $book)
                @php($category = collect(config('library.categories', []))->firstWhere('slug', $book['category']))
                <div class="panel download-row">
                    <div>
                        <h2>{{ $book['title'] }}</h2>
                        <p>
                            {{ $book['author'] }}
                            <span aria-hidden="true">&middot;</span>
                            {{ $category['name'] ?? ucfirst(str_replace('-', ' ', $book['category'])) }}
                            <span aria-hidden="true">&middot;</span>
                            {{ $book['year'] }}
                            @if (! empty($book['pages']))
                                <span aria-hidden="true">&middot;</span> {{ $book['pages'] }} halaman
                            @endif
                        </p>
                    </div>
                    <div class="book-actions">
                        <a class="btn btn-secondary" href="{{ route('books.show', $book['slug']) }}">Detail</a>
                        <a class="btn" href="{{ $book['pdf_url'] }}">Unduh PDF</a>
                    </div>
                </div>
            @endforeach
        @else
            <div class="empty-state">
                <strong>Belum ada dokumen unduhan</strong>
                Admin belum mengunggah berkas PDF untuk koleksi mana pun.
                <a href="{{ route('library') }}">Lihat semua koleksi</a> yang dapat dibaca secara online.
            </div>
        @endif
    </div>
@endsection
