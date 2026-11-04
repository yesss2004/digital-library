@extends('layouts.app')

@section('title', 'Tambah Koleksi Buku')

@section('content')
    <section class="admin-panel">
        <div class="page-head">
            <div>
                <p class="eyebrow">PENGELOLAAN KATALOG</p>
                <h1 class="page-title">Tambah buku terbaru</h1>
                <p class="page-subtitle">Buku akan langsung tampil di katalog publik setelah disimpan.</p>
            </div>
            <a class="btn btn-secondary" href="{{ route('admin.dashboard') }}">&larr; Kembali ke panel</a>
        </div>

        @include('admin.books._form', [
            'book' => null,
            'categories' => $categories,
            'action' => route('admin.books.store'),
            'method' => 'POST',
            'submitLabel' => 'Simpan koleksi',
        ])
    </section>
@endsection
