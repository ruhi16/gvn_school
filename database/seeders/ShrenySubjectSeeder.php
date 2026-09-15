<?php

namespace Database\Seeders;

use App\Models\Shreny;
use App\Models\ShrenySubject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShrenySubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shrenySubjectsMap = [
            1 => [1,2,3,10,11],  // Shreny ID 1 gets Subject IDs 10, 12, 15
            2 => [1,2,3,10,11],      // Shreny ID 2 gets Subject IDs 11, 14
            3 => [1,2,3,10,11],  // Shreny ID 3 gets Subject IDs 10, 13, 16
            4 => [1,2,3,4,9,10,11],  // Shreny ID 4 gets Subject IDs 12, 15
            5 => [1,2,3,4,9,10,11],  // Shreny ID 5 gets Subject IDs 11, 14
            6 => [1,2,3,4,6,7,8,9,10,11],  // Shreny ID 6 gets Subject IDs 10, 13, 16
            7 => [1,2,3,4,6,7,8,9,10,11],  // Shreny ID 7 gets Subject IDs 12, 15
        ];



        foreach ($shrenySubjectsMap as $shrenyId => $subjectIds) {
            foreach ($subjectIds as $subjectId) {
                ShrenySubject::create([
                    'shreny_id' => Shreny::find($shrenyId)->id,
                    'subject_id' => $subjectId,
                    'school_id' => 1,
                    'session_id' => 1,
                    'order_id' => 1,
                    'is_active' => true,
                    ]);
            }
        }
    }
}
