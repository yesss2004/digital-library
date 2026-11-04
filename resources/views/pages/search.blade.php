@extends('layouts.app')

@section('title', 'Hasil Pencarian')

@section('content')
    <div class="page-wrap">
        <div class="page-head">
            <div>
                <h1 class="page-title">Pencarian</h1>
                <p class="page-subtitle">
                    @if ($query !== '')
                        Hasil pencarian untuk "{{ $query }}"
                    @else
                        Cari buku berdasarkan judul, penulis, penerbit, kategori, atau tahun terbit.
                    @endif
                </p>
            </div>
            <a class="btn btn-secondary" href="{{ route('discover') }}">&larr; Kembali ke Discover</a>
        </div>

        <form class="search-area" role="search" method="GET" action="{{ route('search') }}">
            <select class="search-select" name="category" aria-label="Kategori">
                <option value="">Semua kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category['slug'] }}" @selected(request('category') === $category['slug'])>
                        {{ $category['name'] }}
                    </option>
                @endforeach
            </select>
            <input class="search-input" type="search" name="q" value="{{ $query }}" placeholder="Cari judul, penulis, atau penerbit..." aria-label="Kata kunci pencarian" />
            <select class="search-select" name="year" aria-label="Tahun terbit">
                <option value="">Semua tahun</option>
                @foreach ($years as $availableYear)
                    <option value="{{ $availableYear }}" @selected((string) $year === (string) $availableYear)>
                        {{ $availableYear }}
                    </option>
                @endforeach
            </select>
            <button class="search-button" type="submit">Cari</button>
        </form>

        @if ($query !== '' || $year !== '' || request('category'))
            <div class="result-count">{{ count($books) }} buku ditemukan</div>
        @endif

        @if (count($books))
            <section class="book-grid" aria-label="Hasil pencarian">
                @foreach ($books as $book)
                    @include('partials.book-card', ['book' => $book])
                @endforeach
            </section>
        @else
            <div class="empty-state">
                <strong>Buku tidak ditemukan</strong>
                Coba kata kunci lain, pilih kategori yang berbeda, atau kosongkan filter tahun.
            </div>
        @endif
    </div>
@endsection
