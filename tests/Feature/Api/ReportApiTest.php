<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\Author;
use App\Models\Book;
use App\Models\Subject;
use App\Services\ReportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_report_view_does_not_duplicate_rows_with_multiple_authors_and_subjects(): void
    {
        $authors = Author::factory()->count(3)->create();
        $subjects = Subject::factory()->count(3)->create();

        $book = Book::factory()->create([
            'titulo' => 'Antologia da Literatura Brasileira',
        ]);

        $book->authors()->attach($authors->pluck('cod_autor'));
        $book->subjects()->attach($subjects->pluck('cod_assunto'));

        $bookRows = app(ReportService::class)
            ->booksByAuthor()
            ->where('title', 'Antologia da Literatura Brasileira')
            ->values();

        // 3 authors × 3 subjects without aggregation = 9 rows.
        // VIEW aggregates subjects: exactly 3 rows (one per author).
        $this->assertCount(3, $bookRows);

        foreach ($bookRows as $row) {
            $subjectsInRow = array_map('trim', explode(',', (string) $row->subjects));
            $this->assertCount(3, $subjectsInRow);
        }

        $authorsInReport = $bookRows->pluck('authorId')->sort()->values()->all();
        $this->assertSame(
            $authors->pluck('cod_autor')->sort()->values()->all(),
            $authorsInReport
        );
    }

    public function test_report_pdf_download(): void
    {
        $author = Author::factory()->create(['nome' => 'PDF Author']);
        $subject = Subject::factory()->create(['descricao' => 'Romance']);
        $book = Book::factory()->create(['titulo' => 'PDF Book']);
        $book->authors()->attach($author->cod_autor);
        $book->subjects()->attach($subject->cod_assunto);

        $response = $this->get('/api/reports/books-by-author/pdf');

        $response->assertOk();
        $this->assertStringContainsString('application/pdf', (string) $response->headers->get('Content-Type'));
        $this->assertStringContainsString(
            'books-by-author.pdf',
            (string) $response->headers->get('Content-Disposition')
        );
        $this->assertNotEmpty($response->getContent());
        $this->assertStringStartsWith('%PDF', (string) $response->getContent());
    }
}
