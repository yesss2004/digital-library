@extends('layouts.app')

@section('title', 'Pengaturan')

@section('content')
    <div class="page-wrap settings-page">
        <div class="page-head settings-page-head">
            <div>
                <p class="eyebrow">PUSAT PENGATURAN</p>
                <h1 class="page-title">Pengaturan</h1>
                <p class="page-subtitle">Kelola profil, tampilan, dan preferensi perpustakaan digital Anda.</p>
            </div>
            <a class="btn btn-secondary" href="{{ route('discover') }}">&larr; Kembali ke Discover</a>
        </div>

        <div class="settings-layout">
            <aside class="settings-sidebar">
                <nav class="settings-nav" aria-label="Bagian pengaturan">
                    <a href="#profil">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="8" r="3.5"/>
                            <path d="M5 20c.8-3.5 3.6-5.5 7-5.5s6.2 2 7 5.5"/>
                        </svg>
                        <span>Profil</span>
                    </a>
                    <a href="#tampilan">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 6h14M5 12h14M5 18h14"/>
                            <circle cx="9" cy="6" r="2"/>
                            <circle cx="15" cy="12" r="2"/>
                            <circle cx="9" cy="18" r="2"/>
                        </svg>
                        <span>Tampilan</span>
                    </a>
                    <a href="#favorit">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M7 4h10v16l-5-3.5L7 20V4Z"/>
                        </svg>
                        <span>Favorit</span>
                    </a>
                    <a href="#bantuan">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="8.5"/>
                            <path d="M9.7 9.3a2.5 2.5 0 1 1 3.5 2.3c-.7.4-1.2 1-1.2 1.8v.2"/>
                            <path d="M12 17h.01"/>
                        </svg>
                        <span>Bantuan</span>
                    </a>
                </nav>

                <div class="settings-summary">
                    <p class="eyebrow">RINGKASAN KOLEKSI</p>
                    <div class="settings-summary-grid">
                        <div>
                            <strong>{{ number_format($totalBooks, 0, ',', '.') }}</strong>
                            <span>Koleksi</span>
                        </div>
                        <div>
                            <strong>{{ number_format($favoriteCount, 0, ',', '.') }}</strong>
                            <span>Favorit</span>
                        </div>
                    </div>
                    <a href="{{ route('library') }}">Jelajahi katalog <span aria-hidden="true">&rarr;</span></a>
                </div>
            </aside>

            <div class="settings-content">
                <section class="settings-card" id="profil" aria-labelledby="settings-profile-title">
                    <header class="settings-card-head">
                        <span class="settings-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="8" r="3.5"/>
                                <path d="M5 20c.8-3.5 3.6-5.5 7-5.5s6.2 2 7 5.5"/>
                            </svg>
                        </span>
                        <div>
                            <h2 id="settings-profile-title">Profil pengguna</h2>
                            <p>Informasi akun yang sedang mengakses perpustakaan.</p>
                        </div>
                    </header>

                    <div class="settings-rows">
                        @auth
                            <div class="settings-row">
                                <div class="settings-identity">
                                    <span class="settings-avatar" aria-hidden="true">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                                    <div>
                                        <strong>{{ auth()->user()->name }}</strong>
                                        <span>{{ auth()->user()->email }}</span>
                                    </div>
                                </div>
                                <span class="settings-pill">{{ auth()->user()->isAdmin() ? 'Administrator' : 'Pengguna' }}</span>
                            </div>
                            <div class="settings-row">
                                <div class="settings-row-copy">
                                    <strong>Status akun</strong>
                                    <span>Akun ini memiliki akses untuk mengelola katalog dan koleksi perpustakaan.</span>
                                </div>
                                <span class="settings-pill"><span class="settings-dot" aria-hidden="true"></span>Aktif</span>
                            </div>
                        @else
                            <div class="settings-row">
                                <div class="settings-row-copy">
                                    <strong>Mode tamu</strong>
                                    <span>Favorit disimpan pada sesi perangkat ini, sedangkan akun admin diperlukan untuk mengelola katalog.</span>
                                </div>
                                <a class="btn btn-secondary btn-sm" href="{{ route('admin.login') }}">Masuk sebagai admin</a>
                            </div>
                        @endauth
                    </div>
                </section>

                <section class="settings-card" id="tampilan" aria-labelledby="settings-display-title">
                    <header class="settings-card-head">
                        <span class="settings-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 6h14M5 12h14M5 18h14"/>
                                <circle cx="9" cy="6" r="2"/>
                                <circle cx="15" cy="12" r="2"/>
                                <circle cx="9" cy="18" r="2"/>
                            </svg>
                        </span>
                        <div>
                            <h2 id="settings-display-title">Tampilan</h2>
                            <p>Preferensi visual saat menjelajahi dan membaca koleksi.</p>
                        </div>
                    </header>

                    <div class="settings-rows">
                        <div class="settings-row">
                            <div class="settings-row-copy">
                                <strong>Tema antarmuka</strong>
                                <span>Tema gelap aktif secara bawaan agar nyaman digunakan untuk membaca dalam waktu lama.</span>
                            </div>
                            <span class="settings-pill"><span class="settings-dot" aria-hidden="true"></span>Gelap</span>
                        </div>
                    </div>
                </section>

                <section class="settings-card" id="favorit" aria-labelledby="settings-favorite-title">
                    <header class="settings-card-head">
                        <span class="settings-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M7 4h10v16l-5-3.5L7 20V4Z"/>
                            </svg>
                        </span>
                        <div>
                            <h2 id="settings-favorite-title">Favorit</h2>
                            <p>Koleksi yang Anda tandai untuk dibaca kembali nanti.</p>
                        </div>
                    </header>

                    <div class="settings-rows">
                        <div class="settings-row">
                            <div class="settings-row-copy">
                                <strong>Daftar favorit ({{ number_format($favoriteCount, 0, ',', '.') }})</strong>
                                <span>Favorit tersimpan di sesi browser dan akan hilang jika sesi berakhir atau data browser dibersihkan.</span>
                            </div>
                            @if ($favoriteCount > 0)
                                <form method="POST" action="{{ route('favorites.clear') }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-secondary btn-sm" type="submit">Kosongkan favorit</button>
                                </form>
                            @else
                                <a class="btn btn-secondary btn-sm" href="{{ route('library') }}">Cari koleksi</a>
                            @endif
                        </div>
                    </div>
                </section>

                <section class="settings-card" id="bantuan" aria-labelledby="settings-help-title">
                    <header class="settings-card-head">
                        <span class="settings-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="8.5"/>
                                <path d="M9.7 9.3a2.5 2.5 0 1 1 3.5 2.3c-.7.4-1.2 1-1.2 1.8v.2"/>
                                <path d="M12 17h.01"/>
                            </svg>
                        </span>
                        <div>
                            <h2 id="settings-help-title">Bantuan</h2>
                            <p>Panduan penggunaan perpustakaan digital.</p>
                        </div>
                    </header>

                    <div class="settings-rows">
                        <div class="settings-row">
                            <div class="settings-row-copy">
                                <strong>Pusat bantuan</strong>
                                <span>Pelajari cara mencari, membaca, mengunduh, dan menyimpan koleksi.</span>
                            </div>
                            <a class="btn btn-secondary btn-sm" href="{{ route('help') }}">Buka bantuan <span aria-hidden="true">&rarr;</span></a>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
