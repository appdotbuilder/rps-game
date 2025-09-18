<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('game_stats', function (Blueprint $table) {
            $table->id();
            $table->integer('human_wins')->default(0)->comment('Number of wins for human player');
            $table->integer('computer_wins')->default(0)->comment('Number of wins for computer player');
            $table->integer('ties')->default(0)->comment('Number of tied games');
            $table->integer('total_games')->default(0)->comment('Total number of games played');
            $table->timestamps();
            
            // Add indexes for performance
            $table->index('created_at');
            $table->index('total_games');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_stats');
    }
};