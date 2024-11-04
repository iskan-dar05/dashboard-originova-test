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
            //name, description, price, image_path, created_by, updated_by
            // 'name'=>fake()->sentence(),
            // 'description'=>fake()->realText(),
            // 'price'=>fake()->sentence(),
            // 'quantity'=>fake()->randomNumber(),
            // 'image_path'=>fake()->sentence(),
            
        ];
    }
}
