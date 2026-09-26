<?php

namespace Database\Seeders;

use App\Models\Shreny;
use App\Models\ShrenySubject;
use App\Models\School;
use App\Models\Session;
use App\Models\Subject;
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
            'Baby Land' => ['Bengali', 'English', 'Mathematics', 'Physical Education', 'Art & Work Education'],
            'LKG' => ['Bengali', 'English', 'Mathematics', 'Physical Education', 'Art & Work Education'],
            'UKG' => ['Bengali', 'English', 'Mathematics', 'Physical Education', 'Art & Work Education'],
            'Class 1' => ['Bengali', 'English', 'Mathematics', 'General Knowledge', 'Computer Science', 'Physical Education', 'Art & Work Education'],
            'Class 2' => ['Bengali', 'English', 'Mathematics', 'General Knowledge', 'Computer Science', 'Physical Education', 'Art & Work Education'],
            'Class 3' => ['Bengali', 'English', 'Mathematics', 'General Knowledge', 'Environmental Science', 'History & Civics', 'Geography & Culture', 'Computer Science', 'Physical Education', 'Art & Work Education'],
            'Class 4' => ['Bengali', 'English', 'Mathematics', 'General Knowledge', 'Environmental Science', 'History & Civics', 'Geography & Culture', 'Computer Science', 'Physical Education', 'Art & Work Education'],
        ];

        $schoolId = School::query()->where('name', 'Green Vally Nursery School')->value('id');
        $sessionId = Session::query()->where('school_id', $schoolId)->where('name', '2026')->value('id');

        foreach ($shrenySubjectsMap as $shrenyName => $subjectNames) {
            $shreny = Shreny::query()
                ->where('name', $shrenyName)
                ->where('school_id', $schoolId)
                ->where('session_id', $sessionId)
                ->first();

            if (!$shreny) {
                throw new \RuntimeException("Shreny {$shrenyName} must be seeded before its subjects.");
            }

            foreach ($subjectNames as $index => $subjectName) {
                $subjectId = Subject::query()
                    ->where('name', $subjectName)
                    ->where('school_id', $schoolId)
                    ->where('session_id', $sessionId)
                    ->value('id');

                if (!$subjectId) {
                    throw new \RuntimeException("Subject {$subjectName} must be seeded before Shreny subjects.");
                }

                ShrenySubject::query()->updateOrCreate([
                    'shreny_id' => $shreny->id,
                    'subject_id' => $subjectId,
                    'school_id' => $schoolId,
                    'session_id' => $sessionId,
                ], [
                    'order_id' => $index + 1,
                    'is_active' => true,
                ]);
            }
        }
    }
}
