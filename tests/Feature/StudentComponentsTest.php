<?php

use App\Livewire\AdminDashboardComp;
use App\Livewire\StudentCrComp;
use App\Livewire\StudentDbComp;
use App\Livewire\StudentDbForm;
use App\Models\School;
use App\Models\Section;
use App\Models\Session;
use App\Models\Shreny;
use App\Models\ShrenySection;
use App\Models\StudentCr;
use App\Models\StudentDb;
use App\Models\User;
use Livewire\Livewire;

it('renders the admin dashboard without a Blade compilation exception', function () {
    $school = School::query()->create(['name' => 'Dashboard School']);
    $user = User::factory()->create(['school_id' => $school->id, 'role' => 'admin']);

    Livewire::actingAs($user)->test(AdminDashboardComp::class)
        ->assertSee('GVN SCHOOL')
        ->assertSee('General overview');
});

it('persists and displays StudentDB admission fields using the active school session', function () {
    $school = School::query()->create(['name' => 'Admissions School']);
    $session = Session::query()->create(['name' => '2026', 'school_id' => $school->id, 'is_active' => true]);
    $user = User::factory()->create(['school_id' => $school->id, 'role' => 'admin']);

    Livewire::actingAs($user)->test(StudentDbForm::class)
        ->set('mutationsEnabled', true)
        ->set('name', 'Asha Sen')
        ->set('gender', 'Female')
        ->set('fname', 'Ravi Sen')
        ->set('mname', 'Mita Sen')
        ->set('dob', '2015-04-12')
        ->set('aadhaar_id', '123456789012')
        ->set('pen_id', 'PEN-42')
        ->set('apper_id', 'APAAR-42')
        ->set('village', 'Jiaganj')
        ->set('post_office', 'Jiaganj')
        ->set('police_station', 'Jiaganj')
        ->set('district', 'Murshidabad')
        ->set('block', 'Jiaganj')
        ->set('pincode', '742123')
        ->set('state', 'West Bengal')
        ->set('nationality', 'Indian')
        ->set('mobile_1', '9876543210')
        ->set('mobile_2', '9876543211')
        ->set('email', 'asha@example.test')
        ->set('adm_shreny_id', 4)
        ->set('adm_section_id', 7)
        ->set('order_id', 12)
        ->set('school_id', 999)
        ->set('session_id', 999)
        ->set('is_active', true)
        ->set('remarks', 'Admission complete')
        ->call('save')
        ->assertHasNoErrors();

    $student = StudentDb::query()->sole();
    expect($student->school_id)->toBe($school->id)
        ->and($student->session_id)->toBe($session->id)
        ->and($student->gender)->toBe('Female')
        ->and(substr($student->getRawOriginal('dob'), 0, 10))->toBe('2015-04-12')
        ->and($student->apper_id)->toBe('APAAR-42')
        ->and($student->post_office)->toBe('Jiaganj')
        ->and($student->mobile_2)->toBe('9876543211')
        ->and($student->remarks)->toBe('Admission complete')
        ->and($student->is_deleted)->toBeFalse();

    Livewire::actingAs($user)->test(StudentDbComp::class)
        ->assertSee('Asha Sen')
        ->assertSee('Full admission record')
        ->assertSee('APAAR ID')
        ->assertSee('Jiaganj');
});

it('persists complete StudentCR data within the active school session', function () {
    $school = School::query()->create(['name' => 'Class Records School']);
    $session = Session::query()->create(['name' => '2026', 'school_id' => $school->id, 'is_active' => true]);
    $shreny = Shreny::query()->create([
        'name' => 'Class One', 'school_id' => $school->id, 'session_id' => $session->id, 'is_active' => true,
    ]);
    $section = Section::query()->create([
        'name' => 'A', 'school_id' => $school->id, 'session_id' => $session->id, 'is_active' => true,
    ]);
    ShrenySection::query()->create([
        'shreny_id' => $shreny->id,
        'section_id' => $section->id,
        'school_id' => $school->id,
        'session_id' => $session->id,
        'is_active' => true,
    ]);
    $student = StudentDb::query()->create([
        'name' => 'Nila Das', 'school_id' => $school->id, 'session_id' => $session->id,
        'adm_shreny_id' => $shreny->id, 'adm_section_id' => $section->id, 'is_active' => true,
    ]);
    $user = User::factory()->create(['school_id' => $school->id, 'role' => 'admin']);

    Livewire::actingAs($user)->test(StudentCrComp::class)
        ->set('mutationsEnabled', true)
        ->set('selectedShrenyId', $shreny->id)
        ->set('selectedSectionId', $section->id)
        ->assertSee('Nila Das')
        ->set("rollNumbers.{$student->id}", '3')
        ->set("promotedByStudent.{$student->id}", false)
        ->set("activeByStudent.{$student->id}", false)
        ->set("remarksByStudent.{$student->id}", 'Retained')
        ->call('updateRollNumber', $student->id)
        ->assertHasNoErrors()
        ->assertSee('Class record details');

    $classRecord = StudentCr::query()->sole();
    expect($classRecord->studentdb_id)->toBe($student->id)
        ->and($classRecord->curr_shreny_id)->toBe($shreny->id)
        ->and($classRecord->curr_section_id)->toBe($section->id)
        ->and($classRecord->curr_roll_no)->toBe(3)
        ->and($classRecord->school_id)->toBe($school->id)
        ->and($classRecord->session_id)->toBe($session->id)
        ->and($classRecord->is_promoted)->toBeFalse()
        ->and($classRecord->is_active)->toBeFalse()
        ->and($classRecord->remarks)->toBe('Retained')
        ->and($classRecord->is_deleted)->toBeFalse();
});
