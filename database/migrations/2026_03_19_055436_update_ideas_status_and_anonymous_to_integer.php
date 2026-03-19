<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enum\IdeaStatus;
use App\Enum\AnonymousEnum;

class UpdateIdeasStatusAndAnonymousToInteger extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ideas', function (Blueprint $table) {
            $table->integer('status')->default(IdeaStatus::PENDING->value)->change();
            $table->integer('is_anonymous')->default(AnonymousEnum::NOT_ANONYMOUS->value)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ideas', function (Blueprint $table) {
           $table->string('status')->default(IdeaStatus::PENDING->value)->change();
            $table->string('is_anonymous')->default(AnonymousEnum::NOT_ANONYMOUS->value)->change();
        });
    }
}
