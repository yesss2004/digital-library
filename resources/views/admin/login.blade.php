@extends('admin.layouts.app')

@section('title', 'Login Admin')

@section('content')
    <section class="admin-panel admin-login">
        <p class="eyebrow">AREA TERBATAS</p>
        <h1 class="page-title">Login admin</h1>
        <p class="page-subtitle">Masuk untuk mengelola koleksi perpustakaan digital.</p>
        <p><a class="btn btn-secondary btn-sm" href="{{ route('discover') }}">&larr; Kembali ke beranda</a></p>

        @if ($errors->any())
            <div class="admin-alert admin-alert-error" role="alert">
                {{ $errors->first() }}
            </div>
        @endif

        @unless ($databaseReady)
            <div class="admin-alert admin-alert-error" role="status">
                Database belum tersambung, jadi login admin belum bisa dipakai di server ini.
                Halaman Beranda, Informasi, dan Publikasi tetap berjalan normal.
            </div>
        @endunless

        <form class="admin-form" method="POST" action="{{ route('admin.login.submit') }}">
            @csrf
            <label>
                Email admin
                <input type="email" name="email" value="{{ old('email') }}" autocomplete="username" required autofocus>
            </label>
            <label>
                Kata sandi
                <input type="password" name="password" autocomplete="current-password" required>
            </label>
            <label class="admin-checkbox">
                <input type="checkbox" name="remember" value="1">
                Ingat saya
            </label>
            <button class="btn" type="submit" @disabled(! $databaseReady)>Masuk ke panel</button>
        </form>
    </section>
@endsection
