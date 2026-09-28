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
        Schema::create('photo_galleries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('category_id')->nullable();

            $table->string('category_name')->nullable();
            $table->string('category_slug')->nullable();
            $table->string('category_description')->nullable();

            $table->string('image_path')->nullable();
            $table->string('image_caption')->nullable();
            $table->string('image_alt_text')->nullable();

            $table->boolean('is_featured')->default(false);

            $table->integer('order_id')->nullable();
            $table->integer('school_id')->nullable();
            $table->integer('session_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('remarks')->nullable(); 
            $table->boolean('is_editable')->default(false);
            $table->boolean('is_deleted')->default(false);
            $table->boolean('is_finalized')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photo_galleries');
    }
};
