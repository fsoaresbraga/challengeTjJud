<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\Author;
use App\Models\Book;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_report_does_not_duplicate_rows_with_multiple_authors_and_subjects(): void
    {
        $authors = Author::factory()->count(3)->create();
        $subjects = Subject::factory()->count(3)->create();

        $book = Book::factory()->create([
            'titulo' => 'Antologia da Literatura Brasileira',
        ]);

        $book->authors()->attach($authors->pluck('cod_autor'));
        $book->subjects()->attach($subjects->pluck('cod_assunto'));

        $response = $this->getJson('/api/reports/books-by-author');

        $response->assertOk();

        $bookRows = collect($response->json('data'))
            ->where('title', 'Antologia da Literatura Brasileira')
            ->values();

        $this->assertCount(3, $bookRows);

        foreach ($bookRows as $row) {
            $subjectsInRow = array_map('trim', explode(',', (string) $row['subjects']));
            $this->assertCount(3, $subjectsInRow);
        }

        $authorsInReport = $bookRows->pluck('authorId')->sort()->values()->all();
        $this->assertSame(
            $authors->pluck('cod_autor')->sort()->values()->all(),
            $authorsInReport
        );
    }

    public function test_report_returns_expected_structure(): void
    {
        $author = Author::factory()->create(['nome' => 'Test Author']);
        $subject = Subject::factory()->create(['descricao' => 'Romance']);
        $book = Book::factory()->create(['titulo' => 'Test Book']);
        $book->authors()->attach($author->cod_autor);
        $book->subjects()->attach($subject->cod_assunto);

        $this->getJson('/api/reports/books-by-author')
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'authorId',
                        'authorName',
                        'bookId',
                        'title',
                        'publisher',
                        'edition',
                        'publicationYear',
                        'price',
                        'subjects',
                    ],
                ],
            ])
            ->assertJsonFragment([
                'authorName' => 'Test Author',
                'title' => 'Test Book',
                'subjects' => 'Romance',
            ]);
    }
}
