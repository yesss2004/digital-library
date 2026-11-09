<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Support\AnyflipCatalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LibraryController extends Controller
{
    /**
     * Halaman utama dashboard (Discover).
     */
    public function discover()
    {
        return view('discover', [
            'anyflipBooks' => AnyflipCatalog::books(),
            'categories' => $this->categoryListWithCounts(),
        ]);
    }

    /**
     * Daftar seluruh kategori.
     */
    public function categories()
    {
        $categories = $this->categoryListWithCounts();

        return view('pages.categories', compact('categories'));
    }

    /**
     * Buku dalam satu kategori.
     */
    public function showCategory($slug)
    {
        $category = $this->findCategory($slug);
        abort_if($category === null, 404);

        $books = AnyflipCatalog::booksInCategory($slug);

        return view('pages.category', compact('category', 'books'));
    }

    /**
     * Detail buku.
     */
    public function book($slug)
    {
        $book = $this->findBook($slug);
        abort_if($book === null, 404);

        $category = $this->findCategory($book['category']);
        $isFavorite = in_array($book['slug'], $this->favoriteSlugs(), true);

        return view('pages.book', compact('book', 'category', 'isFavorite'));
    }

    /**
     * Reader view for a book with a configured online reading link.
     */
    public function readBook($slug)
    {
        $book = $this->findBook($slug);
        abort_if($book === null, 404);

        return view('pages.reader', compact('book'));
    }

    /**
     * Unduh dokumen PDF asli dari private storage.
     */
    public function downloadBook($slug): StreamedResponse
    {
        $book = $this->findBook($slug);
        abort_if($book === null, 404);

        if (empty($book['pdf_file'])) {
            abort(404, 'Dokumen PDF untuk koleksi ini belum tersedia.');
        }

        $path = $book['pdf_file'];
        $disk = Storage::disk('local');

        abort_unless($disk->exists($path), 404, 'Dokumen PDF tidak ditemukan.');

        $filename = (Str::slug($book['title']) ?: 'dokumen').'.pdf';

        return $disk->download($path, $filename);
    }

    /**
     * Hasil pencarian buku.
     */
    public function search(Request $request)
    {
        $query = trim((string) $request->query('q', ''));
        $categorySlug = trim((string) $request->query('category', ''));
        $year = trim((string) $request->query('year', ''));
        $allBooks = $this->bookList();

        $books = collect($allBooks)->filter(function (array $book) use ($query, $categorySlug, $year) {
            $category = $this->findCategory($book['category']);

            $matchesQuery = $query === ''
                || $this->textContains($book['title'], $query)
                || $this->textContains($book['author'], $query)
                || $this->textContains($book['publisher'], $query)
                || $this->textContains($book['description'], $query)
                || $this->textContains($category['name'] ?? '', $query)
                || $this->textContains((string) $book['year'], $query);

            $matchesCategory = $categorySlug === '' || $book['category'] === $categorySlug;
            $matchesYear = $year === '' || (string) $book['year'] === $year;

            return $matchesQuery && $matchesCategory && $matchesYear;
        })->values()->all();

        $category = $this->findCategory($categorySlug);
        $years = collect($allBooks)
            ->pluck('year')
            ->filter()
            ->unique()
            ->sortDesc()
            ->values()
            ->all();

        return view('pages.search', [
            'books' => $books,
            'categories' => $this->categoryList(),
            'category' => $category,
            'query' => $query,
            'year' => $year,
            'years' => $years,
        ]);
    }

    /**
     * Koleksi yang tersimpan di perpustakaan pengguna.
     */
    public function library()
    {
        return view('pages.library', [
            'anyflipBooks' => AnyflipCatalog::books(),
        ]);
    }

    /**
     * Dokumen yang memiliki berkas PDF untuk diunduh.
     */
    public function downloads()
    {
        $books = array_values(array_filter(
            $this->bookList(),
            fn (array $book) => ! empty($book['pdf_url'])
        ));

        return view('pages.downloads', compact('books'));
    }

    /**
     * Buku favorit pengguna (disimpan pada session).
     */
    public function favorites()
    {
        $favoriteSlugs = $this->favoriteSlugs();
        $booksBySlug = collect($this->bookList())->keyBy('slug');

        $books = collect($favoriteSlugs)
            ->map(fn (string $slug) => $booksBySlug->get($slug))
            ->filter()
            ->values()
            ->all();

        return view('pages.favorites', compact('books'));
    }

    /**
     * Tambah/hapus buku dari favorit.
     */
    public function toggleFavorite(Request $request, string $slug)
    {
        $book = $this->findBook($slug);
        abort_if($book === null, 404);

        $favorites = $this->favoriteSlugs();
        $index = array_search($slug, $favorites, true);
        $added = $index === false;

        if ($added) {
            $favorites[] = $slug;
        } else {
            unset($favorites[$index]);
        }

        $favorites = array_values($favorites);
        $request->session()->put('library.favorites', $favorites);

        $message = $added
            ? "Buku \"{$book['title']}\" ditambahkan ke favorit."
            : "Buku \"{$book['title']}\" dihapus dari favorit.";

        if ($request->expectsJson()) {
            return response()->json([
                'favorite' => $added,
                'count' => count($favorites),
                'message' => $message,
            ]);
        }

        return back()->with('status', $message);
    }

    public function settings()
    {
        return view('pages.settings', [
            'favoriteCount' => count($this->favoriteSlugs()),
            'totalBooks' => count($this->bookList()),
        ]);
    }

    public function clearFavorites(Request $request)
    {
        $request->session()->forget('library.favorites');

        return back()->with('status', 'Daftar favorit berhasil dikosongkan.');
    }

    public function help()
    {
        return view('pages.help');
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function bookList(): array
    {
        $newBooks = [];

        try {
            $newBooks = Book::latest()
                ->get()
                ->map(fn (Book $book) => $book->toCatalogArray())
                ->all();
        } catch (\Throwable $exception) {
            // On hosts without a database (for example a fresh Vercel
            // deployment) the public catalog should still render using the
            // bundled catalog from config/library.php instead of a 500 page.
            report($exception);
        }

        return [...$newBooks, ...config('library.books', [])];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function categoryList(): array
    {
        return config('library.categories', []);
    }

    /**
     * Category list with the number of AnyFlip books in each category.
     *
     * @return array<int, array<string, mixed>>
     */
    private function categoryListWithCounts(): array
    {
        $counts = AnyflipCatalog::counts();

        return array_map(function (array $category) use ($counts) {
            $category['total_books'] = $counts[$category['slug']] ?? 0;

            return $category;
        }, $this->categoryList());
    }

    /**
     * @return array<string, mixed>|null
     */
    private function findBook(string $slug): ?array
    {
        foreach ($this->bookList() as $book) {
            if ($book['slug'] === $slug) {
                return $book;
            }
        }

        return null;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function findCategory(string $slug): ?array
    {
        foreach ($this->categoryList() as $category) {
            if ($category['slug'] === $slug) {
                return $category;
            }
        }

        return null;
    }

    /**
     * @return array<int, string>
     */
    private function favoriteSlugs(): array
    {
        $favorites = session('library.favorites', []);

        if (! is_array($favorites)) {
            return [];
        }

        return array_values(array_unique(array_filter($favorites, 'is_string')));
    }

    private function textContains(string $haystack, string $needle): bool
    {
        return Str::contains(Str::lower($haystack), Str::lower($needle));
    }
}
