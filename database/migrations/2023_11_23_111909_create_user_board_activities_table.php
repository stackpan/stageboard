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
        Schema::create('user_board_activities', function (Blueprint $table) {
            $table->foreignUlid('user_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignUlid('board_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamp('opened_at');
            $table->unique(['user_id', 'board_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_board_activities');
    }
};
