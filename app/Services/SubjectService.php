<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\SubjectData;
use App\Models\Subject;
use App\Repositories\Contracts\SubjectRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SubjectService
{
    public function __construct(
        private readonly SubjectRepositoryInterface $subjectRepository,
    ) {
    }

    public function list(int $perPage = 15): LengthAwarePaginator
    {
        return $this->subjectRepository->paginate($perPage);
    }

    public function find(int $id): Subject
    {
        return $this->subjectRepository->findByIdOrFail($id);
    }

    public function create(SubjectData $data): Subject
    {
        return $this->subjectRepository->create($data->toAttributes());
    }

    public function update(int $id, SubjectData $data): Subject
    {
        $subject = $this->subjectRepository->findByIdOrFail($id);

        return $this->subjectRepository->update($subject, $data->toAttributes());
    }

    public function delete(int $id): void
    {
        $subject = $this->subjectRepository->findByIdOrFail($id);
        $this->subjectRepository->delete($subject);
    }
}
