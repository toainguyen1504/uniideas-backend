<?php

use App\Enum\ActiveStatus;
use App\Enum\ReactEnum;
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
        Schema::create('reacts', function (Blueprint $table) {
            $table->id();
            $table->integer('react')->default(ReactEnum::UNKNOWN->value);
            $table->integer('status')->default(ActiveStatus::ACTIVE->value);
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('idea_id')->constrained('ideas')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'idea_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reacts');
    }
};
