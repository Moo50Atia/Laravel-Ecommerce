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
        Schema::create('blog_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_id')->constrained()->onDelete('cascade'); // كل تقييم مرتبط بمقال
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // المقيِّم ممكن يكون زائر
            $table->unsignedTinyInteger('rate'); // رقم من 1 لـ 5 مثلاً
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_reviews');
    }
};
