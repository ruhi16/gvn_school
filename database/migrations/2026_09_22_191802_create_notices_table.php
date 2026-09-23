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
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('upload_dt')->nullable();
            $table->date('active_dt')->nullable();
            $table->date('expiry_dt')->nullable();
            $table->integer('uploaded_by')->nullable();
            $table->string('notice_img_ref')->nullable();
            $table->string('notice_pdf_ref')->nullable();

            $table->boolean('is_finalized')->nullable();
            $table->boolean('is_issued')->nullable();


            $table->integer('order_id')->nullable();
            $table->integer('school_id')->nullable();
            $table->integer('session_id')->nullable();
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
        Schema::dropIfExists('notices');
    }
};
