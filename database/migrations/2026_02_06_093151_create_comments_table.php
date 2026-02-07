<?php

use App\Enum\ActiveStatus;
use App\Enum\AnonymousEnum;
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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->longText('content')->nullable();
            $table->integer('status')->default(ActiveStatus::ACTIVE->value);
            $table->integer('is_anonymous')->default(AnonymousEnum::NOT_ANONYMOUS->value);
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('idea_id')->constrained('ideas')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
