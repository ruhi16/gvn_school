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
        Schema::create('student_crs', function (Blueprint $table) {
            $table->id();
            $table->integer('studentdb_id')->nullable();
            $table->integer('shreny_id')->nullable();            
            $table->integer('section_id')->nullable();
            $table->integer('roll_no')->nullable();

            $table->integer('school_id')->nullable();
            $table->integer('session_id')->nullable()->default(null);

            $table->integer('order_id')->nullable();
            $table->boolean('is_promoted')->default(true);

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
        Schema::dropIfExists('student_crs');
    }
};
