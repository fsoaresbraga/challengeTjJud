<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\Contracts\ReportRepositoryInterface;
use Illuminate\Support\Collection;

class ReportService
{
    public function __construct(
        private readonly ReportRepositoryInterface $reportRepository,
    ) {
    }

    /**
     * @return Collection<int, object>
     */
    public function booksByAuthor(): Collection
    {
        return $this->reportRepository->booksByAuthor();
    }

    /**
     * @return Collection<string, Collection<int, object>>
     */
    public function booksGroupedByAuthor(): Collection
    {
        return $this->booksByAuthor()->groupBy('authorName');
    }
}
