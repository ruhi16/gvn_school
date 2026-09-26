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
            $table->integer('curr_shreny_id')->nullable();            
            $table->integer('curr_section_id')->nullable();
            $table->integer('curr_roll_no')->nullable();

            $table->integer('school_id')->nullable();
            $table->integer('session_id')->nullable()->default(null);

            $table->integer('order_id')->nullable();
            $table->boolean('is_promoted')->default(true);

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
        Schema::dropIfExists('student_crs');
    }
};
