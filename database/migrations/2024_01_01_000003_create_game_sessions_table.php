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
        Schema::create('game_sessions', function (Blueprint $table) {
            $table->id();
            $table->integer('wins')->default(0)->comment('Number of user wins');
            $table->integer('losses')->default(0)->comment('Number of user losses');
            $table->integer('ties')->default(0)->comment('Number of ties');
            $table->string('user_choice')->nullable()->comment('User last choice');
            $table->string('computer_choice')->nullable()->comment('Computer last choice');
            $table->string('last_result')->nullable()->comment('Last game result');
            $table->timestamps();
            
            // Indexes for performance
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_sessions');
    }
};