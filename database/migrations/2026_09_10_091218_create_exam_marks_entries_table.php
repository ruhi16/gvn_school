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
        Schema::create('exam_marks_entries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();

            $table->integer('shreny_id')->nullable();
            $table->integer('section_id')->nullable();

            $table->integer('exam_name_id')->nullable();
            $table->integer('exam_type_id')->nullable();
            $table->integer('exam_part_id')->nullable();

            $table->integer('subject_id')->nullable();
            $table->integer('obtained_marks')->nullable();

            $table->integer('order_id')->nullable();
            $table->integer('school_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_marks_entries');
    }
};
