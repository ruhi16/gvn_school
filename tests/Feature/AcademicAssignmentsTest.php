<?php

use App\Livewire\ShrenyTeacherComp;
use App\Livewire\TeacherSubjectComp;
use App\Models\School;
use App\Models\Session;
use App\Models\Shreny;
use App\Models\ShrenyTeachers;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherSubjects;
use App\Models\User;
use Livewire\Livewire;

it('manages teacher subject assignments within the active school session', function () {
    $school = School::query()->create(['name' => 'Assignment School']);
    $session = Session::query()->create(['name' => '2026', 'school_id' => $school->id, 'is_active' => true]);
    $teacher = Teacher::query()->create(['name' => 'Mira Sen', 'school_id' => $school->id, 'session_id' => $session->id]);
    $subject = Subject::query()->create([
        'name' => 'Mathematics', 'short_name' => 'Math', 'school_id' => $school->id, 'session_id' => $session->id,
    ]);
    $user = User::factory()->create(['school_id' => $school->id, 'role' => 'admin']);

    Livewire::actingAs($user)->test(TeacherSubjectComp::class)
        ->set('mutationsEnabled', true)
        ->call('create')
        ->set('teacher_id', $teacher->id)
        ->set('subject_id', $subject->id)
        ->set('subject_type', 'main_subject')
        ->set('order_id', 2)
        ->set('remarks', 'Primary mathematics teacher')
        ->call('save')
        ->assertHasNoErrors()
        ->assertSee('Mira Sen')
        ->assertSee('Mathematics');

    $assignment = TeacherSubjects::query()->sole();
    expect($assignment->school_id)->toBe($school->id)
        ->and($assignment->session_id)->toBe($session->id)
        ->and($assignment->subject_type)->toBe('main_subject')
        ->and($assignment->teacher->is($teacher))->toBeTrue()
        ->and($assignment->subject->is($subject))->toBeTrue();

    Livewire::actingAs($user)->test(TeacherSubjectComp::class)
        ->set('mutationsEnabled', true)
        ->call('edit', $assignment->id)
        ->set('remarks', 'Updated assignment')
        ->call('save')
        ->assertHasNoErrors()
        ->call('delete', $assignment->id);

    expect($assignment->fresh()->is_deleted)->toBeTrue()
        ->and($assignment->fresh()->is_active)->toBeFalse();
});

it('manages shreny teacher assignments within the active school session', function () {
    $school = School::query()->create(['name' => 'Shreny Assignment School']);
    $session = Session::query()->create(['name' => '2026', 'school_id' => $school->id, 'is_active' => true]);
    $shreny = Shreny::query()->create([
        'name' => 'Class One', 'school_id' => $school->id, 'session_id' => $session->id, 'is_active' => true,
    ]);
    $teacher = Teacher::query()->create(['name' => 'Ravi Das', 'school_id' => $school->id, 'session_id' => $session->id]);
    $user = User::factory()->create(['school_id' => $school->id, 'role' => 'admin']);

    Livewire::actingAs($user)->test(ShrenyTeacherComp::class)
        ->set('mutationsEnabled', true)
        ->call('create')
        ->set('shreny_id', $shreny->id)
        ->set('teacher_id', $teacher->id)
        ->set('teacher_type', 'class_teacher')
        ->set('order_id', 1)
        ->call('save')
        ->assertHasNoErrors()
        ->assertSee('Class One')
        ->assertSee('Ravi Das');

    $assignment = ShrenyTeachers::query()->sole();
    expect($assignment->school_id)->toBe($school->id)
        ->and($assignment->session_id)->toBe($session->id)
        ->and($assignment->teacher_type)->toBe('class_teacher')
        ->and($assignment->shreny->is($shreny))->toBeTrue()
        ->and($assignment->teacher->is($teacher))->toBeTrue();

    Livewire::actingAs($user)->test(ShrenyTeacherComp::class)
        ->set('mutationsEnabled', true)
        ->call('edit', $assignment->id)
        ->set('teacher_type', 'subject_teacher')
        ->call('save')
        ->assertHasNoErrors()
        ->call('delete', $assignment->id);

    expect($assignment->fresh()->teacher_type)->toBe('subject_teacher')
        ->and($assignment->fresh()->is_deleted)->toBeTrue()
        ->and($assignment->fresh()->is_active)->toBeFalse();
});