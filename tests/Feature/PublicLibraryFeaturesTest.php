<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PublicLibraryFeaturesTest extends TestCase
{
    use RefreshDatabase;

    public function test_visitors_can_add_and_remove_favorites_from_the_session(): void
    {
        $slug = 'panduan-mitigasi-banjir-sungai-code';

        $this->from(route('books.show', $slug))
            ->post(route('favorites.toggle', $slug))
            ->assertRedirect(route('books.show', $slug))
            ->assertSessionHas('status', 'Buku "Panduan Mitigasi Banjir Sungai Code" ditambahkan ke favorit.');

        $this->get(route('favorites'))
            ->assertOk()
            ->assertSee('Panduan Mitigasi Banjir Sungai Code')
            ->assertDontSee('Belum ada favorit');

        $this->from(route('books.show', $slug))
            ->post(route('favorites.toggle', $slug))
            ->assertRedirect(route('books.show', $slug));

        $this->get(route('favorites'))
            ->assertOk()
            ->assertSee('Belum ada favorit');
    }

    public function test_visitors_can_clear_all_favorites(): void
    {
        $this->post(route('favorites.toggle', 'modul-sekolah-aman-bencana'))->assertRedirect();
        $this->delete(route('favorites.clear'))->assertRedirect();

        $this->get(route('favorites'))
            ->assertOk()
            ->assertSee('Belum ada favorit');
    }

    public function test_search_only_shows_anyflip_books_and_filters_by_category(): void
    {
        $this->get('/search?q=Banjir')
            ->assertOk()
            ->assertSee('Rencana Kontingensi Banjir')
            ->assertSee('https://online.anyflip.com/ybfyv/trsw/')
            ->assertSee('Buka flipbook di AnyFlip')
            ->assertDontSee('Kajian Risiko Bencana Kota Yogyakarta')
            ->assertDontSee('Rencana Penanggulangan Bencana Kota Yogyakarta');

        $this->get('/search?q=Perencanaan+PB&category=perencanaan-pb')
            ->assertOk()
            ->assertSee('Buku Penyusunan Rencana Penanggulangan Bencana, Edisi VIII 2020')
            ->assertDontSee('Kajian Risiko Bencana Kota Yogyakarta');
    }

    public function test_pdf_download_is_served_from_private_storage(): void
    {
        Storage::fake('local');

        $admin = User::factory()->create();
        $admin->is_admin = true;
        $admin->save();

        $book = Book::create([
            'slug' => 'panduan-unduhan',
            'title' => 'Panduan Unduhan',
            'author' => 'BPBD Kota Yogyakarta',
            'publisher' => 'BPBD Kota Yogyakarta',
            'year' => 2024,
            'pages' => 12,
            'category' => 'mitigasi-bencana',
            'description' => 'Dokumen contoh untuk pengujian unduhan.',
            'pdf_file' => 'book-pdfs/panduan-unduhan.pdf',
            'created_by' => $admin->id,
        ]);

        Storage::disk('local')->put($book->pdf_file, '%PDF-1.4 contoh');

        $this->get(route('books.download', $book->slug))
            ->assertOk()
            ->assertDownload('panduan-unduhan.pdf');

        $this->get(route('books.download', 'panduan-mitigasi-banjir-sungai-code'))
            ->assertNotFound();
    }

    public function test_downloads_page_only_lists_books_with_a_pdf(): void
    {
        $this->get(route('downloads'))
            ->assertOk()
            ->assertSee('Belum ada dokumen unduhan');

        $admin = User::factory()->create();
        $admin->is_admin = true;
        $admin->save();

        Book::create([
            'slug' => 'buku-dengan-pdf',
            'title' => 'Buku Dengan PDF',
            'author' => 'BPBD Kota Yogyakarta',
            'publisher' => 'BPBD Kota Yogyakarta',
            'year' => 2024,
            'category' => 'mitigasi-bencana',
            'description' => 'Koleksi dengan berkas PDF.',
            'pdf_file' => 'book-pdfs/buku-dengan-pdf.pdf',
            'created_by' => $admin->id,
        ]);

        $this->get(route('downloads'))
            ->assertOk()
            ->assertSee('Buku Dengan PDF')
            ->assertSee('Unduh PDF');
    }

    public function test_library_page_shows_only_the_anyflip_bookcase(): void
    {
        $this->get(route('library'))
            ->assertOk()
            ->assertSee('My Library')
            ->assertSee('RAK DIGITAL')
            ->assertSee('Buku Tersedia')
            ->assertSee('Buka rak AnyFlip')
            ->assertDontSee('Koleksi lainnya')
            ->assertDontSee('11 koleksi tersedia');
    }

    public function test_discover_page_shows_only_the_anyflip_bookcase(): void
    {
        $this->get(route('discover'))
            ->assertOk()
            ->assertSee('Jelajahi koleksi')
            ->assertSee('Buka flipbook di AnyFlip')
            ->assertDontSee('Pedoman Penyusunan Rencana Penanggulangan Bencana');
    }

    public function test_health_check_route_reports_application_state(): void
    {
        $this->getJson(route('healthz'))
            ->assertOk()
            ->assertJsonPath('status', 'ok')
            ->assertJsonPath('vite_manifest', true)
            ->assertJsonPath('database', 'ok');
    }

    public function test_admin_access_is_hidden_from_public_nav_but_available_via_portal_route(): void
    {
        $this->get(route('discover'))
            ->assertOk()
            ->assertDontSee('Admin')
            ->assertDontSee(route('admin.login'));

        $this->get(route('portal.admin'))
            ->assertOk()
            ->assertSee('Masuk Admin')
            ->assertSee(route('admin.login'));
    }
}
