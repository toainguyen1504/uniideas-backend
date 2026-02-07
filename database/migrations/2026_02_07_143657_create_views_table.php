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
        Schema::create('views', function (Blueprint $table) {
            $table->id();
            $table->dateTime('visit_time');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('idea_id')->constrained('ideas')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'idea_id'], 'unique_user_idea_view');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('views');
    }
};
