<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class DuplicateAssociationException extends Exception
{
    public function __construct(string $message = 'Association already exists.')
    {
        parent::__construct($message);
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'message' => $this->getMessage(),
            'errors' => null,
            'code' => 'DUPLICATE_ASSOCIATION',
        ], 409);
    }
}
