<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\BookData;
use App\Models\Book;
use App\Repositories\Contracts\BookRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class BookService
{
    public function __construct(
        private readonly BookRepositoryInterface $bookRepository,
    ) {
    }

    public function list(int $perPage = 15): LengthAwarePaginator
    {
        return $this->bookRepository->paginate($perPage);
    }

    public function find(int $id): Book
    {
        return $this->bookRepository->findByIdOrFail($id);
    }

    public function create(BookData $data): Book
    {
        return DB::transaction(function () use ($data): Book {
            $book = $this->bookRepository->create($data->toAttributes());

            return $this->bookRepository->syncRelations(
                $book,
                $data->authorIds,
                $data->subjectIds,
            );
        });
    }

    public function update(int $id, BookData $data): Book
    {
        return DB::transaction(function () use ($id, $data): Book {
            $book = $this->bookRepository->findByIdOrFail($id);
            $book = $this->bookRepository->update($book, $data->toAttributes());

            return $this->bookRepository->syncRelations(
                $book,
                $data->authorIds,
                $data->subjectIds,
            );
        });
    }

    public function delete(int $id): void
    {
        DB::transaction(function () use ($id): void {
            $book = $this->bookRepository->findByIdOrFail($id);
            $this->bookRepository->delete($book);
        });
    }
}
