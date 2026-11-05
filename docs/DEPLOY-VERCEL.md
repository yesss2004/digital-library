# Deploy ke Vercel

Project ini memakai runtime komunitas [`vercel-php`](https://github.com/vercel-community/php)
karena Vercel tidak menjalankan PHP secara bawaan.

## File yang dipakai

| File | Fungsi |
| --- | --- |
| `vercel.json` | Menjalankan function PHP, menyajikan aset dari `public/`, dan mengarahkan request lain ke Laravel. |
| `api/index.php` | Entry point Laravel untuk Vercel. Storage dipindah ke `/tmp` karena filesystem Vercel read-only. |
| `.vercelignore` | Mencegah `vendor`, `node_modules`, dan `.env` ikut di-upload. |
| `public/build` | Hasil build Vite **ikut di-commit** supaya aset dan manifest selalu tersedia. |

## 1. Build aset lalu push

Setiap kali CSS/JS diubah, build ulang aset sebelum push:

```powershell
npm install
npm run build

git add .
git commit -m "Update aplikasi"
git push origin main
```

Vercel akan otomatis membuat deployment baru dari commit terakhir.

## 2. Pengaturan project di Vercel

Di **Settings → Build & Development Settings**:

- **Framework Preset**: `Other`
- **Build Command**: kosongkan / biarkan default
- **Output Directory**: kosongkan / biarkan default
- **Install Command**: kosongkan / biarkan default

`vercel.json` sudah mengatur semuanya. Jika dashboard masih menyimpan
`dist` dari percobaan sebelumnya, kosongkan atau ubah ke `Other` agar Vercel
tidak menjalankan build static yang membutuhkan folder `dist`.

### Deployment Protection (penting)

Jika saat membuka domain muncul halaman **"Login – Vercel"** (bukan tampilan
perpustakaan), situsnya **tidak rusak**. Itu adalah fitur **Deployment
Protection** milik Vercel yang mengunci semua deployment, termasuk production.

Cara membukanya:

1. Buka project di [vercel.com](https://vercel.com) → **Settings**.
2. Pilih **Deployment Protection**.
3. Pada **Vercel Authentication**, pilih **Disabled** — atau
   **Only Preview Deployments** jika proteksi hanya ingin dipakai di preview.
4. Klik **Save**, lalu buka domain production lagi (tidak perlu redeploy).

Jika masih muncul halaman login setelah itu, matikan juga **Password
Protection** dan pastikan domain yang dibuka ada di tab **Domains**.

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
- Jika CSS/JS berubah tetapi tampilan tidak berubah, jalankan `npm run build`,
  commit folder `public/build`, lalu push ulang.

## 7. Cek kesehatan aplikasi

Buka `https://nama-project.vercel.app/healthz`. Endpoint ini tidak butuh
database dan mengembalikan JSON seperti:

```json
{
  "status": "ok",
  "php": "8.2.x",
  "laravel": "10.x",
  "environment": "production",
  "debug": false,
  "url": "https://nama-project.vercel.app",
  "anyflip_books": 214,
  "vite_manifest": true,
  "storage_writable": true,
  "database": "ok"
}
```

- `vite_manifest: false` → folder `public/build` tidak ikut ter-deploy.
- `storage_writable: false` → entry point tidak memakai `/tmp`; cek `api/index.php`.
- `database: "unavailable"` → env MySQL belum benar. Halaman Beranda dan
  My Library tetap jalan tanpa database.

## 8. Troubleshooting

| Gejala | Penyebab | Solusi |
| --- | --- | --- |
| Halaman **"Login – Vercel"** di semua URL (termasuk CSS) | Deployment Protection aktif | Matikan **Vercel Authentication** (lihat bagian 2). |
| **500 Server Error** | Env/APP_KEY/database bermasalah | Buka **Deployments → Functions → Logs** di Vercel, atau cek `LOG_CHANNEL=stderr`. |
| Halaman tampil tanpa CSS/JS | `public/build` tidak ada atau route statis salah | Jalankan `npm run build`, commit `public/build`, push, lalu cek `/healthz`. |
| **404 NOT_FOUND** dari Vercel | Domain/URL salah atau project belum terhubung ke repo | Buka tab **Domains** di project dan pakai domain yang terdaftar. |
| Admin tidak bisa login | Database belum dimigrasi / akun belum dibuat | Isi env MySQL di Vercel, lalu jalankan `php artisan migrate --force` dan `php artisan library:make-admin`. |

