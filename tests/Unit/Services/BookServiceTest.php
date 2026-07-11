<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\DTOs\BookData;
use App\Exceptions\BookNotFoundException;
use App\Models\Book;
use App\Repositories\Contracts\BookRepositoryInterface;
use App\Services\BookService;
use Illuminate\Support\Facades\DB;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class BookServiceTest extends TestCase
{
    private BookRepositoryInterface&MockInterface $bookRepository;

    private BookService $bookService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bookRepository = Mockery::mock(BookRepositoryInterface::class);
        $this->bookService = new BookService($this->bookRepository);
    }

    public function test_create_persists_book_and_syncs_relations_inside_transaction(): void
    {
        $data = new BookData(
            title: 'Dom Casmurro',
            publisher: 'Garnier',
            edition: 1,
            publicationYear: 1899,
            price: '49.90',
            authorIds: [1, 2],
            subjectIds: [3],
        );

        $created = new Book([
            'titulo' => 'Dom Casmurro',
            'editora' => 'Garnier',
            'edicao' => 1,
            'ano_publicacao' => 1899,
            'valor' => '49.90',
        ]);
        $created->cod_livro = 10;

        $synced = clone $created;

        DB::shouldReceive('transaction')
            ->once()
            ->andReturnUsing(fn (callable $callback) => $callback());

        $this->bookRepository
            ->shouldReceive('create')
            ->once()
            ->with($data->toAttributes())
            ->andReturn($created);

        $this->bookRepository
            ->shouldReceive('syncRelations')
            ->once()
            ->with($created, [1, 2], [3])
            ->andReturn($synced);

        $result = $this->bookService->create($data);

        $this->assertSame($synced, $result);
    }

    public function test_find_delegates_to_repository(): void
    {
        $book = new Book(['titulo' => 'Test']);
        $book->cod_livro = 5;

        $this->bookRepository
            ->shouldReceive('findByIdOrFail')
            ->once()
            ->with(5)
            ->andReturn($book);

        $this->assertSame($book, $this->bookService->find(5));
    }

    public function test_find_propagates_not_found_exception(): void
    {
        $this->bookRepository
            ->shouldReceive('findByIdOrFail')
            ->once()
            ->with(999)
            ->andThrow(new BookNotFoundException(999));

        $this->expectException(BookNotFoundException::class);

        $this->bookService->find(999);
    }

    public function test_delete_loads_and_removes_book_inside_transaction(): void
    {
        $book = new Book(['titulo' => 'To delete']);
        $book->cod_livro = 7;

        DB::shouldReceive('transaction')
            ->once()
            ->andReturnUsing(fn (callable $callback) => $callback());

        $this->bookRepository
            ->shouldReceive('findByIdOrFail')
            ->once()
            ->with(7)
            ->andReturn($book);

        $this->bookRepository
            ->shouldReceive('delete')
            ->once()
            ->with($book);

        $this->bookService->delete(7);

        $this->addToAssertionCount(1);
    }
}
