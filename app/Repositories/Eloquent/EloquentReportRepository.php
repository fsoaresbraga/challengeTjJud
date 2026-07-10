<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\BookReportByAuthor;
use App\Repositories\Contracts\ReportRepositoryInterface;
use Illuminate\Support\Collection;

class EloquentReportRepository implements ReportRepositoryInterface
{
    public function booksByAuthor(): Collection
    {
        return BookReportByAuthor::query()
            ->orderBy('authorName')
            ->orderBy('title')
            ->get();
    }
}
