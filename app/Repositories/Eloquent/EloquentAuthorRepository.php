<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Exceptions\AuthorNotFoundException;
use App\Models\Author;
use App\Repositories\Contracts\AuthorRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentAuthorRepository implements AuthorRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Author::query()
            ->orderBy('nome')
            ->paginate($perPage);
    }

    public function findById(int $id): ?Author
    {
        return Author::query()->find($id);
    }

    public function findByIdOrFail(int $id): Author
    {
        $author = $this->findById($id);

        if ($author === null) {
            throw new AuthorNotFoundException($id);
        }

        return $author;
    }

    public function create(array $attributes): Author
    {
        return Author::query()->create($attributes);
    }

    public function update(Author $author, array $attributes): Author
    {
        $author->update($attributes);

        return $author->refresh();
    }

    public function delete(Author $author): void
    {
        $author->delete();
    }
}
