<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\AuthorData;
use App\Models\Author;
use App\Repositories\Contracts\AuthorRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AuthorService
{
    public function __construct(
        private readonly AuthorRepositoryInterface $authorRepository,
    ) {
    }

    public function list(int $perPage = 15): LengthAwarePaginator
    {
        return $this->authorRepository->paginate($perPage);
    }

    public function find(int $id): Author
    {
        return $this->authorRepository->findByIdOrFail($id);
    }

    public function create(AuthorData $data): Author
    {
        return $this->authorRepository->create($data->toAttributes());
    }

    public function update(int $id, AuthorData $data): Author
    {
        $author = $this->authorRepository->findByIdOrFail($id);

        return $this->authorRepository->update($author, $data->toAttributes());
    }

    public function delete(int $id): void
    {
        $author = $this->authorRepository->findByIdOrFail($id);
        $this->authorRepository->delete($author);
    }
}
