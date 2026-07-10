<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Exceptions\BookNotFoundException;
use App\Models\Book;
use App\Repositories\Contracts\BookRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentBookRepository implements BookRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Book::query()
            ->with(['authors', 'subjects'])
            ->orderBy('titulo')
            ->paginate($perPage);
    }

    public function findById(int $id): ?Book
    {
        return Book::query()
            ->with(['authors', 'subjects'])
            ->find($id);
    }

    public function findByIdOrFail(int $id): Book
    {
        $book = $this->findById($id);

        if ($book === null) {
            throw new BookNotFoundException($id);
        }

        return $book;
    }

    public function create(array $attributes): Book
    {
        return Book::query()->create($attributes);
    }

    public function update(Book $book, array $attributes): Book
    {
        $book->update($attributes);

        return $book->refresh();
    }

    public function delete(Book $book): void
    {
        $book->delete();
    }

    public function syncRelations(Book $book, array $authorIds, array $subjectIds): Book
    {
        $book->authors()->sync($authorIds);
        $book->subjects()->sync($subjectIds);

        return $book->load(['authors', 'subjects']);
    }
}
