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
        Schema::create('exam_shreny_part_fm_pms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();

            $table->integer('shreny_id')->nullable();
            $table->integer('exam_name_id')->nullable();
            $table->integer('exam_type_id')->nullable();
            $table->integer('exam_part_id')->nullable();
            $table->integer('exam_mode_id')->nullable();

            $table->integer('subject_id')->nullable();
            $table->integer('full_marks')->nullable();
            $table->integer('pass_marks')->nullable();
            $table->integer('time_alloted')->nullable();

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
        Schema::dropIfExists('exam_shreny_part_fm_pms');
    }
};
