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
        Schema::create('exam_script_distributions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            
            $table->integer('exam_name_id')->nullable();
            $table->integer('exam_type_id')->nullable();
            $table->integer('exam_part_id')->nullable();
            
            $table->integer('shreny_id')->nullable();
            $table->integer('section_id')->nullable();
            $table->integer('subject_id')->nullable();
            $table->integer('teacher_id')->nullable();

            $table->date('allotted_date')->nullable();
            $table->date('submited_date')->nullable();
            
            $table->boolean('is_issued')->nullable();


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
        Schema::dropIfExists('exam_script_distributions');
    }
};
