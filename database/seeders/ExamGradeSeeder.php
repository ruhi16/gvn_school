<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExamGradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $grades = [
            [
                'name' => 'A+',
                'description' => 'Excellent performance with outstanding distinction',
                'from_percentage' => 90,
                'to_percentage' => 100,
                'remarks' => 'Outstanding',
            ],
            [
                'name' => 'A',
                'description' => 'Excellent performance',
                'from_percentage' => 80,
                'to_percentage' => 89,
                'remarks' => 'Excellent',
            ],
            [
                'name' => 'B',
                'description' => 'Good performance',
                'from_percentage' => 70,
                'to_percentage' => 79,
                'remarks' => 'Very Good',
            ],
            [
                'name' => 'C',
                'description' => 'Satisfactory performance',
                'from_percentage' => 60,
                'to_percentage' => 69,
                'remarks' => 'Good',
            ],
            [
                'name' => 'D',
                'description' => 'Average performance',
                'from_percentage' => 50,
                'to_percentage' => 59,
                'remarks' => 'Average',
            ],
            [
                'name' => 'E',
                'description' => 'Marginal or conditional pass',
                'from_percentage' => 40,
                'to_percentage' => 49,
                'remarks' => 'Pass',
            ],
            [
                'name' => 'F',
                'description' => 'Unsatisfactory performance or failure',
                'from_percentage' => 0,
                'to_percentage' => 39,
                'remarks' => 'Fail',
            ],
        ];

        foreach ($grades as $index => $grade) {
            DB::table('exam_grades')->insert([
                'name' => $grade['name'],
                'description' => $grade['description'],
                'from_percentage' => $grade['from_percentage'],
                'to_percentage' => $grade['to_percentage'],
                'order_id' => $index + 1, // Automatically orders them 1 to 7
                'school_id' => 1,         // Replace with your dynamic or default school ID
                'session_id' => 1,        // Replace with your dynamic or default session ID
                'is_active' => true,
                'remarks' => $grade['remarks'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
