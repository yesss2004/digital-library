# Struktur Folder Proyek Laravel
## Digital Library Jogja Tangguh Bencana

Struktur direktori berikut disesuaikan untuk framework **Laravel** guna mengorganisir modul kearsipan, pengelompokan 8 kategori kebencanaan, serta integrasi AnyFlip.

```text
digital-library-bpbd/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── DashboardController.php      # Analytics pembaca & statistik
│   │   │   │   ├── BookController.php           # CRUD metadata & unggah PDF
│   │   │   │   ├── CategoryController.php       # Manajemen 8 kategori kebencanaan
│   │   │   │   ├── BookcaseController.php       # Kelola Rak AnyFlip & Embed Code
│   │   │   │   └── QrCodeController.php         # Generator QR Code dokumen
│   │   │   └── Public/
│   │   │       ├── HomeController.php           # Landing page & katalog publik
│   │   │       ├── ReaderController.php         # Viewer iFrame AnyFlip
│   │   │       └── SearchController.php         # Fitur pencarian & filter
│   │   ├── Middleware/
│   │   │   ├── CheckPublicAccess.php            # Validasi dokumen publik vs internal
│   │   │   └── TrackReaderAnalytics.php         # Recording jumlah pembaca
│   │   └── Requests/
│   │       └── StoreBookRequest.php             # Validasi unggah PDF & metadata
│   ├── Models/
│   │   ├── Book.php                             # Model Buku Kebencanaan
│   │   ├── Category.php                         # Model Kategori
│   │   ├── Bookcase.php                         # Model Rak Buku AnyFlip
│   │   └── Analytic.php                         # Model Log Pengunjung & Pembaca
│   └── Services/
│       ├── AnyFlipService.php                   # Helper integrasi AnyFlip Embed
│       └── FileArchiveService.php               # Helper enkripsi & arsip PDF lokal
├── database/
│   ├── migrations/
│   │   ├── create_categories_table.php          # Seeder 8 Kategori Utama
│   │   ├── create_books_table.php               # Metadata & URL AnyFlip
│   │   ├── create_bookcases_table.php           # Tabel Rak Buku
│   │   └── create_analytics_table.php
│   └── seeders/
│       └── CategorySeeder.php                   # Inisialisasi Kategori Regulasi, SPAB, KTB, dll.
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── app.blade.php                    # Layout Publik (Portal BPBD)
│   │   │   └── admin.blade.php                  # Layout Admin Dashboard
│   │   ├── public/
│   │   │   ├── index.blade.php                  # Halaman Utama Perpustakaan
│   │   │   ├── category.blade.php               # Filter per Kategori
│   │   │   ├── reader.blade.php                 # Reader AnyFlip (iFrame Embed)
│   │   │   └── search.blade.php                 # Hasil Pencarian
│   │   └── admin/
│   │       ├── books/                           # Manajemen Koleksi Buku
│   │       ├── categories/                      # Manajemen Kategori
│   │       └── analytics/                       # Laporan Pengunjung
│   └── js/
│       └── app.js                               # Integrasi QR Code generator & iFrame handler
├── routes/
│   ├── web.php                                  # Rute Portal Utama & Admin
│   └── api.php                                  # API Endpoint (jika diintegrasikan ke CMS Pemkot)
└── storage/
    └── app/
        ├── private_archives/                    # Dokumen PDF Asli Induk (Terproteksi)
        └── public/
            ├── covers/                          # Thumbnail Sampul Buku
            └── qrcodes/                         # File Image QR Code Generasi
```