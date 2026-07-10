<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Book
 */
class BookResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'bookId' => $this->cod_livro,
            'title' => $this->titulo,
            'publisher' => $this->editora,
            'edition' => $this->edicao,
            'publicationYear' => $this->ano_publicacao,
            'price' => $this->valor,
            'authors' => AuthorResource::collection($this->whenLoaded('authors')),
            'subjects' => SubjectResource::collection($this->whenLoaded('subjects')),
        ];
    }
}
