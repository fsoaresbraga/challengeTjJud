<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class AuthorNotFoundException extends Exception
{
    public function __construct(int $id)
    {
        parent::__construct("Author with id {$id} was not found.");
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'message' => $this->getMessage(),
            'errors' => null,
            'code' => 'AUTHOR_NOT_FOUND',
        ], 404);
    }
}
