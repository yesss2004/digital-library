@extends('layouts.app')

@section('title', $category['name'])

@section('content')
    <div class="page-wrap">
        <div class="page-head">
            <div>
                <h1 class="page-title">{{ $category['name'] }}</h1>
                <p class="page-subtitle">{{ $category['description'] }}</p>
            </div>
            <a class="btn btn-secondary" href="{{ route('categories.index') }}">&larr; Semua Kategori</a>
        </div>

        @if (count($books))
            <div class="result-count">{{ count($books) }} buku dalam kategori ini</div>
            <section class="book-grid anyflip-book-grid" aria-label="Buku {{ $category['name'] }}">
                @foreach ($books as $book)
                    @include('partials.anyflip-card', ['book' => $book])
                @endforeach
            </section>
        @else
            <div class="empty-state">
                <strong>Belum ada buku</strong>
                Koleksi untuk kategori ini belum tersedia.
            </div>
        @endif
    </div>
@endsection
