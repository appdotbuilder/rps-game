<?php

namespace Tests\Feature;

use App\Models\GameSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GameTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_displays_game(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('welcome')
                ->has('gameData')
                ->where('gameData.wins', 0)
                ->where('gameData.losses', 0)
                ->where('gameData.ties', 0)
        );
    }

    public function test_can_play_game_with_valid_choice(): void
    {
        $response = $this->post('/play', ['choice' => 'rock']);

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('welcome')
                ->has('gameData')
                ->where('gameData.userChoice', 'rock')
        );

        $this->assertDatabaseHas('game_sessions', [
            'user_choice' => 'rock',
        ]);
        
        // Verify computer choice is valid
        $gameSession = GameSession::first();
        $this->assertContains($gameSession->computer_choice, ['rock', 'paper', 'scissors']);
        $this->assertContains($gameSession->last_result, ['win', 'lose', 'tie']);
    }

    public function test_cannot_play_game_with_invalid_choice(): void
    {
        $response = $this->post('/play', ['choice' => 'invalid']);

        $response->assertSessionHasErrors(['choice']);
    }

    public function test_game_logic_determines_winner_correctly(): void
    {
        // Test rock beats scissors
        $response = $this->post('/play', ['choice' => 'rock']);
        $response->assertStatus(200);
        
        $gameSession = GameSession::first();
        
        // Verify the result is consistent with the choices
        if ($gameSession->computer_choice === 'scissors') {
            $this->assertEquals('win', $gameSession->last_result);
        } elseif ($gameSession->computer_choice === 'paper') {
            $this->assertEquals('lose', $gameSession->last_result);
        } else {
            $this->assertEquals('tie', $gameSession->last_result);
        }
    }

    public function test_can_reset_game(): void
    {
        // First play a game
        $this->post('/play', ['choice' => 'rock']);
        
        // Reset the game
        $response = $this->delete('/reset');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('welcome')
                ->has('gameData')
                ->where('gameData.wins', 0)
                ->where('gameData.losses', 0)
                ->where('gameData.ties', 0)
                ->where('gameData.userChoice', null)
                ->where('gameData.computerChoice', null)
                ->where('gameData.lastResult', null)
        );
    }

    public function test_score_updates_correctly(): void
    {
        // Play multiple rounds and verify score tracking
        $this->post('/play', ['choice' => 'rock']);
        $this->post('/play', ['choice' => 'paper']);
        $this->post('/play', ['choice' => 'scissors']);

        $gameSession = GameSession::first();
        
        // Verify total rounds played equals sum of wins, losses, and ties
        $totalRounds = $gameSession->wins + $gameSession->losses + $gameSession->ties;
        $this->assertEquals(3, $totalRounds);
    }

    public function test_game_session_is_created_if_not_exists(): void
    {
        $this->assertDatabaseEmpty('game_sessions');

        $response = $this->get('/');

        $response->assertStatus(200);
        $this->assertDatabaseHas('game_sessions', [
            'wins' => 0,
            'losses' => 0,
            'ties' => 0,
        ]);
    }

    public function test_winning_combinations(): void
    {
        // Test that the game logic is implemented correctly
        $gameController = new \App\Http\Controllers\GameController();
        
        // Use reflection to test the protected method
        $reflectionClass = new \ReflectionClass($gameController);
        $method = $reflectionClass->getMethod('determineWinner');
        $method->setAccessible(true);
        
        // Test all winning combinations
        $this->assertEquals('win', $method->invoke($gameController, 'rock', 'scissors'));
        $this->assertEquals('win', $method->invoke($gameController, 'paper', 'rock'));
        $this->assertEquals('win', $method->invoke($gameController, 'scissors', 'paper'));
        
        // Test ties
        $this->assertEquals('tie', $method->invoke($gameController, 'rock', 'rock'));
        $this->assertEquals('tie', $method->invoke($gameController, 'paper', 'paper'));
        $this->assertEquals('tie', $method->invoke($gameController, 'scissors', 'scissors'));
        
        // Test losses
        $this->assertEquals('lose', $method->invoke($gameController, 'rock', 'paper'));
        $this->assertEquals('lose', $method->invoke($gameController, 'paper', 'scissors'));
        $this->assertEquals('lose', $method->invoke($gameController, 'scissors', 'rock'));
    }
}