<?php

declare(strict_types=1);

use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\SubjectController;
use Illuminate\Support\Facades\Route;

Route::apiResource('books', BookController::class)->names('api.books');
Route::apiResource('authors', AuthorController::class)->names('api.authors');
Route::apiResource('subjects', SubjectController::class)->names('api.subjects');

Route::get('reports/books-by-author/pdf', [ReportController::class, 'booksByAuthorPdf'])
    ->name('api.reports.books-by-author.pdf');
