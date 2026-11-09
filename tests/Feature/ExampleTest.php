<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Jelajahi koleksi');
        $response->assertSee('brand-logo-button');
        $response->assertSee('bpbd-logo-dialog');
        $response->assertSee('images/logo-bpbd-yogyakarta.png');
        $response->assertDontSee('Pengetahuan kebencanaan dalam satu ruang.');
        $response->assertSee('Jl. Gambiran No. 26');
        $response->assertDontSee('(0274) 587101');
        $response->assertDontSee('Arsip tahunan');
        $response->assertDontSee('Koleksi unggulan');
        $response->assertDontSee('Jangkauan pembaca');
        $response->assertSee('Struktur perpustakaan');
    }

    public function test_the_category_structure_lists_all_eight_categories(): void
    {
        $response = $this->get('/categories');

        $response->assertOk();
        $response->assertSee('Regulasi Kebencanaan');
        $response->assertSee('Kesiapsiagaan Masyarakat');
        $response->assertSee('EWS Code, Winongo, Gajah Wong dan sungai lainnya');
        $response->assertSee('Publikasi dan Penelitian');
    }

    public function test_a_category_page_shows_its_books(): void
    {
        $response = $this->get('/categories/mitigasi-bencana');

        $response->assertOk();
        $response->assertSee('Mitigasi Bencana');
        $response->assertSee('Di Balik Peristiwa Tanah Longsor Bukit Menoreh Purworejo');
    }

    public function test_search_finds_disaster_library_collections(): void
    {
        $response = $this->get('/search?q=banjir');

        $response->assertOk();
        $response->assertSee('Panduan Mitigasi Banjir Sungai Code');
        $response->assertSee('Panduan Sistem Peringatan Dini Banjir');
    }
}
