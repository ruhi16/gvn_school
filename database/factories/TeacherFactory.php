<?php

namespace Database\Factories;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Teacher>
 */
class TeacherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
         return [
            'name' => fake()->name(),
            'desccription' => fake()->optional()->sentence(),
            'email' => fake()->optional()->safeEmail(),
            'mobile' => fake()->optional()->phoneNumber(),
            'high_qual' => fake()->optional()->randomElement(['Secondary', 'Higer Secondary', 'Bachelor', 'Master', 'PhD']),
            'high_qual_subject' => fake()->optional()->randomElement(['Physics', 'Chemistry', 'Mathematics', 'English', 'History']),
            'prof_qual' => fake()->optional()->randomElement(['BEd', 'Med', 'Ph Ed', 'Other']),
            'prof_qual_subject' => fake()->optional()->randomElement(['General Methods', 'Child Psychology', 'Physical Health']),
            'vill' => fake()->optional()->city(), // Simulating village name
            'post_office' => fake()->optional()->streetAddress(),
            'police_station' => fake()->optional()->streetName(),
            'district' => fake()->optional()->state(),
            'block' => fake()->optional()->word(),
            'pincode' => fake()->optional()->postcode(),
            'order_id' => fake()->optional()->randomDigit(),
            'school_id' => null,
            'session_id' => null,
            'is_active' => fake()->boolean(90), // 90% active rate
            'remarks' => fake()->optional()->sentence(),
        ];
    }
}
