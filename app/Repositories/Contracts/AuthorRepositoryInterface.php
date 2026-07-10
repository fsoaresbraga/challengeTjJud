<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Author;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AuthorRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function findById(int $id): ?Author;

    public function findByIdOrFail(int $id): Author;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Author;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Author $author, array $attributes): Author;

    public function delete(Author $author): void;
}
