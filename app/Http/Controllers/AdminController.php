<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function loginForm(): View
    {
        return view('admin.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! $user->isAdmin() || ! Hash::check($credentials['password'], $user->password)) {
            return back()
                ->withErrors(['email' => 'Email atau kata sandi admin tidak sesuai.'])
                ->onlyInput('email');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    public function dashboard(): View
    {
        return view('admin.dashboard', [
            'books' => Book::with('creator')->latest()->paginate(12),
            'totalBooks' => count(config('library.books', [])) + Book::count(),
            'categories' => collect(config('library.categories', []))->keyBy('slug'),
        ]);
    }

    public function createBookForm(): View
    {
        return view('admin.books.create', [
            'categories' => config('library.categories', []),
        ]);
    }

    public function storeBook(Request $request): RedirectResponse
    {
        $validated = $this->validateBook($request);

        $coverPath = $request->file('cover_image')?->store('book-covers', 'public');
        $pdfPath = $request->file('pdf_file')?->store('book-pdfs', 'local');

        $book = Book::create([
            ...$validated,
            'slug' => $this->uniqueSlug($validated['title']),
            'cover_image' => $coverPath,
            'pdf_file' => $pdfPath,
            'created_by' => $request->user()->id,
        ]);

        return redirect()
            ->route('admin.dashboard')
            ->with('status', "Koleksi \"{$book->title}\" berhasil ditambahkan dan sekarang tampil di katalog.");
    }

    public function editBookForm(Book $book): View
    {
        return view('admin.books.edit', [
            'book' => $book,
            'categories' => config('library.categories', []),
        ]);
    }

    public function updateBook(Request $request, Book $book): RedirectResponse
    {
        $validated = $this->validateBook($request);

        // File uploads are handled separately so an empty input never erases
        // the existing cover or PDF.
        unset($validated['cover_image'], $validated['pdf_file']);

        if ($validated['title'] !== $book->title) {
            $validated['slug'] = $this->uniqueSlug($validated['title'], $book);
        }

        $oldCover = $book->cover_image;
        $oldPdf = $book->pdf_file;

        if ($cover = $request->file('cover_image')) {
            $validated['cover_image'] = $cover->store('book-covers', 'public');
        }

        if ($pdf = $request->file('pdf_file')) {
            $validated['pdf_file'] = $pdf->store('book-pdfs', 'local');
        }

        $book->update($validated);

        if (isset($validated['cover_image']) && $oldCover) {
            Storage::disk('public')->delete($oldCover);
        }

        if (isset($validated['pdf_file']) && $oldPdf) {
            Storage::disk('local')->delete($oldPdf);
        }

        return redirect()
            ->route('admin.dashboard')
            ->with('status', "Koleksi \"{$book->title}\" berhasil diperbarui.");
    }

    public function destroyBook(Book $book): RedirectResponse
    {
        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }

        if ($book->pdf_file) {
            Storage::disk('local')->delete($book->pdf_file);
        }

        $title = $book->title;
        $book->delete();

        return redirect()
            ->route('admin.dashboard')
            ->with('status', "Koleksi \"{$title}\" berhasil dihapus dari katalog.");
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('discover');
    }

    /**
     * @return array<string, mixed>
     */
    private function validateBook(Request $request): array
    {
        $categories = array_column(config('library.categories', []), 'slug');

        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'publisher' => ['required', 'string', 'max:255'],
            'year' => ['required', 'integer', 'min:1900', 'max:'.(now()->year + 1)],
            'pages' => ['nullable', 'integer', 'min:1', 'max:65535'],
            'category' => ['required', 'string', Rule::in($categories)],
            'description' => ['required', 'string', 'max:5000'],
            'reader_url' => ['nullable', 'url:http,https', 'max:2048'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'pdf_file' => ['nullable', 'file', 'mimes:pdf', 'max:20480'],
        ]);
    }

    private function uniqueSlug(string $title, ?Book $ignore = null): string
    {
        $baseSlug = Str::slug($title) ?: 'koleksi-buku';
        $slug = $baseSlug;
        $suffix = 2;
        $configSlugs = collect(config('library.books', []))->pluck('slug')->all();

        while (
            in_array($slug, $configSlugs, true)
            || Book::where('slug', $slug)
                ->when($ignore !== null, fn ($query) => $query->where('id', '!=', $ignore->id))
                ->exists()
        ) {
            $slug = $baseSlug.'-'.$suffix++;
        }

        return $slug;
    }
}
