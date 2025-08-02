<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::query()->inRandomOrder()->value('id'), // 既存のIDを必ず選ぶ
            'first_name'  => fake()->firstName(),
            'last_name'   => fake()->lastName(),
            'gender'      => fake()->randomElement([1, 2, 3]),
            'email'       => fake()->unique()->safeEmail(),
            'tel'         => preg_replace('/\D/', '', fake()->numerify('0##########')),
            'address'     => fake()->address(),
            'building'    => fake()->optional()->secondaryAddress(),
            'detail'      => mb_substr(fake()->realText(100), 0, 120),
        ];
    }
}