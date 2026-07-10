<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Subject;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface SubjectRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function findById(int $id): ?Subject;

    public function findByIdOrFail(int $id): Subject;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Subject;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Subject $subject, array $attributes): Subject;

    public function delete(Subject $subject): void;
}
