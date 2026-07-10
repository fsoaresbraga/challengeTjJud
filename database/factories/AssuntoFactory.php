<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Assunto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Assunto>
 */
class AssuntoFactory extends Factory
{
    protected $model = Assunto::class;

    public function definition(): array
    {
        return [
            'descricao' => fake()->unique()->words(2, true),
        ];
    }
}
