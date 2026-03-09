<?php

use App\Enum\AnonymousEnum;
use App\Enum\IdeaStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ideas', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->longText('content')->nullable();
            $table->string('status')->default(IdeaStatus::PENDING->value);
            $table->string('is_anonymous')->default(AnonymousEnum::NOT_ANONYMOUS->value);
            $table->integer('total_views')->default(0);
            $table->integer('total_comments')->default(0);
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignId('submission_id')->constrained('submissions')->cascadeOnDelete();
            $table->timestamps();

           
            $table->index('status');
            $table->index(['user_id', 'submission_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ideas');
    }
};
