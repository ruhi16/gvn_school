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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->enum('floor', ['Ground', 'First', 'Second', 'Third', 'Fourth', 'Other'])->nullable();
            
            $table->enum('room_type', ['Classroom', 'Laboratory', 'Library', 'Auditorium', 'Other'])->nullable();
            $table->enum('room_status', ['Available', 'Occupied', 'Under Maintenance', 'Closed'])->nullable();
            $table->enum('room_condition', ['Good', 'Needs Repair', 'Under Renovation'])->nullable();

            $table->integer('no_of_benches')->nullable();
            $table->integer('no_of_students_per_bench')->nullable();
            $table->integer('no_of_students_total')->nullable();



            $table->integer('school_id')->nullable();
            $table->integer('session_id')->nullable()->default(null);
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
        Schema::dropIfExists('rooms');
    }
};
