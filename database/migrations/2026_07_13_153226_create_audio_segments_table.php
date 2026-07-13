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
        Schema::create('audio_segments', function (Blueprint $table) {
            $table->id(); $table->unsignedBigInteger('episode_audio_track_id'); $table->unsignedBigInteger('character_id')->nullable(); $table->unsignedInteger('segment_order'); $table->text('text_content')->nullable(); $table->string('file_path')->nullable(); $table->unsignedInteger('start_ms')->nullable(); $table->unsignedInteger('end_ms')->nullable(); $table->string('voice_name')->nullable(); $table->string('voice_reference')->nullable(); $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audio_segments');
    }
};
