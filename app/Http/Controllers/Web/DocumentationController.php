<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DocumentationController extends Controller
{
    public function index(): View
    {
        return view('documentation.index');
    }

    public function spec(string $path = 'openapi.yaml'): BinaryFileResponse
    {
        $basePath = realpath(base_path('documentation'));

        if ($basePath === false) {
            throw new NotFoundHttpException('Documentation directory not found.');
        }

        $normalized = str_replace(['\\', "\0"], ['/', ''], $path);
        $normalized = ltrim($normalized, '/');

        if ($normalized === '' || str_contains($normalized, '..')) {
            throw new NotFoundHttpException('Invalid documentation path.');
        }

        $absolute = realpath($basePath.DIRECTORY_SEPARATOR.$normalized);

        if ($absolute === false || ! str_starts_with($absolute, $basePath) || ! is_file($absolute)) {
            throw new NotFoundHttpException('Documentation file not found.');
        }

        $extension = strtolower(pathinfo($absolute, PATHINFO_EXTENSION));
        $contentType = match ($extension) {
            'yaml', 'yml' => 'application/yaml',
            'json' => 'application/json',
            default => 'text/plain',
        };

        return response()->file($absolute, [
            'Content-Type' => $contentType,
            'Cache-Control' => 'no-cache, private',
        ]);
    }
}
