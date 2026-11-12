<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\LibraryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [LibraryController::class, 'discover'])->name('discover');
Route::view('/profil', 'pages.profile')->name('profile');

Route::get('/healthz', function () {
    $manifest = public_path('build/manifest.json');

    $checks = [
        'status' => 'ok',
        'php' => PHP_VERSION,
        'laravel' => app()->version(),
        'environment' => app()->environment(),
        'debug' => (bool) config('app.debug'),
        'url' => config('app.url'),
        'anyflip_books' => count(config('library.anyflip_books', [])),
        'catalog_books' => count(config('library.books', [])),
        'categories' => count(config('library.categories', [])),
        'vite_manifest' => is_file($manifest),
        'storage_writable' => is_writable(storage_path()),
    ];

    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        $checks['database'] = 'ok';

        try {
            $checks['database_books'] = \App\Models\Book::count();
        } catch (\Throwable $exception) {
            $checks['database_books'] = 'unavailable';
        }
    } catch (\Throwable $exception) {
        $checks['database'] = 'unavailable';
    }

    return response()->json($checks);
})->name('healthz');

Route::get('/portal-admin', function () {
    return view('pages.admin-portal');
})->name('portal.admin');
Route::get('/search', [LibraryController::class, 'search'])->name('search');

Route::get('/categories', [LibraryController::class, 'categories'])->name('categories.index');
Route::get('/categories/{category}', [LibraryController::class, 'showCategory'])->name('categories.show');

Route::get('/books/{book}/read', [LibraryController::class, 'readBook'])->name('books.read');
Route::get('/books/{book}/download', [LibraryController::class, 'downloadBook'])->name('books.download');
Route::get('/books/{book}', [LibraryController::class, 'book'])->name('books.show');

Route::get('/library', [LibraryController::class, 'library'])->name('library');
Route::get('/downloads', [LibraryController::class, 'downloads'])->name('downloads');
Route::get('/favorites', [LibraryController::class, 'favorites'])->name('favorites');
Route::post('/favorites/{book}', [LibraryController::class, 'toggleFavorite'])->name('favorites.toggle');
Route::delete('/favorites', [LibraryController::class, 'clearFavorites'])->name('favorites.clear');
Route::get('/settings', [LibraryController::class, 'settings'])->name('settings');
Route::get('/help', [LibraryController::class, 'help'])->name('help');

Route::middleware('guest')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminController::class, 'loginForm'])->name('login');
    Route::post('/login', [AdminController::class, 'login'])->middleware('throttle:5,1')->name('login.submit');
});

Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/books/create', [AdminController::class, 'createBookForm'])->name('books.create');
    Route::post('/books', [AdminController::class, 'storeBook'])->name('books.store');
    Route::get('/books/{book}/edit', [AdminController::class, 'editBookForm'])->name('books.edit');
    Route::put('/books/{book}', [AdminController::class, 'updateBook'])->name('books.update');
    Route::delete('/books/{book}', [AdminController::class, 'destroyBook'])->name('books.destroy');
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
});
