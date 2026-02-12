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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title_primary');
            $table->string('title_secondary');
            $table->string('title_suffix')->nullable();
            $table->text('description');
            $table->string('image_path');
            $table->json('features')->nullable(); // [{"title":"...","description":"..."}]
            $table->string('cta_text')->default('Reserve Your Spot');
            $table->string('cta_link')->nullable();
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->boolean('show_on_homepage')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
