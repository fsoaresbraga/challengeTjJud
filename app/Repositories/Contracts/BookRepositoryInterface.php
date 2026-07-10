<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Book;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface BookRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function findById(int $id): ?Book;

    public function findByIdOrFail(int $id): Book;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Book;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Book $book, array $attributes): Book;

    public function delete(Book $book): void;

    /**
     * @param  list<int>  $authorIds
     * @param  list<int>  $subjectIds
     */
    public function syncRelations(Book $book, array $authorIds, array $subjectIds): Book;
}
