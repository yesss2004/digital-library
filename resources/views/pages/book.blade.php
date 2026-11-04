@extends('layouts.app')

@section('title', $book['title'])

@section('content')
    <div class="page-wrap">
        <div class="page-head">
            <div>
                <h1 class="page-title">{{ $book['title'] }}</h1>
                <p class="page-subtitle">oleh {{ $book['author'] }}</p>
            </div>
            <a class="btn btn-secondary" href="{{ route('discover') }}">&larr; Kembali ke Discover</a>
        </div>

        <div class="book-detail">
            <div>
                @include('partials.book-card', ['book' => $book])
            </div>

            <div>
                <div class="book-meta">
                    @if ($category)
                        <span>{{ $category['name'] }}</span>
                    @endif
                    <span>{{ $book['year'] }}</span>
                    @if ($book['pages'])
                        <span>{{ $book['pages'] }} halaman</span>
                    @endif
                    <span>{{ $book['publisher'] }}</span>
                    @if (! empty($book['pdf_url']))
                        <span>PDF tersedia</span>
                    @endif
                </div>

                <p class="book-description">{{ $book['description'] }}</p>

                <div class="book-actions">
                    <a class="btn" href="{{ route('books.read', $book['slug']) }}">Buka Buku</a>

                    @if (! empty($book['pdf_url']))
                        <a class="btn btn-secondary" href="{{ $book['pdf_url'] }}">Unduh PDF</a>
                    @endif

                    <form class="favorite-form" method="POST" action="{{ route('favorites.toggle', $book['slug']) }}">
                        @csrf
                        <button class="btn {{ $isFavorite ? 'btn-secondary' : '' }}" type="submit">
                            {{ $isFavorite ? 'Hapus dari favorit' : 'Tambah ke favorit' }}
                        </button>
                    </form>

                    @if ($category)
                        <a class="btn btn-secondary" href="{{ route('categories.show', $category['slug']) }}">Lihat Kategori</a>
                    @endif
                    <a class="btn btn-secondary" href="{{ route('library') }}">Buka My Library</a>
                </div>
            </div>
        </div>
    </div>
@endsection
