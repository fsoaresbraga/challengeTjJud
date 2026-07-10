<?php

declare(strict_types=1);

namespace App\DTOs;

final readonly class SubjectData
{
    public function __construct(
        public string $description,
    ) {
    }

    /**
     * @param  array<string, mixed>  $validated
     */
    public static function fromArray(array $validated): self
    {
        return new self(
            description: (string) $validated['description'],
        );
    }

    /**
     * @return array{descricao: string}
     */
    public function toAttributes(): array
    {
        return [
            'descricao' => $this->description,
        ];
    }
}
