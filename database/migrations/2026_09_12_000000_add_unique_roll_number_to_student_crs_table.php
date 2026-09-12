<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_crs', function (Blueprint $table): void {
            $table->unique(
                ['curr_shreny_id', 'curr_section_id', 'curr_roll_no'],
                'student_crs_shreny_section_roll_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('student_crs', function (Blueprint $table): void {
            $table->dropUnique('student_crs_shreny_section_roll_unique');
        });
    }
};