# Kategori Buku AnyFlip

Koleksi AnyFlip (`config/anyflip-books.json`) tidak menyimpan kategori, jadi
kategori ditentukan otomatis dari **judul buku** oleh
`app/Support/AnyflipCatalog.php`.

## Cara kerja

1. Judul diubah ke huruf kecil.
2. Judul dicocokkan dengan daftar kata kunci tiap kategori, memakai pencocokan
   kata utuh (word boundary), bukan sekadar potongan kata.
3. Kategori pertama yang cocok dipakai (urutan aturan: regulasi, sistem
   peringatan dini, perencanaan, mitigasi, pendidikan, kesiapsiagaan,
   penanganan darurat, publikasi).
4. Buku yang tidak cocok dengan kata kunci mana pun masuk ke
   `publikasi-penelitian` supaya tetap tampil di salah satu kategori.

## Daftar kategori

| Slug | Nama kategori |
| --- | --- |
| `regulasi-kebencanaan` | Regulasi Kebencanaan |
| `perencanaan-pb` | Perencanaan PB |
| `mitigasi-bencana` | Mitigasi Bencana |
| `kesiapsiagaan-masyarakat` | Kesiapsiagaan Masyarakat |
| `pendidikan-kebencanaan` | Pendidikan Kebencanaan |
| `penanganan-darurat` | Penanganan Darurat |
| `sistem-peringatan-dini` | Sistem Peringatan Dini |
| `publikasi-penelitian` | Publikasi dan Penelitian |

## Mengubah pengelompokan

Tambahkan atau hapus kata kunci pada konstanta `CATEGORY_KEYWORDS` di
`app/Support/AnyflipCatalog.php`. Setelah diubah, cek distribusinya lewat
halaman **Informasi** (`/categories`) atau dengan menjalankan tes:

```bash
php artisan test --filter=AnyflipCatalog
```
