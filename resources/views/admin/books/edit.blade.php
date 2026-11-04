@extends('layouts.app')

@section('title', 'Edit Koleksi Buku')

@section('content')
    <section class="admin-panel">
        <div class="page-head">
            <div>
                <p class="eyebrow">PENGELOLAAN KATALOG</p>
                <h1 class="page-title">Edit koleksi</h1>
                <p class="page-subtitle">Perbarui metadata, sampul, tautan baca, atau berkas PDF buku.</p>
            </div>
            <a class="btn btn-secondary" href="{{ route('admin.dashboard') }}">&larr; Kembali ke panel</a>
        </div>

        @include('admin.books._form', [
            'book' => $book,
            'categories' => $categories,
            'action' => route('admin.books.update', $book),
            'method' => 'PUT',
            'submitLabel' => 'Perbarui koleksi',
        ])
    </section>
@endsection
