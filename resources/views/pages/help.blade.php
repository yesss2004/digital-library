@extends('layouts.app')

@section('title', 'Bantuan')

@section('content')
    <div class="page-wrap">
        <div class="page-head">
            <div>
                <h1 class="page-title">Bantuan</h1>
                <p class="page-subtitle">Panduan singkat menggunakan perpustakaan digital.</p>
            </div>
            <a class="btn btn-secondary" href="{{ route('discover') }}">&larr; Kembali ke Discover</a>
        </div>

        <div class="panel">
            <h2>Mencari buku</h2>
            <p>Gunakan kolom pencarian di bagian atas halaman. Anda dapat mencari berdasarkan judul, penulis, penerbit, kategori, atau tahun terbit, lalu mempersempit hasil dengan filter kategori dan tahun.</p>
        </div>

        <div class="panel">
            <h2>Membuka kategori</h2>
            <p>Klik menu <strong>Informasi</strong> atau salah satu kartu kategori pada beranda untuk melihat semua buku dalam kategori tersebut.</p>
        </div>

        <div class="panel">
            <h2>Membaca buku</h2>
            <p>Buka halaman detail buku, lalu pilih <strong>Buka Buku</strong>. Isi buku akan ditampilkan melalui viewer digital jika admin sudah menambahkan tautan baca. Jika belum tersedia, halaman reader akan menampilkan keterangan.</p>
        </div>

        <div class="panel">
            <h2>Mengunduh dokumen</h2>
            <p>Buku yang memiliki berkas PDF menampilkan tombol <strong>Unduh PDF</strong> pada halaman detail. Daftar seluruh dokumen unduhan tersedia di menu <a href="{{ route('downloads') }}">Unduhan</a>.</p>
        </div>

        <div class="panel">
            <h2>Menyimpan favorit</h2>
            <p>Pada halaman detail buku, pilih <strong>Tambah ke favorit</strong>. Daftar favorit dapat dibuka melalui menu <a href="{{ route('favorites') }}">Favorit</a>. Favorit disimpan pada sesi browser perangkat ini.</p>
        </div>

        <div class="panel">
            <h2>Panel admin</h2>
            <p>Admin dapat menambah, mengubah, dan menghapus koleksi melalui panel admin. Akun admin dibuat dari terminal server, bukan melalui pendaftaran publik. Lihat panduan pada <code>README.md</code>.</p>
        </div>
    </div>
@endsection
