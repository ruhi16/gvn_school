<?php

use Database\Seeders\DatabaseSeeder;
use Database\Seeders\ExamGradeSeeder;
use Illuminate\Support\Facades\DB;

it('seeds the demo database idempotently', function () {
    $this->seed(DatabaseSeeder::class);
    $this->seed(ExamGradeSeeder::class);

    $tableCounts = [
        'users' => DB::table('users')->count(),
        'schools' => DB::table('schools')->count(),
        'sessions' => DB::table('sessions')->count(),
        'shrenies' => DB::table('shrenies')->count(),
        'sections' => DB::table('sections')->count(),
        'subjects' => DB::table('subjects')->count(),
        'shreny_sections' => DB::table('shreny_sections')->count(),
        'student_dbs' => DB::table('student_dbs')->count(),
        'teachers' => DB::table('teachers')->count(),
        'shreny_subjects' => DB::table('shreny_subjects')->count(),
        'exam_names' => DB::table('exam_names')->count(),
        'exam_types' => DB::table('exam_types')->count(),
        'exam_parts' => DB::table('exam_parts')->count(),
        'exam_modes' => DB::table('exam_modes')->count(),
        'exam_grades' => DB::table('exam_grades')->count(),
    ];
    expect($tableCounts)->toBe([
        'users' => 3,
        'schools' => 1,
        'sessions' => 1,
        'shrenies' => 7,
        'sections' => 3,
        'subjects' => 11,
        'shreny_sections' => 7,
        'student_dbs' => 20,
        'teachers' => 20,
        'shreny_subjects' => 49,
        'exam_names' => 4,
        'exam_types' => 2,
        'exam_parts' => 2,
        'exam_modes' => 5,
        'exam_grades' => 9,
    ]);

    $this->seed(DatabaseSeeder::class);
    $this->seed(ExamGradeSeeder::class);

    foreach ($tableCounts as $table => $count) {
        expect(DB::table($table)->count())->toBe($count);
    }
});