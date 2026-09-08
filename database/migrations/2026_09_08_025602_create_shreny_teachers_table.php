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
        Schema::create('shreny_teachers', function (Blueprint $table) {
            $table->id();
            $table->integer('shreny_id')->nullable();
            $table->integer('teacher_id')->nullable();
            $table->integer('order_id')->nullable();
            $table->integer('school_id')->nullable();
            $table->integer('session_id')->nullable()->default(null);
            $table->enum('teacher_type', ['class_teacher', 'subject_teacher', 'other'])->nullable();

            $table->boolean('is_active')->default(true);
            $table->string('remarks')->nullable();
            // $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shreny_teachers');
    }
};
