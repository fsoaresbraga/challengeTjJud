<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\DTOs\AuthorData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Author\StoreAuthorRequest;
use App\Http\Requests\Author\UpdateAuthorRequest;
use App\Http\Resources\AuthorResource;
use App\Services\AuthorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class AuthorController extends Controller
{
    public function __construct(
        private readonly AuthorService $authorService,
    ) {
    }

    public function index(): AnonymousResourceCollection
    {
        return AuthorResource::collection($this->authorService->list());
    }

    public function store(StoreAuthorRequest $request): JsonResponse
    {
        $author = $this->authorService->create(AuthorData::fromArray($request->validated()));

        return (new AuthorResource($author))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(int $author): AuthorResource
    {
        return new AuthorResource($this->authorService->find($author));
    }

    public function update(UpdateAuthorRequest $request, int $author): AuthorResource
    {
        $updated = $this->authorService->update(
            $author,
            AuthorData::fromArray($request->validated()),
        );

        return new AuthorResource($updated);
    }

    public function destroy(int $author): JsonResponse
    {
        $this->authorService->delete($author);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
