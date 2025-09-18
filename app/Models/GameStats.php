<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\GameStats
 *
 * @property int $id
 * @property int $human_wins
 * @property int $computer_wins
 * @property int $ties
 * @property int $total_games
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|GameStats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GameStats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GameStats query()
 * @method static \Illuminate\Database\Eloquent\Builder|GameStats whereComputerWins($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameStats whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameStats whereHumanWins($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameStats whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameStats whereTies($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameStats whereTotalGames($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameStats whereUpdatedAt($value)
 * @method static \Database\Factories\GameStatsFactory factory($count = null, $state = [])
 * @method static GameStats create(array $attributes = [])
 * @method static GameStats firstOrCreate(array $attributes = [], array $values = [])
 * 
 * @mixin \Eloquent
 */
class GameStats extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'human_wins',
        'computer_wins',
        'ties',
        'total_games',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'human_wins' => 'integer',
        'computer_wins' => 'integer',
        'ties' => 'integer',
        'total_games' => 'integer',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'game_stats';
}