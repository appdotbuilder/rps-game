<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\GameSession
 *
 * @property int $id
 * @property int $wins
 * @property int $losses
 * @property int $ties
 * @property string|null $user_choice
 * @property string|null $computer_choice
 * @property string|null $last_result
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|GameSession newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GameSession newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GameSession query()
 * @method static \Illuminate\Database\Eloquent\Builder|GameSession whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameSession whereWins($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameSession whereLosses($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameSession whereTies($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameSession whereUserChoice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameSession whereComputerChoice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameSession whereLastResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameSession whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameSession whereUpdatedAt($value)
 * @method static \Database\Factories\GameSessionFactory factory($count = null, $state = [])
 * @method static GameSession create(array $attributes = [])
 * @method static GameSession firstOrCreate(array $attributes = [], array $values = [])
 * 
 * @mixin \Eloquent
 */
class GameSession extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'wins',
        'losses',
        'ties',
        'user_choice',
        'computer_choice',
        'last_result',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'wins' => 'integer',
        'losses' => 'integer',
        'ties' => 'integer',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'game_sessions';
}