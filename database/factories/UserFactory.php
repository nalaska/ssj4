<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'picture' => $this->faker->imageUrl(640, 480, 'people', true, 'Faker'), // Ajout de la photo
            'date_of_birth' => $this->faker->date(),
            'belt' => $this->faker->randomElement(['blanche', 'grise', 'jaune', 'orange', 'verte', 'bleu', 'violette', 'marron', 'noire']),
            'phone' => $this->faker->phoneNumber,
            'year_of_registration' => $this->faker->year,
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
