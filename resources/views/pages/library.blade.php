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
            <section class="book-grid anyflip-book-grid" aria-label="Buku dari rak AnyFlip">
                @foreach ($anyflipBooks as $book)
                    @include('partials.anyflip-card', ['book' => $book])
                @endforeach
            </section>
        </section>
    </div>
@endsection
