<?php

use App\Enums\BoardPermission;
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
        Schema::create('user_board', function (Blueprint $table) {
            $table->timestamp('opened_at')->useCurrent()->useCurrentOnUpdate();
            $table->enum('permission', BoardPermission::nameCases())->default(BoardPermission::FULL_ACCESS->name);
            $table->foreignUlid('user_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('board_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('user_board', function (Blueprint $table) {
            $table->unique(['user_id', 'board_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_board');
    }
};
