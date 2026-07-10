<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface ReportRepositoryInterface
{
    /**
     * @return Collection<int, object>
     */
    public function booksByAuthor(): Collection;
}
