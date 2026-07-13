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
        Schema::create('characters', function (Blueprint $table) {
            $table->id(); $table->string('name'); $table->string('english_name')->nullable(); $table->string('slug')->unique(); $table->string('role')->nullable(); $table->text('short_description')->nullable(); $table->longText('biography')->nullable(); $table->text('personality')->nullable(); $table->text('abilities')->nullable(); $table->string('affiliation')->nullable(); $table->string('image_path')->nullable(); $table->string('theme_color',20)->nullable(); $table->unsignedInteger('sort_order')->default(0); $table->boolean('is_published')->default(false); $table->timestamps(); $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('characters');
    }
};
