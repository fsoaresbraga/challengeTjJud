<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookReportResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'authorId' => $this->authorId,
            'authorName' => $this->authorName,
            'bookId' => $this->bookId,
            'title' => $this->title,
            'publisher' => $this->publisher,
            'edition' => $this->edition,
            'publicationYear' => $this->publicationYear,
            'price' => $this->price,
            'subjects' => $this->subjects,
        ];
    }
}
