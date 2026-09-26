<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Teacher;
use App\Models\School;
use App\Models\Session;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schoolId = School::query()->value('id');
        $sessionId = Session::query()->where('school_id', $schoolId)->value('id');

        for ($number = 1; $number <= 20; $number++) {
            $email = "teacher{$number}@example.com";
            $attributes = Teacher::factory()->make([
                'name' => "Sample Teacher {$number}",
                'email' => $email,
                'order_id' => $number,
                'school_id' => $schoolId,
                'session_id' => $sessionId,
            ])->getAttributes();

            Teacher::query()->firstOrCreate(['email' => $email], $attributes);
        }
    }
}
