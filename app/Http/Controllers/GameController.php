<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlayGameRequest;
use App\Models\GameSession;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GameController extends Controller
{
    /**
     * Display the game.
     */
    public function index()
    {
        $gameSession = GameSession::firstOrCreate([], [
            'wins' => 0,
            'losses' => 0,
            'ties' => 0,
        ]);
        
        return Inertia::render('welcome', [
            'gameData' => [
                'wins' => $gameSession->wins,
                'losses' => $gameSession->losses,
                'ties' => $gameSession->ties,
                'userChoice' => $gameSession->user_choice,
                'computerChoice' => $gameSession->computer_choice,
                'lastResult' => $gameSession->last_result,
            ]
        ]);
    }
    
    /**
     * Play a round of the game.
     */
    public function store(PlayGameRequest $request)
    {
        $userChoice = $request->validated()['choice'];
        $choices = ['rock', 'paper', 'scissors'];
        $computerChoice = $choices[random_int(0, 2)];
        
        // Determine the winner
        $result = $this->determineWinner($userChoice, $computerChoice);
        
        // Get or create game session
        $gameSession = GameSession::firstOrCreate([], [
            'wins' => 0,
            'losses' => 0,
            'ties' => 0,
        ]);
        
        // Update score based on result
        if ($result === 'win') {
            $gameSession->increment('wins');
        } elseif ($result === 'lose') {
            $gameSession->increment('losses');
        } else {
            $gameSession->increment('ties');
        }
        
        // Update last round data
        $gameSession->update([
            'user_choice' => $userChoice,
            'computer_choice' => $computerChoice,
            'last_result' => $result,
        ]);
        
        return Inertia::render('welcome', [
            'gameData' => [
                'wins' => $gameSession->wins,
                'losses' => $gameSession->losses,
                'ties' => $gameSession->ties,
                'userChoice' => $gameSession->user_choice,
                'computerChoice' => $gameSession->computer_choice,
                'lastResult' => $gameSession->last_result,
            ]
        ]);
    }
    
    /**
     * Reset the game score.
     */
    public function destroy()
    {
        $gameSession = GameSession::first();
        if ($gameSession) {
            $gameSession->update([
                'wins' => 0,
                'losses' => 0,
                'ties' => 0,
                'user_choice' => null,
                'computer_choice' => null,
                'last_result' => null,
            ]);
        }
        
        return Inertia::render('welcome', [
            'gameData' => [
                'wins' => 0,
                'losses' => 0,
                'ties' => 0,
                'userChoice' => null,
                'computerChoice' => null,
                'lastResult' => null,
            ]
        ]);
    }
    
    /**
     * Determine the winner of the round.
     *
     * @param string $userChoice
     * @param string $computerChoice
     * @return string
     */
    protected function determineWinner(string $userChoice, string $computerChoice): string
    {
        if ($userChoice === $computerChoice) {
            return 'tie';
        }
        
        $winningCombinations = [
            'rock' => 'scissors',
            'paper' => 'rock',
            'scissors' => 'paper',
        ];
        
        return $winningCombinations[$userChoice] === $computerChoice ? 'win' : 'lose';
    }
}