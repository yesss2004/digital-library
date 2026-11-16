@extends('layouts.app')

@section('title', 'Struktur Perpustakaan')

@section('content')
    <div class="page-wrap structure-page">
        <div class="page-head">
            <div>
                <p class="eyebrow">KOLEKSI PERPUSTAKAAN</p>
                <h1 class="page-title">Struktur perpustakaan</h1>
                <p class="page-subtitle">Jelajahi koleksi kebencanaan berdasarkan kategori.</p>
            </div>
            <a class="btn btn-secondary" href="{{ route('discover') }}">&larr; Kembali ke beranda</a>
        </div>

        <div class="structure-summary" aria-label="Ringkasan struktur perpustakaan">
            <div class="structure-summary-stats">
                <div>
                    <span>Kategori</span>
                    <strong>{{ number_format($totalCategories, 0, ',', '.') }}</strong>
                </div>
                <div>
                    <span>Total koleksi</span>
                    <strong>{{ number_format($totalBooks, 0, ',', '.') }}</strong>
                </div>
            </div>
        </div>

        <div class="section-head structure-section-head">
            <div>
                <p class="eyebrow">KATEGORI KOLEKSI</p>
                <h2 class="section-title">Pilih bidang literasi</h2>
            </div>
            <span class="structure-count">{{ number_format($totalCategories, 0, ',', '.') }} kategori</span>
        </div>

        <div class="structure-grid">
            @forelse ($categories as $category)
                <a class="structure-card" href="{{ route('categories.show', $category['slug']) }}">
                    <div class="structure-card-top">
                        <span class="structure-number">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="structure-arrow" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M7 17 17 7"/>
                                <path d="M8 7h9v9"/>
                            </svg>
                        </span>
                    </div>

                    <h3 class="structure-name">{{ $category['name'] }}</h3>
                    <p class="structure-description">{{ $category['description'] }}</p>

                    <div class="structure-card-foot">
                        <span class="structure-collection">{{ $category['collection'] }}</span>
                        <span class="structure-total">{{ number_format($category['total_books'], 0, ',', '.') }} koleksi</span>
                    </div>
                </a>
            @empty
                <div class="empty-state">
                    <strong>Belum ada kategori</strong>
                    Struktur perpustakaan akan tampil di sini setelah kategori ditambahkan.
                </div>
            @endforelse
        </div>
    </div>
@endsection
