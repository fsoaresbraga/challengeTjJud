<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\DTOs\BookData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Book\StoreBookRequest;
use App\Http\Requests\Book\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Services\BookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class BookController extends Controller
{
    public function __construct(
        private readonly BookService $bookService,
    ) {
    }

    public function index(): AnonymousResourceCollection
    {
        return BookResource::collection($this->bookService->list());
    }

    public function store(StoreBookRequest $request): JsonResponse
    {
        $book = $this->bookService->create(BookData::fromArray($request->validated()));

        return (new BookResource($book))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(int $book): BookResource
    {
        return new BookResource($this->bookService->find($book));
    }

    public function update(UpdateBookRequest $request, int $book): BookResource
    {
        $updated = $this->bookService->update(
            $book,
            BookData::fromArray($request->validated()),
        );

        return new BookResource($updated);
    }

    public function destroy(int $book): JsonResponse
    {
        $this->bookService->delete($book);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
