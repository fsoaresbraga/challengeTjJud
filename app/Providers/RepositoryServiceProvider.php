<?php

declare(strict_types=1);

namespace App\Providers;

use App\Repositories\Contracts\AuthorRepositoryInterface;
use App\Repositories\Contracts\BookRepositoryInterface;
use App\Repositories\Contracts\ReportRepositoryInterface;
use App\Repositories\Contracts\SubjectRepositoryInterface;
use App\Repositories\Eloquent\EloquentAuthorRepository;
use App\Repositories\Eloquent\EloquentBookRepository;
use App\Repositories\Eloquent\EloquentReportRepository;
use App\Repositories\Eloquent\EloquentSubjectRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(BookRepositoryInterface::class, EloquentBookRepository::class);
        $this->app->bind(AuthorRepositoryInterface::class, EloquentAuthorRepository::class);
        $this->app->bind(SubjectRepositoryInterface::class, EloquentSubjectRepository::class);
        $this->app->bind(ReportRepositoryInterface::class, EloquentReportRepository::class);
    }
}
