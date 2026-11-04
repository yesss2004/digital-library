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

        @if (count($books))
            <div class="result-count">{{ count($books) }} koleksi tersedia</div>
            <section class="book-grid" aria-label="Koleksi perpustakaan">
                @foreach ($books as $book)
                    @include('partials.book-card', ['book' => $book])
                @endforeach
            </section>
        @else
            <div class="empty-state">
                <strong>Belum ada koleksi</strong>
                Koleksi perpustakaan akan tampil di sini setelah ditambahkan oleh admin.
            </div>
        @endif
    </div>
@endsection
