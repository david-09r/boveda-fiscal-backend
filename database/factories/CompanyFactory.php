<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nit' => $this->faker->unique()->numberBetween(10000, 100000),
            'social_reason' => $this->faker->lexify('Company ?????'),
            'site_direction' => $this->faker->lexify('Direction ?????'),
            'code_number' => $this->faker->unique()->numberBetween(1, 100),
            'phone_number' => $this->faker->unique()->numberBetween(3000000000,3999999999),
            'email' => $this->faker->unique()->email,
            'website' => $this->faker->unique()->email,
            'user_id' => User::factory()
        ];
    }
}
