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
        Schema::create('columns', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name', 24);
            $table->enum('color', \App\Enums\ColumnColor::values());
            $table->timestamps();
            $table->unsignedTinyInteger('order');
            $table->foreignUlid('board_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('columns');
    }
};
