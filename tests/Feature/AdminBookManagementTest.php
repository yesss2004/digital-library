<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminBookManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_panel_redirects_guests_to_admin_login(): void
    {
        $this->get('/admin')->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_open_the_dashboard_and_new_book_form(): void
    {
        $admin = User::factory()->create();
        $admin->is_admin = true;
        $admin->save();

        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk()
            ->assertSee('Panel admin')
            ->assertSee('Tambah buku');

        $this->get('/admin/books/create')
            ->assertOk()
            ->assertSee('Tambah buku terbaru')
            ->assertSee('cover_image');
    }

    public function test_regular_users_cannot_access_admin_panel_or_add_books(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/admin')->assertForbidden();
        $this->actingAs($user)->post('/admin/books', [])->assertForbidden();
    }

    public function test_only_admin_credentials_can_log_in(): void
    {
        $user = User::factory()->create([
            'email' => 'reader@example.test',
            'password' => 'correct-password',
        ]);
        $this->post(route('admin.login.submit'), [
            'email' => $user->email,
            'password' => 'correct-password',
        ])->assertSessionHasErrors('email');

        $admin = User::factory()->create([
            'email' => 'admin@example.test',
            'password' => 'admin-password-123',
        ]);
        $admin->is_admin = true;
        $admin->save();

        $this->post(route('admin.login.submit'), [
            'email' => $admin->email,
            'password' => 'admin-password-123',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin);
    }

    public function test_admin_credentials_can_be_changed_with_the_console_command(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin-old@example.test',
            'password' => 'old-password-123',
        ]);
        $admin->is_admin = true;
        $admin->save();

        $this->artisan('library:update-admin', ['email' => 'admin-new@example.test'])
            ->expectsQuestion('Password admin baru (minimal 12 karakter)', 'new-password-123')
            ->expectsQuestion('Ulangi password admin baru', 'new-password-123')
            ->expectsOutput('Email dan password admin berhasil diperbarui untuk admin-new@example.test.')
            ->assertExitCode(0);

        $admin->refresh();
        $this->assertSame('admin-new@example.test', $admin->email);
        $this->assertTrue($admin->isAdmin());
        $this->assertTrue(Hash::check('new-password-123', $admin->password));
    }

    public function test_admin_to_update_can_be_selected_when_multiple_admins_exist(): void
    {
        $firstAdmin = User::factory()->create(['email' => 'admin-a@example.test']);
        $firstAdmin->is_admin = true;
        $firstAdmin->save();

        $secondAdmin = User::factory()->create(['email' => 'admin-b@example.test']);
        $secondAdmin->is_admin = true;
        $secondAdmin->save();

        $this->artisan('library:update-admin', ['email' => 'admin-new@example.test'])
            ->expectsChoice('Pilih email admin yang ingin diperbarui', 'admin-b@example.test', [
                'admin-a@example.test',
                'admin-b@example.test',
            ])
            ->expectsQuestion('Password admin baru (minimal 12 karakter)', 'new-password-123')
            ->expectsQuestion('Ulangi password admin baru', 'new-password-123')
            ->assertExitCode(0);

        $this->assertSame('admin-a@example.test', $firstAdmin->fresh()->email);
        $this->assertSame('admin-new@example.test', $secondAdmin->fresh()->email);
    }

    public function test_admin_can_add_a_book_and_it_appears_in_the_public_catalog(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create();
        $admin->is_admin = true;
        $admin->save();

        $this->actingAs($admin)->post(route('admin.books.store'), [
            'title' => 'Panduan Siaga Longsor',
            'author' => 'BPBD Kota Yogyakarta',
            'publisher' => 'BPBD Kota Yogyakarta',
            'year' => now()->year,
            'pages' => 28,
            'category' => 'mitigasi-bencana',
            'description' => 'Panduan kesiapsiagaan menghadapi risiko longsor.',
            'reader_url' => 'https://anyflip.com/example/book/',
            'cover_image' => UploadedFile::fake()->createWithContent(
                'cover.png',
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8DwHwAFAAH/q842iQAAAABJRU5ErkJggg==')
            ),
        ])->assertRedirect(route('admin.dashboard'));

        $book = Book::where('title', 'Panduan Siaga Longsor')->firstOrFail();
        $this->assertSame($admin->id, $book->created_by);
        Storage::disk('public')->assertExists($book->cover_image);

        $this->flushSession();

        $this->get('/')->assertOk()->assertDontSee('Panduan Siaga Longsor');
        $this->get('/categories/mitigasi-bencana')->assertOk()->assertSee('Panduan Siaga Longsor');
        $this->get('/library')->assertOk()->assertSee('Panduan Siaga Longsor');
        $this->get('/search?q=Longsor')->assertOk()->assertDontSee('Panduan Siaga Longsor');
        $this->get('/books/panduan-siaga-longsor')->assertOk()->assertSee('Panduan kesiapsiagaan');
        $this->get('/books/panduan-siaga-longsor/read')
            ->assertOk()
            ->assertSee('Isi buku Panduan Siaga Longsor')
            ->assertSee('https://anyflip.com/example/book/');
    }

    public function test_book_creation_rejects_categories_outside_the_library_structure(): void
    {
        $admin = User::factory()->create();
        $admin->is_admin = true;
        $admin->save();

        $this->actingAs($admin)->post(route('admin.books.store'), [
            'title' => 'Buku kategori tidak valid',
            'author' => 'BPBD Kota Yogyakarta',
            'publisher' => 'BPBD Kota Yogyakarta',
            'year' => now()->year,
            'category' => 'kategori-lain',
            'description' => 'Deskripsi contoh.',
        ])->assertSessionHasErrors('category');

        $this->assertDatabaseMissing('books', ['title' => 'Buku kategori tidak valid']);
    }
}
