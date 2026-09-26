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
            $table->string('dp_img_ref')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->string('fname')->nullable();
            $table->string('mname')->nullable();
            $table->date('dob')->nullable();
            $table->string('dob_cert_img_ref')->nullable();
            $table->string('aadhaar_id')->nullable();
            $table->string('aadhaar_img_ref')->nullable();
            $table->string('pen_id')->nullable();
            $table->string('apper_id')->nullable();
            
            $table->string('village')->nullable();
            $table->string('post_office')->nullable();
            $table->string('police_station')->nullable();
            $table->string('district')->nullable();
            $table->string('block')->nullable();
            $table->string('pincode')->nullable();
            $table->string('state')->nullable()->default('West Bengal');
            $table->string('nationality')->nullable()->default('Indian');

            $table->string('mobile_1')->nullable();
            $table->string('mobile_2')->nullable();
            $table->string('email')->nullable();

            $table->integer('adm_shreny_id')->nullable();
            $table->integer('adm_section_id')->nullable();

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
        Schema::dropIfExists('student_dbs');
    }
};
