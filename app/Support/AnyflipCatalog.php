<?php

namespace App\Support;

use Illuminate\Support\Str;

/**
 * Groups the AnyFlip books into the library categories.
 *
 * AnyFlip only exposes the book title, cover, and URL, so the category is
 * derived from keywords in the title. Rules are evaluated from top to bottom
 * and the first matching category wins. Books that match nothing fall back to
 * "Publikasi dan Penelitian" so every book stays visible in a category.
 */
class AnyflipCatalog
{
    private const DEFAULT_CATEGORY = 'publikasi-penelitian';

    /**
     * @var array<string, array<int, string>>
     */
    private const CATEGORY_KEYWORDS = [
        'regulasi-kebencanaan' => [
            'undang-undang', 'peraturan', 'perban', 'perka', 'perda', 'perwal',
            'instruksi menteri', 'surat edaran', 'pmk', 'kebijakan',
            'standar pelayanan', 'pedoman penetapan status', 'piagam', 'sphere',
            'konvensi', 'regulasi',
        ],
        'sistem-peringatan-dini' => [
            'peringatan dini', 'ews', 'pemantauan', 'sungai', 'winongo',
            'gajah wong', 'sirene', 'sensor', 'deteksi dini',
        ],
        'perencanaan-pb' => [
            'rencana', 'recana', 'renkon', 'kontingensi', 'kontinjensi', 'krb',
            'kajian risiko', 'rpb', 'rencana induk', 'perencanaan',
            'garis besar', 'direktif latihan', 'skenario', 'tatalaksana',
            'tahapan proses', 'strategi pengurangan risiko', 'sendai',
        ],
        'mitigasi-bencana' => [
            'mitigasi', 'banjir', 'longsor', 'gempa', 'erupsi', 'gunung',
            'merapi', 'tsunami', 'likuefaksi', 'cuaca', 'la nina', 'el nino',
            'kekeringan', 'kebakaran', 'siklon', 'patahan', 'seismic',
            'megathrust', 'geologi', 'bentang alam', 'letusan', 'abu vulkanik',
            'hidrometeorologi', 'kaldera',
        ],
        'pendidikan-kebencanaan' => [
            'sekolah', 'spab', 'modul', 'kurikulum', 'buku anak', 'pendidikan',
            'literasi', 'bahan ajar', 'siswa', 'pramuka', 'micro teaching',
            'mata pelatihan', 'edukasi', 'komik', 'buku saku', 'workshop',
            'fasilitator', 'pelatihan', 'tot', 'dmb', 'bimtek',
            'bimbingan teknis', 'tugas peserta', 'bahan tayang', 'karya tulis',
            'ilmiah', 'pelatih', 'materi kebencanaan', 'aku bangkit',
            'petunjuk user', 'buku dasar', 'teknik fasilitasi', 'coaching',
            'peningkatan sdm',
        ],
        'kesiapsiagaan-masyarakat' => [
            'kesiapsiagaan', 'siaga', 'simulasi', 'prbbk', 'ktb', 'fprb',
            'komunitas', 'keluarga', 'relawan', 'destana', 'forum', 'pelajar',
            'mahasiswa', 'desa', 'masyarakat', 'kampung', 'hari kesiapsiagaan',
            'protokol kesehatan', 'isolasi mandiri', 'covid', 'pandemi',
            'ppkm', 'mudik', 'psikososial', 'penyintas',
        ],
        'penanganan-darurat' => [
            'darurat', 'sop', 'logistik', 'pertolongan', 'kaji cepat',
            'pusdalops', 'trc', 'pengungsi', 'kesehatan', 'evakuasi',
            'komando', 'operasi', 'gudang', 'distribusi', 'peralatan',
            'bantuan', 'kemanusiaan', 'dana siap pakai',
            'status keadaan darurat', 'penanganan', 'rehabilitasi',
            'rekonstruksi', 'hunian', 'relokasi', 'rumah instan', 'mengangkat',
            'memindahkan', 'penderita', 'klaster', 'pengadaan lahan',
        ],
        'publikasi-penelitian' => [
            'laporan', 'profil', 'kajian', 'penelitian', 'evaluasi',
            'pembelajaran', 'inovasi', 'lakip', 'hasil survei', 'survei',
            'sambutan', 'rakornas', 'pra sidang', 'sinergitas', 'kerjasama',
            'kolaborasi', 'komunikasi', 'peran', 'indeks', 'tumbuh dan tangguh',
            'smt', 'sasaran kinerja', 'kadin', 'apbd', 'sipil militer',
            'integrasi klaster',
        ],
    ];

    /**
     * All AnyFlip books with a category attached.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function books(): array
    {
        $books = config('library.anyflip_books', []);

        if (! is_array($books)) {
            return [];
        }

        $books = array_filter($books, 'is_array');

        return array_values(array_map(function (array $book): array {
            $book['category'] = self::categoryFor((string) ($book['title'] ?? ''));

            return $book;
        }, $books));
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function booksInCategory(string $slug): array
    {
        return array_values(array_filter(
            self::books(),
            fn (array $book): bool => $book['category'] === $slug
        ));
    }

    /**
     * Number of AnyFlip books per category slug.
     *
     * @return array<string, int>
     */
    public static function counts(): array
    {
        $slugs = array_column(config('library.categories', []), 'slug');
        $counts = array_fill_keys($slugs, 0);

        foreach (self::books() as $book) {
            if (array_key_exists($book['category'], $counts)) {
                $counts[$book['category']]++;
            }
        }

        return $counts;
    }

    public static function categoryFor(string $title): string
    {
        $title = Str::lower($title);

        foreach (self::CATEGORY_KEYWORDS as $slug => $keywords) {
            foreach ($keywords as $keyword) {
                $pattern = '/\b'.preg_quote($keyword, '/').'\b/';

                if (preg_match($pattern, $title) === 1) {
                    return $slug;
                }
            }
        }

        return self::DEFAULT_CATEGORY;
    }
}
