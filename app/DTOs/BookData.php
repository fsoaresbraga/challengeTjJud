<?php

declare(strict_types=1);

namespace App\DTOs;

final readonly class BookData
{
    /**
     * @param  list<int>  $authorIds
     * @param  list<int>  $subjectIds
     */
    public function __construct(
        public string $title,
        public string $publisher,
        public int $edition,
        public int $publicationYear,
        public string $price,
        public array $authorIds,
        public array $subjectIds,
    ) {
    }

    /**
     * @param  array<string, mixed>  $validated
     */
    public static function fromArray(array $validated): self
    {
        /** @var list<int> $authorIds */
        $authorIds = array_map('intval', $validated['authors'] ?? []);
        /** @var list<int> $subjectIds */
        $subjectIds = array_map('intval', $validated['subjects'] ?? []);

        return new self(
            title: (string) $validated['title'],
            publisher: (string) $validated['publisher'],
            edition: (int) $validated['edition'],
            publicationYear: (int) $validated['publicationYear'],
            price: number_format((float) $validated['price'], 2, '.', ''),
            authorIds: $authorIds,
            subjectIds: $subjectIds,
        );
    }

    /**
     * @return array{titulo: string, editora: string, edicao: int, ano_publicacao: int, valor: string}
     */
    public function toAttributes(): array
    {
        return [
            'titulo' => $this->title,
            'editora' => $this->publisher,
            'edicao' => $this->edition,
            'ano_publicacao' => $this->publicationYear,
            'valor' => $this->price,
        ];
    }
}
