// database/migrations/xxxx_xx_xx_create_ideas_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ideas', function (Blueprint $table) {
            $table->id();
            $table->text('text');
            $table->string('file_path')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('submission_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            // Thêm index cho các trường thường query
            $table->index('status');
            $table->index(['user_id', 'submission_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('ideas');
    }
};