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
        Schema::create('exam_result_promotions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();

            $table->integer('exam_name_id')->nullable();
            $table->integer('exam_type_id')->nullable();
            $table->integer('exam_part_id')->nullable();
            
            $table->integer('shreny_id')->nullable();
            $table->integer('section_id')->nullable();

            $table->integer('student_cr_id')->nullable();
            $table->integer('obtained_total_marks')->nullable();
            $table->integer('obtained_total_no_of_failed_subjects')->nullable();
            $table->string('obtained_overall_grade')->nullable();

            $table->boolean('is_promoted')->default(false);
            $table->integer('promoted_to_shreny_id')->nullable();
            $table->integer('promoted_to_section_id')->nullable();
            $table->string('promoted_reasons')->nullable();

            $table->boolean('is_transferred')->nullable();
            $table->string('transferred_reasons')->nullable();
            $table->string('transferred_to_school_name')->nullable();

            $table->boolean('is_final')->default(false)->nullable();

            $table->boolean('is_repeating')->default(false)->nullable();

            $table->boolean('is_issued')->default(false);

            $table->integer('order_id')->nullable();
            $table->integer('school_id')->nullable();
            $table->integer('session_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('remarks')->nullable();
            $table->boolean('is_editable')->default(false);
            $table->boolean('is_deleted')->default(false);
            $table->boolean('is_finalized')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_result_promotions');
    }
};
