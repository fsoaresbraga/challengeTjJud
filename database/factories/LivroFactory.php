<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Livro;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Livro>
 */
class LivroFactory extends Factory
{
    protected $model = Livro::class;

    public function definition(): array
    {
        return [
            'titulo' => fake()->unique()->sentence(3),
            'editora' => fake()->company(),
            'edicao' => fake()->numberBetween(1, 10),
            'ano_publicacao' => fake()->numberBetween(1980, (int) date('Y')),
            'valor' => fake()->randomFloat(2, 20, 250),
        ];
    }
}
