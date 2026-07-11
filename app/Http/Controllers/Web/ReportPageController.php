<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\View\View;

class ReportPageController extends Controller
{
    public function __construct(
        private readonly ReportService $reportService,
    ) {
    }

    public function booksByAuthor(): View
    {
        $grouped = $this->reportService->booksGroupedByAuthor();

        return view('reports.books-by-author', [
            'grouped' => $grouped,
        ]);
    }
}
