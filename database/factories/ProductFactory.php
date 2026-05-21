<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'brand' => $this->faker->randomElement(['Nike', 'Adidas', 'Reebok', 'Vans', 'Converse', 'Jordan', 'Puma', 'New Balance']),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 50, 300),
            'stock' => $this->faker->numberBetween(5, 50),
            'image' => 'sneakers/placeholder.jpg',
            'category' => $this->faker->randomElement(['running', 'lifestyle', 'basketball', 'skate']),
        ];
    }
}
