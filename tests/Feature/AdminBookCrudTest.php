<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminBookCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_a_book_and_replace_its_files(): void
    {
        Storage::fake('public');
        Storage::fake('local');

        [$admin, $book] = $this->makeBookWithFiles();

        $this->actingAs($admin)
            ->get(route('admin.books.edit', $book))
            ->assertOk()
            ->assertSee('Edit koleksi')
            ->assertSee('Buku Lama');

        $response = $this->actingAs($admin)->put(route('admin.books.update', $book), [
            'title' => 'Buku Baru',
            'author' => 'Penulis Baru',
            'publisher' => 'Penerbit Baru',
            'year' => now()->year,
            'pages' => 42,
            'category' => 'mitigasi-bencana',
            'description' => 'Deskripsi buku yang sudah diperbarui.',
            'reader_url' => 'https://anyflip.com/contoh/buku-baru/',
            'cover_image' => UploadedFile::fake()->createWithContent(
                'sampul-baru.png',
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8DwHwAFAAH/q842iQAAAABJRU5ErkJggg==')
            ),
            'pdf_file' => UploadedFile::fake()->create('dokumen-baru.pdf', 200, 'application/pdf'),
        ]);

        $response->assertRedirect(route('admin.dashboard'));

        $book->refresh();

        $this->assertSame('buku-baru', $book->slug);
        $this->assertSame('Penulis Baru', $book->author);
        $this->assertNotSame('book-covers/lama.jpg', $book->cover_image);
        $this->assertNotSame('book-pdfs/lama.pdf', $book->pdf_file);

        Storage::disk('public')->assertMissing('book-covers/lama.jpg');
        Storage::disk('public')->assertExists($book->cover_image);
        Storage::disk('local')->assertMissing('book-pdfs/lama.pdf');
        Storage::disk('local')->assertExists($book->pdf_file);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Buku Baru')
            ->assertSee('Mitigasi Bencana');
    }

    public function test_updating_a_book_without_files_keeps_existing_files(): void
    {
        Storage::fake('public');
        Storage::fake('local');

        [$admin, $book] = $this->makeBookWithFiles();

        $this->actingAs($admin)->put(route('admin.books.update', $book), [
            'title' => $book->title,
            'author' => 'Penulis Diperbarui',
            'publisher' => 'Penerbit Diperbarui',
            'year' => $book->year,
            'pages' => $book->pages,
            'category' => $book->category,
            'description' => 'Deskripsi tanpa mengganti berkas.',
        ])->assertRedirect(route('admin.dashboard'));

        $book->refresh();

        $this->assertSame('book-covers/lama.jpg', $book->cover_image);
        $this->assertSame('book-pdfs/lama.pdf', $book->pdf_file);
        $this->assertSame('buku-lama', $book->slug);

        Storage::disk('public')->assertExists('book-covers/lama.jpg');
        Storage::disk('local')->assertExists('book-pdfs/lama.pdf');
    }

    public function test_admin_can_delete_a_book_and_its_files(): void
    {
        Storage::fake('public');
        Storage::fake('local');

        [$admin, $book] = $this->makeBookWithFiles();

        $this->actingAs($admin)
            ->delete(route('admin.books.destroy', $book))
            ->assertRedirect(route('admin.dashboard'));

        $this->assertDatabaseMissing('books', ['id' => $book->id]);

        Storage::disk('public')->assertMissing('book-covers/lama.jpg');
        Storage::disk('local')->assertMissing('book-pdfs/lama.pdf');
    }

    public function test_regular_users_cannot_update_or_delete_books(): void
    {
        $user = User::factory()->create();
        $book = $this->makeBook();

        $this->actingAs($user)->get(route('admin.books.edit', $book))->assertForbidden();
        $this->actingAs($user)->put(route('admin.books.update', $book), [])->assertForbidden();
        $this->actingAs($user)->delete(route('admin.books.destroy', $book))->assertForbidden();

        $this->assertDatabaseHas('books', ['id' => $book->id]);
    }

    public function test_update_rejects_categories_outside_the_library_structure(): void
    {
        $admin = $this->makeAdmin();
        $book = $this->makeBook();

        $this->actingAs($admin)->put(route('admin.books.update', $book), [
            'title' => $book->title,
            'author' => $book->author,
            'publisher' => $book->publisher,
            'year' => $book->year,
            'category' => 'kategori-tidak-valid',
            'description' => $book->description,
        ])->assertSessionHasErrors('category');

        $this->assertSame('mitigasi-bencana', $book->fresh()->category);
    }

    /**
     * @return array{0: User, 1: Book}
     */
    private function makeBookWithFiles(): array
    {
        Storage::disk('public')->put('book-covers/lama.jpg', 'cover lama');
        Storage::disk('local')->put('book-pdfs/lama.pdf', '%PDF-1.4');

        $admin = $this->makeAdmin();

        $book = $this->makeBook([
            'cover_image' => 'book-covers/lama.jpg',
            'pdf_file' => 'book-pdfs/lama.pdf',
            'created_by' => $admin->id,
        ]);

        return [$admin, $book];
    }

    private function makeAdmin(): User
    {
        $admin = User::factory()->create();
        $admin->is_admin = true;
        $admin->save();

        return $admin;
    }

    private function makeBook(array $attributes = []): Book
    {
        $admin = User::where('is_admin', true)->first() ?? $this->makeAdmin();

        return Book::create(array_merge([
            'slug' => 'buku-lama',
            'title' => 'Buku Lama',
            'author' => 'Penulis Lama',
            'publisher' => 'Penerbit Lama',
            'year' => 2024,
            'pages' => 20,
            'category' => 'mitigasi-bencana',
            'description' => 'Deskripsi buku lama.',
            'created_by' => $admin->id,
        ], $attributes));
    }
}
