<?php

use App\Models\GameStats;
use Inertia\Testing\AssertableInertia as Assert;

it('displays the game on home page', function () {
    $response = $this->get('/');

    $response->assertOk();
    $response->assertInertia(
        fn (Assert $page) => $page
            ->component('welcome')
            ->has('stats')
            ->where('lastGame', null)
    );
});

it('can play game with valid choice', function () {
    $response = $this->post('/play', ['choice' => 'rock']);

    $response->assertOk();
    $response->assertInertia(
        fn (Assert $page) => $page
            ->component('welcome')
            ->has('stats')
            ->has('lastGame')
            ->where('lastGame.human_choice', 'rock')
            ->has('lastGame.computer_choice')
            ->has('lastGame.result')
            ->has('lastGame.message')
    );

    expect(GameStats::first()->total_games)->toBe(1);
});

it('cannot play game with invalid choice', function () {
    $response = $this->post('/play', ['choice' => 'invalid']);

    $response->assertRedirect();
    $response->assertSessionHasErrors(['choice']);
});

it('updates game stats correctly', function () {
    GameStats::create([
        'human_wins' => 5,
        'computer_wins' => 3,
        'ties' => 2,
        'total_games' => 10,
    ]);

    $response = $this->post('/play', ['choice' => 'rock']);

    $response->assertOk();

    $stats = GameStats::first();
    expect($stats->total_games)->toBe(11);
    
    // Check that one of the counters was incremented
    expect(
        $stats->human_wins === 6 || 
        $stats->computer_wins === 4 || 
        $stats->ties === 3
    )->toBeTrue();
});

it('can reset game stats', function () {
    GameStats::create([
        'human_wins' => 10,
        'computer_wins' => 8,
        'ties' => 5,
        'total_games' => 23,
    ]);

    $response = $this->delete('/reset');

    $response->assertOk();
    $response->assertInertia(
        fn (Assert $page) => $page
            ->component('welcome')
            ->where('stats.human_wins', 0)
            ->where('stats.computer_wins', 0)
            ->where('stats.ties', 0)
            ->where('stats.total_games', 0)
            ->where('lastGame', null)
    );

    $this->assertDatabaseHas('game_stats', [
        'human_wins' => 0,
        'computer_wins' => 0,
        'ties' => 0,
        'total_games' => 0,
    ]);
});

it('returns valid game result', function () {
    $response = $this->post('/play', ['choice' => 'rock']);
    
    $response->assertInertia(function (Assert $page) {
        $page->component('welcome')
            ->where('lastGame.human_choice', 'rock')
            ->has('lastGame.computer_choice')
            ->has('lastGame.result')
            ->has('lastGame.message');
            
        // Verify result is one of the valid options
        $result = $page->toArray()['props']['lastGame']['result'];
        expect($result)->toBeIn(['human', 'computer', 'tie']);
    });
});

it('requires choice to play', function () {
    $response = $this->post('/play', []);

    $response->assertRedirect();
    $response->assertSessionHasErrors(['choice']);
});

it('initializes stats if none exist', function () {
    expect(GameStats::count())->toBe(0);

    $response = $this->get('/');

    $response->assertOk();
    
    expect(GameStats::count())->toBe(1);
    expect(GameStats::first())
        ->human_wins->toBe(0)
        ->computer_wins->toBe(0)
        ->ties->toBe(0)
        ->total_games->toBe(0);
});