<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Secret>
 */
class SecretFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'hash' => bin2hex(random_bytes(16)),
            'secretText' => fake()->sentence(),
            'expiresAt' => Carbon::now('Europe/Budapest')->addMinutes(rand(1,1000))->format('Y-m-d H:i:s.v'),
            'remainingViews' => rand(1, 10),
        ];
    }
}
