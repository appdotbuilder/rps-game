<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GameStats>
 */
class GameStatsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $humanWins = fake()->numberBetween(0, 50);
        $computerWins = fake()->numberBetween(0, 50);
        $ties = fake()->numberBetween(0, 20);
        $totalGames = $humanWins + $computerWins + $ties;

        return [
            'human_wins' => $humanWins,
            'computer_wins' => $computerWins,
            'ties' => $ties,
            'total_games' => $totalGames,
        ];
    }

    /**
     * Indicate that the game stats represent a new game session.
     */
    public function newSession(): static
    {
        return $this->state(fn (array $attributes) => [
            'human_wins' => 0,
            'computer_wins' => 0,
            'ties' => 0,
            'total_games' => 0,
        ]);
    }
}