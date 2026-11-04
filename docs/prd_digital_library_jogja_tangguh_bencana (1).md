# Product Requirement Document (PRD)
## Digital Library Jogja Tangguh Bencana (Knowledge Management Platform)

---

## 1. Ringkasan Eksekutif & Tujuan
**Digital Library Jogja Tangguh Bencana** adalah platform Knowledge Management Platform (KMP) kebencanaan berbasis web yang dikembangkan untuk BPBD Kota Yogyakarta (Rencana Pengembangan 2026–2027). Platform ini bertujuan memberikan akses cepat dan terstruktur terhadap dokumen, modul, dan regulasi kebencanaan bagi ASN, KTB (Kampung Tangguh Bencana), FPRB, sekolah (SPAB), relawan, dan masyarakat umum.

### Konsep Dua Lapis (Hybrid Architecture):
1. **Website Portal BPBD (Pintu Utama/Backend Engine):** Manajemen katalog, pencarian metadata, kearsipan PDF asli terproteksi, serta analitik.
2. **AnyFlip Integration (Ruang Baca Interactive):** Penyajian dokumen PDF dalam bentuk *flipbook* interaktif yang mudah dibaca via HP/Komputer melalui *embed iFrame*.

---

## 2. Target Pengguna (User Personas)

| Peran Pengguna | Deskripsi & Tujuan Access |
| :--- | :--- |
| **Masyarakat Umum & Relawan** | Membaca buku saku kesiapsiagaan, panduan KTB, leaflet, dan regulasi tanpa memerlukan proses autentikasi yang rumit. |
| **Petugas / Relawan KTB & SPAB** | Mengakses modul pelatihan, panduan sekolah aman bencana, dan SOP kedaruratan. |
| **Pengelola Perpustakaan / Operator BPBD** | Mengunggah PDF, memasukkan metadata, mengatur integrasi iFrame AnyFlip, dan memilah dokumen publik vs internal. |
| **Administrator System / Diskominfo** | Mengelola *role-permission*, konfigurasi domain (`bpbd.jogjakota.go.id`), dan backup basis data. |

---

## 3. Struktur Pengelompokan Koleksi (8 Kategori Utama)

1. **Regulasi Kebencanaan:** UU, PP, Permendagri, Peraturan BNPB, Perda, Perwal.
2. **Perencanaan PB:** KRB (Kajian Risiko Bencana), RPB, RPKB, Rencana Kontingensi (Renkon).
3. **Mitigasi Bencana:** Gempa bumi, banjir (Code, Winongo, Gajah Wong, Belik), cuaca ekstrem, longsor.
4. **Kesiapsiagaan Masyarakat:** Panduan KTB, FPRB, Keluarga Tangguh Bencana.
5. **Pendidikan Kebencanaan:** SPAB (Sekolah Aman Bencana), modul guru, buku saku siswa.
6. **Penanganan Darurat:** TRC, Pusdalops, kaji cepat, SOP Kedaruratan.
7. **Sistem Peringatan Dini (EWS):** EWS sungai dan kebencanaan wilayah.
8. **Publikasi dan Penelitian:** Profil BPBD, laporan kinerja, dokumentasi, hasil kajian ilmiah.

---

## 4. Fitur Utama & Kebutuhan Fungsional

### 4.1. Portal Publik (Front-End)
* **Search Engine & Filter:** Pencarian berdasarkan judul, kategori, tahun terbit, dan *keywords*.
* **Display Flipbook Reader:** Integrasi *iFrame/Lightbox* AnyFlip untuk membaca langsung di halaman tanpa *download*.
* **Direct Download (Optional):** Tombol unduh file PDF asli jika dokumen berstatus *Public Downloadable*.
* **QR Code Generator:** Pembuatan QR Code otomatis per dokumen/rak untuk kebutuhan sosialisasi dan cetak banner/leaflet.

### 4.2. Panel Admin (Back-End)
* **Manajemen Metadata Buku:** Input Judul, Penulis, Penerbit, Tahun, Kategori (1-8), Deskripsi, URL Embed AnyFlip, dan Unggah PDF Induk.
* **Manajemen Status Dokumen:** Penandaan dokumen *Publik* vs *Internal/Terbatas*.
* **Statistik & Analytics:** Pencatatan jumlah pengunjung, total pembaca per buku, dan buku terpopuler.
* **Workflow Persetujuan Dokumen:** *Pengumpulan -> Verifikasi -> Klasifikasi -> Persetujuan -> Publikasi*.

---

## 5. Kriteria Non-Fungsional & Keamanan
* **Keamanan Dokumen:** File PDF asli disimpan dalam *private storage* lokal/S3, sedangkan versi yang dibaca publik disajikan via iFrame AnyFlip.
* **Performansi:** Respon waktu muat katalog halaman utama $< 2$ detik.
* **Responsif:** Tampilan optimal pada perangkat seluler (*mobile-first design*).