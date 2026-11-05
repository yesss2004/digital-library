# Deploy ke Vercel

Project ini memakai runtime komunitas [`vercel-php`](https://github.com/vercel-community/php)
karena Vercel tidak menjalankan PHP secara bawaan.

## File yang dipakai

| File | Fungsi |
| --- | --- |
| `vercel.json` | Menjalankan Vite, menyajikan folder `public`, dan mengarahkan request PHP ke `api/index.php`. |
| `api/index.php` | Entry point Laravel untuk Vercel. Storage dipindah ke `/tmp` karena filesystem Vercel read-only. |
| `.vercelignore` | Mencegah `vendor`, `node_modules`, dan `.env` ikut di-upload. |
| `public/build` | Hasil build Vite ikut di-commit agar manifest aset selalu tersedia di dalam function PHP. |

## 1. Push kode terbaru

Vercel hanya membangun kode yang ada di GitHub. Pastikan semua perubahan sudah di-commit dan di-push:

```powershell
git add .
git commit -m "Siapkan deploy Vercel dan koleksi AnyFlip"
git push origin main
```

## 2. Deploy

1. Buka project di <https://vercel.com>.
2. Buka **Settings → Build & Development Settings**.
3. Pastikan:
   - **Framework Preset**: `Other`
   - **Build Command**: `npm run build`
   - **Output Directory**: `public`
   - **Install Command**: `npm install`
4. Klik **Deploy** (atau **Redeploy** pada deployment terakhir).

Nilai di atas sudah ditulis di `vercel.json`, jadi seharusnya otomatis. Jika dashboard
masih menyimpan `dist`, ubah manual ke `public`.

## 3. Environment Variables

Buka **Settings → Environment Variables**, lalu tambahkan:

| Nama | Nilai | Wajib |
| --- | --- | --- |
| `APP_KEY` | sudah ada di `vercel.json`; boleh dipindah ke sini | ya |
| `APP_URL` | `https://nama-project.vercel.app` | disarankan |
| `DB_CONNECTION` | `mysql` | untuk admin/katalog |
| `DB_HOST` | host MySQL eksternal | untuk admin/katalog |
| `DB_PORT` | `3306` | untuk admin/katalog |
| `DB_DATABASE` | nama database | untuk admin/katalog |
| `DB_USERNAME` | user database | untuk admin/katalog |
| `DB_PASSWORD` | password database | untuk admin/katalog |
| `MYSQL_ATTR_SSL_CA` | path sertifikat SSL, jika penyedia mewajibkan | opsional |

`VERCEL_URL` dipakai otomatis sebagai `APP_URL` jika `APP_URL` tidak diisi.

## 4. Database

Halaman **Beranda** dan **My Library** hanya membaca `config/anyflip-books.json`,
jadi tetap tampil tanpa database.

Halaman **kategori, pencarian, detail buku, favorit, unduhan, dan admin** membutuhkan
MySQL. Vercel tidak menyediakan MySQL, jadi pakai database eksternal (misalnya
Aiven, Railway, TiDB Cloud, atau hosting MySQL lain), lalu jalankan migrasi dari
komputer lokal dengan env yang mengarah ke database tersebut:

```powershell
$env:DB_HOST="host-db"
$env:DB_PORT="3306"
$env:DB_DATABASE="nama_db"
$env:DB_USERNAME="user_db"
$env:DB_PASSWORD="password_db"

php artisan migrate --force
php artisan library:make-admin admin@contoh.go.id "Admin Perpustakaan"
```

Setelah itu isi env yang sama di dashboard Vercel, lalu redeploy.

## 5. Batasan Vercel

- Filesystem hanya bisa ditulis di `/tmp` dan tidak permanen. File sampul/PDF yang
  di-upload lewat panel admin bisa hilang saat function dibuat ulang.
  Untuk penyimpanan permanen gunakan disk S3 (misalnya Cloudflare R2 / AWS S3).
- Setiap function punya batas waktu eksekusi. Operasi berat sebaiknya dijalankan
  di lokal atau CI, bukan di request web.
- `vercel-php` adalah runtime komunitas, bukan runtime resmi Vercel.

## 6. Setelah deploy

- Buka `https://nama-project.vercel.app` → Beranda dan My Library harus menampilkan
  buku AnyFlip.
- Buka `https://nama-project.vercel.app/admin/login` untuk masuk ke panel admin
  (butuh database yang sudah dimigrasi).
- Jika aset/CSS tidak muncul setelah mengubah CSS, jalankan `npm run build` lalu
  commit ulang folder `public/build`.
