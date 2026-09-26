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
        Schema::create('exam_rooms', function (Blueprint $table) {
            $table->id();
            $table->integer('exam_name_id')->nullable();
            $table->integer('exam_type_id')->nullable();
            $table->integer('exam_part_id')->nullable();

            $table->integer('shreny_id')->nullable();
            $table->integer('section_id')->nullable();

            $table->integer('room_id')->nullable();
            $table->integer('no_of_students_per_bench')->nullable();

            $table->integer('roll_no_range_start')->nullable();
            $table->integer('roll_no_range_end')->nullable();

            $table->integer('order_id')->nullable();
            $table->integer('school_id')->nullable();
            $table->integer('session_id')->nullable()->default(null);
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
        Schema::dropIfExists('exam_rooms');
    }
};
