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
        Schema::create('student_dbs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('fname')->nullable();
            $table->string('mname')->nullable();
            $table->date('dob')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('aadhaar_id')->nullable();
            $table->string('pen_id')->nullable();
            $table->string('apper_id')->nullable();

            $table->string('village')->nullable();
            $table->string('post_office')->nullable();
            $table->string('police_station')->nullable();
            $table->string('district')->nullable();
            $table->string('block')->nullable();
            $table->string('pincode')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_dbs');
    }
};
