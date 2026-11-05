@extends('admin.layouts.app')

@section('title', 'Panel Admin')

@section('content')
    <section class="admin-panel">
        <div class="page-head admin-page-head">
            <div>
                <p class="eyebrow">PENGELOLAAN KATALOG</p>
                <h1 class="page-title">Panel admin</h1>
                <p class="page-subtitle">Masuk sebagai {{ auth()->user()->name }} &middot; {{ $totalBooks }} koleksi di katalog.</p>
            </div>
            <div class="admin-actions">
                <a class="btn" href="{{ route('admin.books.create') }}">+ Tambah buku</a>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button class="btn btn-secondary" type="submit">Keluar</button>
                </form>
            </div>
        </div>

        <h2 class="admin-section-title">Buku yang ditambahkan admin</h2>
        @if ($books->isEmpty())
            <div class="empty-state">
                <strong>Belum ada buku tambahan</strong>
                Tambahkan koleksi baru agar tampil di katalog publik.
            </div>
        @else
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Tahun</th>
                            <th>Berkas</th>
                            <th>Ditambahkan oleh</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($books as $book)
                            <tr>
                                <td>
                                    <a href="{{ route('books.show', $book->slug) }}">{{ $book->title }}</a>
                                    <small>{{ $book->author }} &middot; {{ $book->publisher }}</small>
                                </td>
                                <td>{{ $categories[$book->category]['name'] ?? $book->category }}</td>
                                <td>{{ $book->year }}</td>
                                <td>
                                    {{ $book->cover_image ? 'Sampul' : 'Tanpa sampul' }}
                                    <small>{{ $book->pdf_file ? 'PDF tersedia' : 'Tanpa PDF' }}</small>
                                </td>
                                <td>{{ $book->creator?->name ?? 'Tidak diketahui' }}</td>
                                <td>
                                    <div class="row-actions">
                                        <a class="btn btn-secondary btn-sm" href="{{ route('admin.books.edit', $book) }}">Edit</a>
                                        <form method="POST" action="{{ route('admin.books.destroy', $book) }}" onsubmit="return confirm('Hapus koleksi ini? Tindakan ini tidak dapat dibatalkan.');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="admin-pagination">{{ $books->links() }}</div>
        @endif
    </section>
@endsection
