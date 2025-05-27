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
            // $table->id();


            // $table->string('thumbnail')->nullable(); // صورة مصغّرة
            // $table->integer('views')->default(0);
            // $table->boolean('is_processed')->default(false); // هل تم ضغط الفيديو؟
            
            // $table->timestamps();

            $table->id();
            $table->foreignId('channel_id')->constrained()->onDelete('cascade'); // قناة الفيديو
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('video_path');
            $table->string('thumbnail');
            $table->string('hours')->nullable();
            $table->string('minutes')->nullable();
            $table->string('seconds')->nullable();
            $table->string('quality')->nullable();
            $table->boolean('processed')->default(false);
            $table->boolean('Longitudinal')->default(false);
            $table->integer('views')->default(0); // عدد المشاهدات
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
