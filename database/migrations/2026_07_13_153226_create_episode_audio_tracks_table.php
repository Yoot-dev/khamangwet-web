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
        Schema::create('episode_audio_tracks', function (Blueprint $table) {
            $table->id(); $table->foreignId('episode_id')->constrained()->cascadeOnDelete(); $table->string('title'); $table->enum('track_type',['full_episode','narrator','character','segment'])->default('full_episode'); $table->string('storage_disk')->default('public'); $table->string('file_path'); $table->string('mime_type',80); $table->unsignedBigInteger('file_size'); $table->unsignedInteger('duration_ms')->nullable(); $table->unsignedInteger('version')->default(1); $table->boolean('is_active')->default(true); $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('episode_audio_tracks');
    }
};
