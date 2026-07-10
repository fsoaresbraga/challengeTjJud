<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Exceptions\SubjectNotFoundException;
use App\Models\Subject;
use App\Repositories\Contracts\SubjectRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentSubjectRepository implements SubjectRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Subject::query()
            ->orderBy('descricao')
            ->paginate($perPage);
    }

    public function findById(int $id): ?Subject
    {
        return Subject::query()->find($id);
    }

    public function findByIdOrFail(int $id): Subject
    {
        $subject = $this->findById($id);

        if ($subject === null) {
            throw new SubjectNotFoundException($id);
        }

        return $subject;
    }

    public function create(array $attributes): Subject
    {
        return Subject::query()->create($attributes);
    }

    public function update(Subject $subject, array $attributes): Subject
    {
        $subject->update($attributes);

        return $subject->refresh();
    }

    public function delete(Subject $subject): void
    {
        $subject->delete();
    }
}
