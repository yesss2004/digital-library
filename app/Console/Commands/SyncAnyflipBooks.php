<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;

class SyncAnyflipBooks extends Command
{
    protected $signature = 'anyflip:sync
                            {--url= : URL bookcase AnyFlip yang akan disinkronkan}
                            {--file= : Path file HTML bookcase lokal sebagai sumber data}
                            {--insecure : Abaikan verifikasi sertifikat SSL (gunakan jika server bermasalah dengan CA lokal)}';

    protected $description = 'Sinkronkan daftar buku dan sampul dari rak AnyFlip ke config/anyflip-books.json';

    public function handle(): int
    {
        $url = $this->option('url') ?: config('library.anyflip_bookcase_url');
        $localFile = $this->option('file');

        try {
            $html = $localFile
                ? File::get($localFile)
                : Http::withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0 Safari/537.36',
                    'Accept' => 'text/html,application/xhtml+xml',
                ])->when($this->option('insecure'), fn ($http) => $http->withoutVerifying())
                    ->timeout(30)
                    ->get($url)
                    ->throw()
                    ->body();
        } catch (\Throwable $e) {
            $this->error("Gagal mengambil halaman bookcase: {$e->getMessage()}");

            return self::FAILURE;
        }

        // AnyFlip menanamkan seluruh data buku pada variabel JavaScript `bookData`.
        $json = $this->extractBookData($html);

        if ($json === null) {
            $this->error('Data buku (bookData) tidak ditemukan pada halaman bookcase.');

            return self::FAILURE;
        }

        try {
            $source = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            $this->error("Data buku tidak dapat dibaca: {$e->getMessage()}");

            return self::FAILURE;
        }

        $books = collect($source)
            ->filter(fn (array $book) => ! empty($book['id']) && ! empty($book['title']))
            ->map(fn (array $book) => [
                'id' => (string) $book['id'],
                'title' => trim($book['title']),
                'cover' => $this->normalizeUrl($book['coverimg'] ?? $book['cover'] ?? ''),
                'url' => $this->normalizeUrl($book['url'] ?? ''),
            ])
            ->filter(fn (array $book) => $book['cover'] !== '' && $book['url'] !== '')
            ->values()
            ->all();

        if ($books === []) {
            $this->error('Tidak ada buku valid yang ditemukan.');

            return self::FAILURE;
        }

        $path = config_path('anyflip-books.json');

        File::put(
            $path,
            json_encode($books, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE).PHP_EOL
        );

        $this->info(count($books).' buku AnyFlip disimpan ke '.$path);

        return self::SUCCESS;
    }

    /**
     * Ambil array JSON `bookData` dengan mencocokkan kurung siku secara berimbang.
     */
    private function extractBookData(string $html): ?string
    {
        $start = strpos($html, 'bookData:');

        if ($start === false) {
            return null;
        }

        $start = strpos($html, '[', $start);

        if ($start === false) {
            return null;
        }

        $depth = 0;
        $inString = false;
        $escaped = false;
        $length = strlen($html);

        for ($i = $start; $i < $length; $i++) {
            $char = $html[$i];

            if ($inString) {
                if ($escaped) {
                    $escaped = false;
                } elseif ($char === '\\') {
                    $escaped = true;
                } elseif ($char === '"') {
                    $inString = false;
                }

                continue;
            }

            if ($char === '"') {
                $inString = true;
            } elseif ($char === '[') {
                $depth++;
            } elseif ($char === ']') {
                $depth--;

                if ($depth === 0) {
                    return substr($html, $start, $i - $start + 1);
                }
            }
        }

        return null;
    }

    private function normalizeUrl(string $url): string
    {
        $url = trim($url);

        if ($url === '') {
            return '';
        }

        if (str_starts_with($url, '//')) {
            return 'https:'.$url;
        }

        return preg_replace('#^http://#i', 'https://', $url) ?? $url;
    }
}
