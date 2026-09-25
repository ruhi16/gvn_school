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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('desccription')->nullable();

            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->string('dob')->nullable();
            $table->string('gender')->nullable();
            $table->string('prof_img_ref')->nullable();

            $table->enum('high_qual', ['Secondary', 'Higer Secondary', 'Bachelor', 'Master', 'PhD'])->nullable();
            $table->string('high_qual_subject')->nullable();
            $table->string('high_qual_certificate_img_ref')->nullable();

            $table->enum('prof_qual', ['BEd', 'Med', 'Ph Ed', 'Other'])->nullable();
            $table->string('prof_qual_subject')->nullable();
            $table->string('prof_qual_certificate_img_ref')->nullable();




            $table->string('vill')->nullable();
            $table->string('post_office')->nullable();
            $table->string('police_station')->nullable();
            $table->string('district')->nullable();
            $table->string('block')->nullable();
            $table->string('pincode')->nullable();
            $table->integer('order_id')->nullable();
            
            $table->integer('school_id')->nullable();
            $table->integer('session_id')->nullable()->default(null);
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
        Schema::dropIfExists('teachers');
    }
};
