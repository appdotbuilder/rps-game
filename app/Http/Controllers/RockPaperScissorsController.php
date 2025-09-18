<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlayGameRequest;
use App\Models\GameStats;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RockPaperScissorsController extends Controller
{
    /**
     * Display the game page.
     */
    public function index()
    {
        $stats = GameStats::firstOrCreate([], [
            'human_wins' => 0,
            'computer_wins' => 0,
            'ties' => 0,
            'total_games' => 0,
        ]);
        
        return Inertia::render('welcome', [
            'stats' => $stats,
            'lastGame' => null,
        ]);
    }
    
    /**
     * Play a round of Rock Paper Scissors.
     */
    public function store(PlayGameRequest $request)
    {
        $humanChoice = $request->validated()['choice'];
        $computerChoice = $this->getComputerChoice();
        $result = $this->determineWinner($humanChoice, $computerChoice);
        
        // Update statistics
        $stats = GameStats::firstOrCreate([], [
            'human_wins' => 0,
            'computer_wins' => 0,
            'ties' => 0,
            'total_games' => 0,
        ]);
        
        $stats->total_games++;
        
        switch ($result) {
            case 'human':
                $stats->human_wins++;
                break;
            case 'computer':
                $stats->computer_wins++;
                break;
            case 'tie':
                $stats->ties++;
                break;
        }
        
        $stats->save();
        
        $lastGame = [
            'human_choice' => $humanChoice,
            'computer_choice' => $computerChoice,
            'result' => $result,
            'message' => $this->getResultMessage($result, $humanChoice, $computerChoice),
        ];
        
        return Inertia::render('welcome', [
            'stats' => $stats,
            'lastGame' => $lastGame,
        ]);
    }
    
    /**
     * Reset the game statistics.
     */
    public function destroy()
    {
        $stats = GameStats::firstOrCreate([], [
            'human_wins' => 0,
            'computer_wins' => 0,
            'ties' => 0,
            'total_games' => 0,
        ]);
        
        $stats->update([
            'human_wins' => 0,
            'computer_wins' => 0,
            'ties' => 0,
            'total_games' => 0,
        ]);
        
        return Inertia::render('welcome', [
            'stats' => $stats,
            'lastGame' => null,
        ]);
    }
    
    /**
     * Get computer's choice randomly.
     */
    protected function getComputerChoice(): string
    {
        $choices = ['rock', 'paper', 'scissors'];
        return $choices[random_int(0, 2)];
    }
    
    /**
     * Determine the winner of the game.
     */
    protected function determineWinner(string $humanChoice, string $computerChoice): string
    {
        if ($humanChoice === $computerChoice) {
            return 'tie';
        }
        
        $winConditions = [
            'rock' => 'scissors',
            'paper' => 'rock',
            'scissors' => 'paper',
        ];
        
        return $winConditions[$humanChoice] === $computerChoice ? 'human' : 'computer';
    }
    
    /**
     * Get the result message for the game.
     */
    protected function getResultMessage(string $result, string $humanChoice, string $computerChoice): string
    {
        $humanEmoji = $this->getChoiceEmoji($humanChoice);
        $computerEmoji = $this->getChoiceEmoji($computerChoice);
        
        switch ($result) {
            case 'human':
                return "🎉 You win! {$humanEmoji} beats {$computerEmoji}";
            case 'computer':
                return "😔 Computer wins! {$computerEmoji} beats {$humanEmoji}";
            case 'tie':
                return "🤝 It's a tie! Both chose {$humanEmoji}";
            default:
                return "Something went wrong!";
        }
    }
    
    /**
     * Get emoji representation of choice.
     */
    protected function getChoiceEmoji(string $choice): string
    {
        $emojis = [
            'rock' => '🪨',
            'paper' => '📄',
            'scissors' => '✂️',
        ];
        
        return $emojis[$choice] ?? '❓';
    }
}