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
        Schema::create('teacher_subjects', function (Blueprint $table) {
            $table->id();
            $table->integer('teacher_id')->nullable();
            $table->integer('subject_id')->nullable();
            $table->integer('order_id')->nullable();
            $table->enum('subject_type', ['main_subject', 'secondary_subject', 'additional_subject', 'other'])->nullable();
            
            $table->integer('session_id')->nullable()->default(null);
            $table->integer('school_id')->nullable();
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
        Schema::dropIfExists('teacher_subjects');
    }
};
