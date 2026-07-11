<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\DTOs\SubjectData;
use App\Http\Controllers\Concerns\ResolvesPerPage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Subject\StoreSubjectRequest;
use App\Http\Requests\Subject\UpdateSubjectRequest;
use App\Http\Resources\SubjectResource;
use App\Services\SubjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class SubjectController extends Controller
{
    use ResolvesPerPage;

    public function __construct(
        private readonly SubjectService $subjectService,
    ) {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        return SubjectResource::collection(
            $this->subjectService->list($this->perPage($request))
        );
    }

    public function store(StoreSubjectRequest $request): JsonResponse
    {
        $subject = $this->subjectService->create(SubjectData::fromArray($request->validated()));

        return (new SubjectResource($subject))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(int $subject): SubjectResource
    {
        return new SubjectResource($this->subjectService->find($subject));
    }

    public function update(UpdateSubjectRequest $request, int $subject): SubjectResource
    {
        $updated = $this->subjectService->update(
            $subject,
            SubjectData::fromArray($request->validated()),
        );

        return new SubjectResource($updated);
    }

    public function destroy(int $subject): JsonResponse
    {
        $this->subjectService->delete($subject);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
