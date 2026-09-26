<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class StudentdbSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schoolId = DB::table('schools')->value('id');
        $sessionId = DB::table('sessions')->where('school_id', $schoolId)->value('id');
        $shrenyIds = DB::table('shrenies')
            ->where('school_id', $schoolId)
            ->where('session_id', $sessionId)
            ->orderBy('order_id')
            ->pluck('id')
            ->values();

        if (!$schoolId || !$sessionId || $shrenyIds->isEmpty()) {
            throw new \RuntimeException('Seed the school, session, and Shrenies before seeding students.');
        }

        $genders = ['Male', 'Female', 'Other'];
        
        $villages = ['Jiaganj', 'Azimganj', 'Lalgola', 'Murshidabad', 'Raghunathganj'];
        $districts = ['Murshidabad', 'Nadia', 'Hooghly', 'Bardhaman'];
        $blocks = ['Murshidabad-Jiaganj', 'Lalgola', 'Baharampur', 'Bhagwangola'];

        for ($i = 1; $i <= 20; $i++) {
            $gender = $genders[array_rand($genders)];
            
            // Basic contextual names based on gender
            if ($gender === 'Male') {
                $name = "Student Boy " . $i;
                $fname = "Father Name " . $i;
                $mname = "Mother Name " . $i;
            } elseif ($gender === 'Female') {
                $name = "Student Girl " . $i;
                $fname = "Father Name " . $i;
                $mname = "Mother Name " . $i;
            } else {
                $name = "Student Individual " . $i;
                $fname = "Father Name " . $i;
                $mname = "Mother Name " . $i;
            }

            $shrenyId = $shrenyIds[($i - 1) % $shrenyIds->count()];
            $sectionId = DB::table('shreny_sections')
                ->where('shreny_id', $shrenyId)
                ->where('school_id', $schoolId)
                ->where('session_id', $sessionId)
                ->value('section_id');

            if (!$sectionId) {
                throw new \RuntimeException("No section is mapped to Shreny {$shrenyId}.");
            }

            DB::table('student_dbs')->updateOrInsert(['email' => "student{$i}@example.com"], [
                'name' => $name,
                'dp_img_ref' => 'student-dbs/1/dp/' . $i . '.jpg',
                'gender' => $gender,
                'fname' => $fname,
                'mname' => $mname,
                'dob' => Carbon::now()->subYears(rand(6, 16))->subDays(rand(1, 365))->format('Y-m-d'),
                'dob_cert_img_ref' => 'student-dbs/1/dob/' . $i . '.pdf',
                'aadhaar_id' => rand(2000, 9999) . rand(1000, 9999) . rand(1000, 9999), // 12 digit mock ID
                'aadhaar_img_ref' => 'student-dbs/1/aadhaar/' . $i . '.pdf',
                'pen_id' => 'PEN' . rand(100000, 999999),
                'apper_id' => 'APP' . rand(100000, 999999),
                
                // Address fields
                'village' => $villages[array_rand($villages)],
                'post_office' => 'PO-' . Str::random(5),
                'police_station' => 'PS-' . Str::random(5),
                'district' => $districts[array_rand($districts)],
                'block' => $blocks[array_rand($blocks)],
                'pincode' => (string)rand(742100, 742199),
                'state' => 'West Bengal', // Defaults natively but kept for data density
                'nationality' => 'Indian',

                // Contact
                'mobile_1' => '9876' . rand(10000, 99999),
                'mobile_2' => '8765' . rand(10000, 99999),
                'email' => "student{$i}@example.com",

                // Class relational mappings (integers)
                'adm_shreny_id' => $shrenyId,
                'adm_section_id' => $sectionId,

                // System tags
                'order_id' => $i,
                'school_id' => $schoolId,
                'session_id' => $sessionId,
                'is_active' => (rand(1, 10) > 1), // 90% chance to be active
                'remarks' => rand(1, 5) == 5 ? 'Needs review' : null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
