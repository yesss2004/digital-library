<?php

namespace Tests\Feature;

use App\Support\AnyflipCatalog;
use Tests\TestCase;

class AnyflipCatalogTest extends TestCase
{
    public function test_every_anyflip_book_is_grouped_into_a_known_category(): void
    {
        $slugs = array_column(config('library.categories', []), 'slug');
        $books = AnyflipCatalog::books();

        $this->assertCount(count(config('library.anyflip_books', [])), $books);
        $this->assertNotEmpty($books);

        foreach ($books as $book) {
            $this->assertContains($book['category'], $slugs);
        }
    }

    public function test_category_counts_match_the_grouped_books(): void
    {
        $counts = AnyflipCatalog::counts();

        $this->assertSame(count(AnyflipCatalog::books()), array_sum($counts));

        foreach ($counts as $slug => $count) {
            $this->assertSame($count, count(AnyflipCatalog::booksInCategory($slug)));
        }
    }

    public function test_titles_are_classified_from_keywords(): void
    {
        $this->assertSame('regulasi-kebencanaan', AnyflipCatalog::categoryFor('Undang-Undang Penanggulangan Bencana'));
        $this->assertSame('perencanaan-pb', AnyflipCatalog::categoryFor('Rencana Kontingensi Banjir'));
        $this->assertSame('mitigasi-bencana', AnyflipCatalog::categoryFor('Di Balik Peristiwa Tanah Longsor'));
        $this->assertSame('pendidikan-kebencanaan', AnyflipCatalog::categoryFor('Modul Dasar Manajemen Bencana'));
        $this->assertSame('publikasi-penelitian', AnyflipCatalog::categoryFor('Judul Tanpa Kata Kunci'));
    }

    public function test_a_category_page_lists_anyflip_books_for_that_category(): void
    {
        $book = AnyflipCatalog::booksInCategory('mitigasi-bencana')[0];

        $this->get('/categories/mitigasi-bencana')
            ->assertOk()
            ->assertSee($book['title'])
            ->assertSee($book['url'])
            ->assertSee('Buka flipbook di AnyFlip');
    }

    public function test_publications_page_groups_books_by_category(): void
    {
        $this->get('/library')
            ->assertOk()
            ->assertSee('publish-group-title')
            ->assertSee('Regulasi Kebencanaan')
            ->assertSee('Sistem Peringatan Dini')
            ->assertSee('Publikasi dan Penelitian')
            ->assertSee(count(config('library.anyflip_books', [])).' buku');
    }
}
