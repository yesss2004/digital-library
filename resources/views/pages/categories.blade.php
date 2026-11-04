@extends('layouts.app')

@section('title', 'Struktur Perpustakaan')

@section('content')
    <div class="page-wrap">
        <div class="page-head">
            <div>
                <p class="eyebrow">KOLEKSI PERPUSTAKAAN</p>
                <h1 class="page-title">Struktur perpustakaan</h1>
                <p class="page-subtitle">Jelajahi koleksi kebencanaan berdasarkan kategori.</p>
            </div>
            <a class="btn btn-secondary" href="{{ route('discover') }}">Kembali ke beranda</a>
        </div>

        <div class="category-table-wrap">
            <table class="category-table">
                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Kategori</th>
                        <th scope="col">Isi koleksi</th>
                        <th scope="col">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><a href="{{ route('categories.show', $category['slug']) }}">{{ $category['name'] }}</a></td>
                            <td>{{ $category['collection'] }}</td>
                            <td>{{ $category['total_books'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
