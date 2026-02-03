<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ideas', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // tiêu đề ngắn
            $table->string('slug')->unique(); // slug duy nhất
            $table->text('content'); // nội dung chi tiết
            $table->string('file_path')->nullable(); // file đính kèm
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // trạng thái duyệt
            $table->boolean('is_anonymous')->default(false); // cờ ẩn danh
            $table->unsignedBigInteger('total_views')->default(0); // thống kê lượt xem
            $table->unsignedBigInteger('total_comments')->default(0); // thống kê bình luận
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // liên kết user
            $table->foreignId('category_id')->constrained()->onDelete('cascade'); // liên kết category
            $table->foreignId('submission_id')->constrained()->onDelete('cascade'); // liên kết submission
            $table->timestamps();

            // Index cho các trường thường query
            $table->index('status');
            $table->index(['user_id', 'submission_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ideas');
    }
};
