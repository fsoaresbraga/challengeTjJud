<?php

declare(strict_types=1);

use App\Http\Controllers\Web\AuthorPageController;
use App\Http\Controllers\Web\BookPageController;
use App\Http\Controllers\Web\DocumentationController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\ReportPageController;
use App\Http\Controllers\Web\SubjectPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/books', [BookPageController::class, 'index'])->name('books.index');
Route::get('/authors', [AuthorPageController::class, 'index'])->name('authors.index');
Route::get('/subjects', [SubjectPageController::class, 'index'])->name('subjects.index');
Route::get('/reports/books-by-author', [ReportPageController::class, 'booksByAuthor'])
    ->name('reports.books-by-author');

Route::get('/documentation', [DocumentationController::class, 'index'])
    ->name('documentation.index');
Route::get('/documentation/spec/{path}', [DocumentationController::class, 'spec'])
    ->where('path', '.*')
    ->name('documentation.spec');
