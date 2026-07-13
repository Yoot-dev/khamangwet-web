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
        Schema::create('episodes', function (Blueprint $table) {
            $table->id(); $table->unsignedInteger('episode_number')->unique(); $table->string('title'); $table->string('slug')->unique(); $table->text('excerpt')->nullable(); $table->longText('content')->nullable(); $table->string('cover_image_path')->nullable(); $table->enum('status',['draft','scheduled','published'])->default('draft')->index(); $table->boolean('is_featured')->default(false); $table->timestamp('published_at')->nullable()->index(); $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete(); $table->timestamps(); $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('episodes');
    }
};
