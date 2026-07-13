<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\Author;
use App\Models\Book;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class BookApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_books(): void
    {
        $author = Author::factory()->create();
        $subject = Subject::factory()->create();
        $book = Book::factory()->create();
        $book->authors()->attach($author->cod_autor);
        $book->subjects()->attach($subject->cod_assunto);

        $this->getJson('/api/books')
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'bookId',
                        'title',
                        'publisher',
                        'edition',
                        'publicationYear',
                        'price',
                        'authors',
                        'subjects',
                    ],
                ],
            ]);
    }

    public function test_can_create_book_with_authors_and_subjects(): void
    {
        $authors = Author::factory()->count(2)->create();
        $subjects = Subject::factory()->count(2)->create();

        $payload = [
            'title' => 'Dom Casmurro',
            'publisher' => 'Garnier',
            'edition' => 1,
            'publicationYear' => 1899,
            'price' => 49.90,
            'authors' => $authors->pluck('cod_autor')->all(),
            'subjects' => $subjects->pluck('cod_assunto')->all(),
        ];

        $this->postJson('/api/books', $payload)
            ->assertCreated()
            ->assertJsonPath('data.title', 'Dom Casmurro')
            ->assertJsonCount(2, 'data.authors')
            ->assertJsonCount(2, 'data.subjects');

        $this->assertDatabaseHas('livro', ['titulo' => 'Dom Casmurro']);
    }

    public function test_create_book_without_title_returns_422(): void
    {
        $this->postJson('/api/books', [
            'publisher' => 'Garnier',
            'edition' => 1,
            'publicationYear' => 1899,
            'price' => 10,
            'authors' => [1],
            'subjects' => [1],
        ])
            ->assertUnprocessable()
            ->assertJsonPath('code', 'VALIDATION_ERROR')
            ->assertJsonStructure(['message', 'errors', 'code']);
    }

    public function test_create_book_rejects_title_and_publisher_over_max_length(): void
    {
        $author = Author::factory()->create();
        $subject = Subject::factory()->create();

        $this->postJson('/api/books', [
            'title' => str_repeat('a', 41),
            'publisher' => str_repeat('b', 41),
            'edition' => 1,
            'publicationYear' => 1899,
            'price' => 10,
            'authors' => [$author->cod_autor],
            'subjects' => [$subject->cod_assunto],
        ])
            ->assertUnprocessable()
            ->assertJsonPath('code', 'VALIDATION_ERROR')
            ->assertJsonValidationErrors(['title', 'publisher']);
    }

    public function test_can_show_update_and_delete_book(): void
    {
        $author = Author::factory()->create();
        $subject = Subject::factory()->create();
        $book = Book::factory()->create(['titulo' => 'Original']);
        $book->authors()->attach($author->cod_autor);
        $book->subjects()->attach($subject->cod_assunto);

        $this->getJson("/api/books/{$book->cod_livro}")
            ->assertOk()
            ->assertJsonPath('data.title', 'Original');

        $this->putJson("/api/books/{$book->cod_livro}", [
            'title' => 'Updated',
            'publisher' => 'New Publisher',
            'edition' => 2,
            'publicationYear' => 2001,
            'price' => 59.90,
            'authors' => [$author->cod_autor],
            'subjects' => [$subject->cod_assunto],
        ])
            ->assertOk()
            ->assertJsonPath('data.title', 'Updated');

        $this->deleteJson("/api/books/{$book->cod_livro}")
            ->assertNoContent();

        $this->assertDatabaseMissing('livro', ['cod_livro' => $book->cod_livro]);
    }

    public function test_missing_book_returns_404(): void
    {
        $this->getJson('/api/books/9999')
            ->assertNotFound()
            ->assertJsonPath('code', 'BOOK_NOT_FOUND');
    }

    public function test_duplicate_association_returns_409(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create();
        DB::table('livro_autor')->insert([
            'livro_id' => $book->cod_livro,
            'autor_id' => $author->cod_autor,
        ]);

        Route::post('/api/_force_dup_association', function () use ($book, $author) {
            DB::table('livro_autor')->insert([
                'livro_id' => $book->cod_livro,
                'autor_id' => $author->cod_autor,
            ]);
        });

        $this->postJson('/api/_force_dup_association')
            ->assertStatus(409)
            ->assertJsonPath('code', 'DUPLICATE_ASSOCIATION');
    }
}
