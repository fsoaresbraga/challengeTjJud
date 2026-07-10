<?php

declare(strict_types=1);

use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\SubjectController;
use Illuminate\Support\Facades\Route;

Route::apiResource('books', BookController::class);
Route::apiResource('authors', AuthorController::class);
Route::apiResource('subjects', SubjectController::class);

Route::get('reports/books-by-author', [ReportController::class, 'booksByAuthor']);
