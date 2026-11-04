@extends('layouts.app')

@section('title', 'Baca: '.$book['title'])

@section('content')
    <section class="page-wrap reader-page">
        <div class="page-head">
            <div>
                <p class="eyebrow">RUANG BACA DIGITAL</p>
                <h1 class="page-title">{{ $book['title'] }}</h1>
                <p class="page-subtitle">oleh {{ $book['author'] }}</p>
            </div>
            <div class="book-actions">
                @if (! empty($book['pdf_url']))
                    <a class="btn btn-secondary" href="{{ $book['pdf_url'] }}">Unduh PDF</a>
                @endif
                <a class="btn btn-secondary" href="{{ route('books.show', $book['slug']) }}">&larr; Kembali ke detail buku</a>
            </div>
        </div>

        @if (! empty($book['reader_url']))
            <div class="reader-frame-wrap">
                <iframe
                    class="reader-frame"
                    src="{{ $book['reader_url'] }}"
                    title="Isi buku {{ $book['title'] }}"
                    loading="lazy"
                    referrerpolicy="no-referrer"
                    sandbox="allow-scripts allow-forms allow-presentation allow-same-origin allow-popups allow-downloads"
                    allowfullscreen
                ></iframe>
            </div>
            <p class="reader-fallback">
                Jika buku tidak tampil,
                <a href="{{ $book['reader_url'] }}" target="_blank" rel="noopener noreferrer">buka isi buku di tab baru</a>.
            </p>
        @else
            <div class="empty-state reader-empty">
                <strong>Isi buku belum tersedia</strong>
                Tautan baca untuk koleksi ini belum ditambahkan. Admin dapat mengisinya melalui menu
                <a href="{{ auth()->check() && auth()->user()->isAdmin() ? route('admin.books.create') : route('admin.login') }}">panel admin</a>.
            </div>
        @endif
    </section>
@endsection
