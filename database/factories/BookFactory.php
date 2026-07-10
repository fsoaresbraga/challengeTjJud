<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Book>
 */
class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition(): array
    {
        return [
            'titulo' => fake()->unique()->sentence(3),
            'editora' => substr(fake()->company(), 0, 40),
            'edicao' => fake()->numberBetween(1, 10),
            'ano_publicacao' => fake()->numberBetween(1980, (int) date('Y')),
            'valor' => fake()->randomFloat(2, 20, 250),
        ];
    }
}
