<?php

namespace Database\Seeders;

use App\Models\ExamGrade;
use App\Models\ExamMode;
use App\Models\ExamName;
use App\Models\ExamPart;
use App\Models\ExamType;
use App\Models\School;
use App\Models\Section;
use App\Models\Session;
use App\Models\Shreny;
use App\Models\ShrenySection;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $upsert = static function (string $modelClass, array $identity, array $values) {
                $record = $modelClass::query()->firstOrNew($identity);
                $record->forceFill(array_merge($identity, $values));
                $record->save();

                return $record;
            };

            $school = $upsert(School::class, ['name' => 'Green Vally Nursery School'], [
                'dise_code' => '123456',
                'udise_code' => '654321',
                'school_type' => 'Public',
                'vill' => 'Manikchak',
                'post_office' => 'Manikchak',
                'police_station' => 'Lalgola',
                'district' => 'Murshidabad',
                'block' => 'Lalgola',
                'pincode' => '742148',
                'is_active' => true,
                'remarks' => 'This is a sample school.',
            ]);
            $session = $upsert(Session::class, [
                'name' => '2026',
                'school_id' => $school->id,
            ], [
                'start_date' => '2023-01-01',
                'end_date' => '2024-12-31',
                'status' => 'active',
                'order_id' => 1,
                'is_active' => true,
                'remarks' => 'This is the academic session for the year 2023-2024.',
            ]);

            foreach ([
                ['Admin User', 'admin@school.com', 'admin'],
                ['Teacher User', 'teacher@school.com', 'teacher'],
                ['Student User', 'student@school.com', 'student'],
            ] as [$name, $email, $role]) {
                User::query()->firstOrCreate(['email' => $email], [
                    'name' => $name,
                    'password' => Hash::make('password'),
                    'role' => $role,
                    'school_id' => $school->id,
                ]);
            }

            $shrenyIds = [];
            foreach (['Baby Land', 'LKG', 'UKG', 'Class 1', 'Class 2', 'Class 3', 'Class 4'] as $index => $name) {
                $shreny = $upsert(Shreny::class, [
                    'name' => $name,
                    'school_id' => $school->id,
                    'session_id' => $session->id,
                ], [
                    'description' => "Sample class {$name}.",
                    'order_id' => $index + 1,
                    'is_active' => true,
                    'remarks' => 'This is a sample shreny.',
                ]);
                $shrenyIds[$name] = $shreny->id;
            }

            $sectionIds = [];
            foreach (['A', 'B', 'C'] as $index => $name) {
                $section = $upsert(Section::class, [
                    'name' => $name,
                    'school_id' => $school->id,
                    'session_id' => $session->id,
                ], [
                    'description' => "Sample section {$name}.",
                    'order_id' => $index + 1,
                    'is_active' => true,
                    'remarks' => 'This is a sample section.',
                ]);
                $sectionIds[$name] = $section->id;
            }

            $subjects = [
                ['Bengali', 'BENG'],
                ['English', 'ENGL'],
                ['Mathematics', 'MATH'],
                ['General Knowledge', 'GK'],
                ['Three in one', 'EVS'],
                ['Environmental Science', 'EVS'],
                ['History & Civics', 'Hist'],
                ['Geography & Culture', 'Geo'],
                ['Computer Science', 'CS'],
                ['Physical Education', 'PE'],
                ['Art & Work Education', 'Work'],
            ];
            foreach ($subjects as $index => [$name, $shortName]) {
                $upsert(Subject::class, [
                    'name' => $name,
                    'school_id' => $school->id,
                    'session_id' => $session->id,
                ], [
                    'short_name' => $shortName,
                    'order_id' => $index + 1,
                    'is_active' => true,
                    'remarks' => 'This is a sample subject.',
                ]);
            }

            $catalogs = [
                ExamName::class => [
                    ['First Term Exam', 'T1'],
                    ['Half Yearly Exam', 'HY'],
                    ['Second Term Exam', 'T2'],
                    ['Annual Exam', 'AE'],
                ],
                ExamType::class => [
                    ['Formative Exam', 'FE'],
                    ['Summative Exam', 'SE'],
                ],
                ExamPart::class => [
                    ['P1', 'Part 1 Exam'],
                    ['P2', 'Part 2 Exam'],
                ],
                ExamMode::class => [
                    ['Written', 'Written Exam'],
                    ['Oral', 'Oral Exam'],
                    ['Practical', 'Practical Exam'],
                    ['Project', 'Project Exam'],
                    ['Assignment', 'Assignment Exam'],
                ],
            ];
            foreach ($catalogs as $modelClass => $items) {
                foreach ($items as $index => [$name, $description]) {
                    $upsert($modelClass, [
                        'name' => $name,
                        'school_id' => $school->id,
                        'session_id' => $session->id,
                    ], [
                        'description' => $description,
                        'order_id' => $index + 1,
                        'is_active' => true,
                        'remarks' => 'This is a sample exam setting.',
                    ]);
                }
            }

            foreach (array_values($shrenyIds) as $index => $shrenyId) {
                $upsert(ShrenySection::class, [
                    'shreny_id' => $shrenyId,
                    'section_id' => $sectionIds['A'],
                    'school_id' => $school->id,
                    'session_id' => $session->id,
                ], [
                    'order_id' => $index + 1,
                    'is_active' => true,
                    'remarks' => 'This is a sample shreny-section association.',
                ]);
            }

            $grades = [
                ['A+', 'Excellent', 90, 100],
                ['A', 'Very Good', 80, 89],
                ['B+', 'Good', 70, 79],
                ['B', 'Above Average', 60, 69],
                ['C+', 'Average', 45, 59],
                ['C', 'Below Average', 25, 44],
                ['D', 'Poor', 0, 24],
            ];
            foreach ($grades as $index => [$name, $description, $from, $to]) {
                $upsert(ExamGrade::class, [
                    'name' => $name,
                    'school_id' => $school->id,
                    'session_id' => $session->id,
                ], [
                    'description' => $description,
                    'from_percentage' => $from,
                    'to_percentage' => $to,
                    'order_id' => $index + 1,
                    'is_active' => true,
                    'remarks' => 'This is a sample exam grade.',
                ]);
            }
        });
    }
}