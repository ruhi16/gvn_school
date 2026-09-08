<?php

namespace Database\Seeders;

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
        ]);

        // Create Teacher
        User::create([
            'name' => 'Teacher User',
            'email' => 'teacher@school.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
        ]);

        // Create Student
        User::create([
            'name' => 'Student User',
            'email' => 'student@school.com',
            'password' => Hash::make('password'),
            'role' => 'student',
        ]);
    }
}
