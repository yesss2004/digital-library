@extends('layouts.app')

@section('title', 'Masuk Admin')

@section('content')
    <div class="admin-portal-page">
        <div class="admin-portal-card">
            <p class="eyebrow">AREA TERBATAS</p>
            <h1 class="page-title">Masuk Admin</h1>
            <p class="page-subtitle">Halaman ini hanya untuk pengelola perpustakaan digital.</p>

            <div class="admin-portal-actions">
                <a class="btn" href="{{ route('admin.login') }}">Masuk Admin</a>
                <a class="btn btn-secondary" href="{{ route('discover') }}">Kembali ke beranda</a>
            </div>
        </div>
    </div>
@endsection
