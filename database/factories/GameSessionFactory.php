<?php

namespace Database\Factories;

use App\Models\GameSession;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GameSession>
 */
class GameSessionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\GameSession>
     */
    protected $model = GameSession::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $choices = ['rock', 'paper', 'scissors'];
        $results = ['win', 'lose', 'tie'];

        return [
            'wins' => $this->faker->numberBetween(0, 10),
            'losses' => $this->faker->numberBetween(0, 10),
            'ties' => $this->faker->numberBetween(0, 10),
            'user_choice' => $this->faker->randomElement($choices),
            'computer_choice' => $this->faker->randomElement($choices),
            'last_result' => $this->faker->randomElement($results),
        ];
    }

    /**
     * Indicate that the game session has no previous rounds.
     *
     * @return static
     */
    public function fresh(): static
    {
        return $this->state(fn (array $attributes) => [
            'wins' => 0,
            'losses' => 0,
            'ties' => 0,
            'user_choice' => null,
            'computer_choice' => null,
            'last_result' => null,
        ]);
    }
}