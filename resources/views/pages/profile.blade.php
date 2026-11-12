@extends('layouts.app')

@section('title', 'Profil Perpustakaan')

@section('content')
    <div class="page-wrap profile-page">
        <section class="profile-hero" aria-labelledby="profile-title">
            <div class="profile-hero-copy">
                <p class="eyebrow">PROFIL LAYANAN</p>
                <h1 class="page-title" id="profile-title">Literasi kebencanaan untuk Jogja yang lebih tangguh.</h1>
                <p class="profile-lead">
                    Temukan publikasi, modul, dan referensi kebencanaan dalam satu ruang baca digital.
                </p>
                <div class="profile-actions">
                    <a class="btn profile-primary-action" href="{{ route('library') }}">
                        Jelajahi koleksi <span aria-hidden="true">&rarr;</span>
                    </a>
                    <a class="profile-secondary-action" href="{{ route('categories.index') }}">Lihat kategori</a>
                </div>
            </div>

            <div class="profile-visual" aria-hidden="true">
                <div class="profile-visual-orbit">
                    <svg viewBox="0 0 64 64" fill="none">
                        <path d="M32 17c-6-5-14-5-22-2v33c8-3 16-3 22 2m0-33c6-5 14-5 22-2v33c-8-3-16-3-22 2m0-33v33" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <span class="profile-visual-label">JOGJA<br>TANGGUH BENCANA</span>
                <span class="profile-visual-caption">Literasi <i></i> Kesiapsiagaan <i></i> Ketangguhan</span>
            </div>
        </section>

        <section class="profile-section" aria-labelledby="profile-values-title">
            <div class="profile-section-heading">
                <p class="eyebrow">ARAH PENGEMBANGAN</p>
                <h2 class="section-title" id="profile-values-title">Visi, Misi, dan Tujuan Perpustakaan Digital</h2>
            </div>

            <div class="profile-values-grid">
                <article class="profile-value-card profile-vision">
                    <p class="eyebrow">VISI</p>
                    <h3>Menjadi pusat literasi dan edukasi kebencanaan digital yang terdepan untuk mewujudkan masyarakat Jogja yang tangguh, tanggap, dan siap menghadapi bencana.</h3>
                </article>

                <article class="profile-value-card profile-mission">
                    <p class="eyebrow">MISI</p>
                    <ol class="profile-mission-list">
                        <li>Menyediakan akses informasi dan literatur kebencanaan yang mudah dijangkau oleh seluruh lapisan masyarakat.</li>
                        <li>Meningkatkan kesadaran dan kesiapsiagaan warga melalui modul edukasi digital yang interaktif.</li>
                        <li>Mendukung riset dan pengembangan ilmu pengetahuan terkait mitigasi bencana di daerah.</li>
                    </ol>
                </article>

                <article class="profile-value-card profile-goal">
                    <p class="eyebrow">TUJUAN</p>
                    <h3>Menjadi referensi utama literasi kebencanaan berbasis teknologi di tingkat regional hingga nasional.</h3>
                </article>
            </div>
        </section>

        <section class="profile-section" aria-labelledby="profile-background-title">
            <div class="profile-background-grid">
                <div class="profile-background-copy">
                    <p class="eyebrow">TENTANG KAMI</p>
                    <h2 class="section-title" id="profile-background-title">Pengetahuan yang mudah dijangkau membantu membangun kesiapsiagaan.</h2>
                    <p>
                        Perpustakaan Digital Jogja Tangguh Bencana dikembangkan sebagai ruang akses terstruktur
                        untuk publikasi, modul, dan referensi penanggulangan bencana.
                    </p>
                    <p>
                        Di wilayah dengan beragam risiko kebencanaan, masyarakat membutuhkan bahan belajar yang
                        mudah ditemukan untuk memahami mitigasi, kesiapsiagaan, dan langkah penanganan darurat.
                        Katalog digital ini menjembatani kebutuhan tersebut dengan menghimpun koleksi yang dapat
                        dicari dan dibaca secara daring.
                    </p>
                    <p>
                        Panduan evakuasi, modul darurat, dan materi mitigasi dapat diakses sesuai ketersediaan
                        publikasi di dalam katalog.
                    </p>
                </div>

            </div>
        </section>

        <section class="profile-section" aria-labelledby="profile-features-title">
            <div class="profile-section-heading">
                <p class="eyebrow">FITUR UNGGULAN</p>
                <h2 class="section-title" id="profile-features-title">Dirancang untuk menemukan dan mempelajari koleksi.</h2>
            </div>

            <div class="profile-feature-grid">
                <article class="profile-feature-card">
                    <span class="profile-feature-index">01 / PENCARIAN</span>
                    <h3>Smart Search</h3>
                    <p>
                        Cari judul publikasi AnyFlip melalui kolom pencarian, lalu telusuri hasil yang tersedia
                        secara cepat dan responsif.
                    </p>
                    <a class="profile-feature-link" href="{{ route('search') }}">Mulai mencari <span aria-hidden="true">&rarr;</span></a>
                </article>

                <article class="profile-feature-card">
                    <span class="profile-feature-index">02 / AKSES</span>
                    <h3>Ramah untuk berbagai perangkat</h3>
                    <p>
                        Tata letak responsif membantu masyarakat dan relawan mengakses katalog melalui
                        smartphone maupun laptop.
                    </p>
                    <a class="profile-feature-link" href="{{ route('library') }}">Buka perpustakaan <span aria-hidden="true">&rarr;</span></a>
                </article>

                <article class="profile-feature-card profile-feature-catalog">
                    <span class="profile-feature-index">03 / KOLEKSI</span>
                    <h3>Koleksi terkurasi</h3>
                    <p>Publikasi dikelompokkan ke dalam {{ count(config('library.categories', [])) }} kategori:</p>
                    <div class="profile-category-chips" aria-label="Kategori koleksi">
                        @foreach (config('library.categories', []) as $category)
                            <span>{{ $category['name'] }}</span>
                        @endforeach
                    </div>
                    <a class="profile-feature-link" href="{{ route('categories.index') }}">Lihat semua kategori <span aria-hidden="true">&rarr;</span></a>
                </article>
            </div>
        </section>

        <aside class="profile-closing">
            <div>
                <p class="eyebrow">MULAI MENJELAJAH</p>
                <h2>Temukan referensi kebencanaan yang Anda butuhkan.</h2>
            </div>
            <a class="btn profile-primary-action" href="{{ route('library') }}">
                Buka perpustakaan <span aria-hidden="true">&rarr;</span>
            </a>
        </aside>
    </div>
@endsection
