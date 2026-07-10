<?php

declare(strict_types=1);

namespace App\DTOs;

final readonly class AuthorData
{
    public function __construct(
        public string $name,
    ) {
    }

    /**
     * @param  array<string, mixed>  $validated
     */
    public static function fromArray(array $validated): self
    {
        return new self(
            name: (string) $validated['name'],
        );
    }

    /**
     * @return array{nome: string}
     */
    public function toAttributes(): array
    {
        return [
            'nome' => $this->name,
        ];
    }
}
