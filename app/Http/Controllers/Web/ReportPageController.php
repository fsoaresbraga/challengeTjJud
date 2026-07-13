<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ReportPageController extends Controller
{
    public function booksByAuthor(): View
    {
        return view('reports.books-by-author');
    }
}
