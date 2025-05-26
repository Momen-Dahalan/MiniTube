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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('channel_id')->constrained()->onDelete('cascade'); // قناة الفيديو
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('video_path'); // مسار الفيديو في storage أو S3
            $table->json('video_paths')->nullable();
            $table->string('thumbnail')->nullable(); // صورة مصغّرة
            $table->integer('views')->default(0);
            $table->boolean('is_processed')->default(false); // هل تم ضغط الفيديو؟
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
