<?php

namespace Database\Seeders;

use App\Models\ExamGrade;
use App\Models\ExamMode;
use App\Models\ExamName;
use App\Models\ExamPart;
use App\Models\ExamType;

use App\Models\School;
use App\Models\Session;
use App\Models\Shreny;
use App\Models\Section;
use App\Models\ShrenySection;
use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@school.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'school_id' => 1, // Assuming the school ID is 1 for the admin user
        ]);

        // Create Teacher
        User::create([
            'name' => 'Teacher User',
            'email' => 'teacher@school.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
            'school_id' => 1, // Assuming the school ID is 1 for the teacher user
        ]);

        // Create Student
        User::create([
            'name' => 'Student User',
            'email' => 'student@school.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'school_id' => 1, // Assuming the school ID is 1 for the student user
        ]);
        School::create([
            'name' => 'Green Vally Nursery School',
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
        Session::create([
            'name' => '2026',
            'start_date' => '2023-01-01',
            'end_date' => '2024-12-31',
            'status' => 'active',
            'order_id' => 1,
            'school_id' => 1,
            'is_active' => true,
            'remarks' => 'This is the academic session for the year 2023-2024.',
        ]);
        Shreny::create([
            'name' => 'Baby Land',
            'description' => 'This is the first shreny.',
            'order_id' => 1,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample shreny.',
        ]);
        Shreny::create([
            'name' => 'LKG',
            'description' => 'This is the first shreny.',
            'order_id' => 2,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample shreny.',
        ]);
        Shreny::create([
            'name' => 'UKG',
            'description' => 'This is the first shreny.',
            'order_id' => 3,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample shreny.',
        ]);
        Shreny::create([
            'name' => 'Class 1',
            'description' => 'This is the first shreny.',
            'order_id' => 4,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample shreny.',
        ]);
        Shreny::create([
            'name' => 'Class 2',
            'description' => 'This is the first shreny.',
            'order_id' => 5,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample shreny.',
        ]);
        Shreny::create([
            'name' => 'Class 3',
            'description' => 'This is the first shreny.',
            'order_id' => 6,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample shreny.',
        ]);
        Shreny::create([
            'name' => 'Class 4',
            'description' => 'This is the first shreny.',
            'order_id' => 7,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample shreny.',
        ]);
        Section::create([
            'name' => 'A',
            'description' => 'This is the first section.',
            'order_id' => 1,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample section.',
        ]);
        Section::create([
            'name' => 'B',
            'description' => 'This is the second section.',
            'order_id' => 2,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample section.',
        ]);
        Section::create([
            'name' => 'C',
            'description' => 'This is the third section.',
            'order_id' => 3,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample section.',
        ]);

        Subject::create([
            'name' => 'Bengali',
            'short_name' => 'BENG',
            'order_id' => 1,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample subject.',
        ]);
        Subject::create([
            'name' => 'English',
            'short_name' => 'ENGL',
            'order_id' => 2,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample subject.',
        ]);
        Subject::create([
            'name' => 'Mathematics',
            'short_name' => 'MATH',
            'order_id' => 3,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample subject.',
        ]);
        Subject::create([
            'name' => 'General Knowledge',
            'short_name' => 'GK',
            'order_id' => 4,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample subject.',
        ]);
        Subject::create([
            'name' => 'Three in one',
            'short_name' => 'EVS',
            'order_id' => 5,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample subject.',
        ]);
        Subject::create([
            'name' => 'Environmental Science',
            'short_name' => 'EVS',
            'order_id' => 6,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample subject.',
        ]);
        Subject::create([
            'name' => 'History & Civics',
            'short_name' => 'Hist',
            'order_id' => 7,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample subject.',
        ]);
        Subject::create([
            'name' => 'Geography & Culture',
            'short_name' => 'Geo',
            'order_id' => 8,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample subject.',
        ]);
        Subject::create([
            'name' => 'Computer Science',
            'short_name' => 'CS',
            'order_id' => 9,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample subject.',
        ]);
        Subject::create([
            'name' => 'Physical Education',
            'short_name' => 'PE',
            'order_id' => 10,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample subject.',
        ]);
        Subject::create([
            'name' => 'Art & Work Education',
            'short_name' => 'Work',
            'order_id' => 11,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample subject.',
        ]);
        ExamName::create([
            'name' => 'First Term Exam',
            'description' => 'T1',
            'order_id' => 1,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample exam name.',
        ]);
        ExamName::create([
            'name' => 'Half Yearly Exam',
            'description' => 'HY',
            'order_id' => 2,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample exam name.',
        ]);
        ExamName::create([
            'name' => 'Second Term Exam',
            'description' => 'T2',
            'order_id' => 3,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample exam name.',
        ]);
        ExamName::create([
            'name' => 'Annual Exam',
            'description' => 'AE',
            'order_id' => 4,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample exam name.',
        ]);
        ExamType::create([
            'name' => 'Formative Exam',
            'description' => 'FE',
            'order_id' => 1,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample exam type.',
        ]);
        ExamType::create([
            'name' => 'Summative Exam',
            'description' => 'SE',
            'order_id' => 2,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample exam type.',
        ]);
        ExamPart::create([
            'name' => 'P1',
            'description' => 'Part 1 Exam',
            'order_id' => 1,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample exam part.',
        ]);
        ExamPart::create([
            'name' => 'P2',
            'description' => 'Part 2 Exam',
            'order_id' => 2,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample exam part.',
        ]);
        ExamMode::create([
            'name' => 'Written',
            'description' => 'Written Exam',
            'order_id' => 1,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample exam mode.',
        ]);
        ExamMode::create([
            'name' => 'Oral',
            'description' => 'Oral Exam',
            'order_id' => 2,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample exam mode.',
        ]);
        ExamMode::create([
            'name' => 'Practical',
            'description' => 'Practical Exam',
            'order_id' => 3,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample exam mode.',
        ]);
        ExamMode::create([
            'name' => 'Project',
            'description' => 'Project Exam',
            'order_id' => 4,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample exam mode.',
        ]);
        ExamMode::create([
            'name' => 'Assignment',
            'description' => 'Assignment Exam',
            'order_id' => 5,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample exam mode.',
        ]);
        ShrenySection::create([
            'shreny_id' => 1,
            'section_id' => 1,
            'order_id' => 1,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample shreny-section association.',
            'updated_at' => now(),
            'created_at' => now(),
        ]);
        ShrenySection::create([
            'shreny_id' => 2,
            'section_id' => 1,
            'order_id' => 2,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample shreny-section association.',
            'updated_at' => now(),
            'created_at' => now(),
        ]);
        ShrenySection::create([
            'shreny_id' => 3,
            'section_id' => 1,
            'order_id' => 3,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample shreny-section association.',
            'updated_at' => now(),
            'created_at' => now(),
        ]);
        ShrenySection::create([
            'shreny_id' => 4,
            'section_id' => 1,
            'order_id' => 4,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample shreny-section association.',
            'updated_at' => now(),
            'created_at' => now(),
        ]);
        ShrenySection::create([
            'shreny_id' => 5,
            'section_id' => 1,
            'order_id' => 5,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample shreny-section association.',
            'updated_at' => now(),
            'created_at' => now(),
        ]);
        ShrenySection::create([
            'shreny_id' => 6,
            'section_id' => 1,
            'order_id' => 6,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample shreny-section association.',
            'updated_at' => now(),
            'created_at' => now(),
        ]);
        ShrenySection::create([
            'shreny_id' => 7,
            'section_id' => 1,
            'order_id' => 7,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample shreny-section association.',
            'updated_at' => now(),
            'created_at' => now(),
        ]);
        ExamGrade::create([
            'name' => 'A+',
            'description' => 'Excellent',
            'from_percentage' => 90,
            'to_percentage' => 100,
            'order_id' => 1,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample exam grade.',
        ]);
        ExamGrade::create([
            'name' => 'A',
            'description' => 'Very Good',
            'from_percentage' => 80,
            'to_percentage' => 89,
            'order_id' => 2,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample exam grade.',
        ]);
        ExamGrade::create([
            'name' => 'B+',
            'description' => 'Good',
            'from_percentage' => 70,
            'to_percentage' => 79,
            'order_id' => 3,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample exam grade.',
        ]);
        ExamGrade::create([
            'name' => 'B',
            'description' => 'Above Average',
            'from_percentage' => 60,
            'to_percentage' => 69,
            'order_id' => 4,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample exam grade.',
        ]);
        ExamGrade::create([
            'name' => 'C+',
            'description' => 'Average',
            'from_percentage' => 45,
            'to_percentage' => 59,
            'order_id' => 5,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample exam grade.',
        ]);
        ExamGrade::create([
            'name' => 'C',
            'description' => 'Below Average',
            'from_percentage' => 25,
            'to_percentage' => 44,
            'order_id' => 6,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample exam grade.',
        ]);
        ExamGrade::create([
            'name' => 'D',
            'description' => 'Poor',
            'from_percentage' => 0,
            'to_percentage' => 24,
            'order_id' => 7,
            'school_id' => 1,
            'session_id' => 1,
            'is_active' => true,
            'remarks' => 'This is a sample exam grade.',
        ]);

    }
}
