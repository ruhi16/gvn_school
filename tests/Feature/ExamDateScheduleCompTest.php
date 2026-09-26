<?php

use App\Livewire\ExamDateScheduleComp;
use App\Models\ExamDateSchedule;
use App\Models\ExamHalf;
use App\Models\ExamMode;
use App\Models\ExamName;
use App\Models\ExamPart;
use App\Models\ExamShrenyPartFmPm;
use App\Models\ExamType;
use App\Models\School;
use App\Models\Section;
use App\Models\Session;
use App\Models\Shreny;
use App\Models\ShrenySection;
use App\Models\Subject;
use App\Models\User;
use Livewire\Livewire;

it('creates, finalizes, reopens, and deletes an exam date schedule', function () {
    $school = School::query()->create(['name' => 'Schedule Test School']);
    $session = Session::query()->create(['name' => '2026', 'school_id' => $school->id, 'is_active' => true]);
    $examName = ExamName::query()->create(['name' => 'Term One', 'school_id' => $school->id, 'is_active' => true]);
    $examType = ExamType::query()->create(['name' => 'Written', 'school_id' => $school->id, 'is_active' => true]);
    $examPart = ExamPart::query()->create(['name' => 'Part One', 'school_id' => $school->id, 'is_active' => true]);
    $examMode = ExamMode::query()->create(['name' => 'Written', 'school_id' => $school->id, 'is_active' => true]);
    $shreny = Shreny::query()->create(['name' => 'Class One', 'school_id' => $school->id, 'session_id' => $session->id, 'is_active' => true]);
    $section = Section::query()->create(['name' => 'A', 'school_id' => $school->id, 'session_id' => $session->id, 'is_active' => true]);
    $subject = Subject::query()->create(['name' => 'Mathematics', 'short_name' => 'MATH', 'school_id' => $school->id, 'session_id' => $session->id, 'is_active' => true]);

    ShrenySection::query()->create([
        'shreny_id' => $shreny->id,
        'section_id' => $section->id,
        'school_id' => $school->id,
        'session_id' => $session->id,
        'is_active' => true,
    ]);
    ExamShrenyPartFmPm::query()->create([
        'name' => 'Configured combination',
        'exam_name_id' => $examName->id,
        'exam_type_id' => $examType->id,
        'exam_part_id' => $examPart->id,
        'school_id' => $school->id,
        'session_id' => $session->id,
        'is_active' => true,
    ]);
    ExamShrenyPartFmPm::query()->create([
        'name' => 'Configured subject',
        'exam_name_id' => $examName->id,
        'exam_type_id' => $examType->id,
        'exam_part_id' => $examPart->id,
        'exam_mode_id' => $examMode->id,
        'shreny_id' => $shreny->id,
        'subject_id' => $subject->id,
        'school_id' => $school->id,
        'session_id' => $session->id,
        'is_active' => true,
    ]);
    $half = ExamHalf::query()->create([
        'name' => 'Morning',
        'exam_name_id' => $examName->id,
        'exam_type_id' => $examType->id,
        'exam_part_id' => $examPart->id,
        'school_id' => $school->id,
        'session_id' => $session->id,
        'is_active' => true,
    ]);

    $user = User::factory()->create(['school_id' => $school->id, 'role' => 'admin']);
    $combinationKey = "{$examName->id}:{$examType->id}:{$examPart->id}";
    $component = Livewire::actingAs($user)->test(ExamDateScheduleComp::class)
        ->set('mutationsEnabled', true)
        ->set('selectedCombinationKey', $combinationKey)
        ->call('create')
        ->set('name', 'Mathematics paper')
        ->set('exam_mode_id', $examMode->id)
        ->set('shreny_id', $shreny->id)
        ->set('section_id', $section->id)
        ->set('subject_id', $subject->id)
        ->set('exam_half_id', $half->id)
        ->set('exam_date', '2026-10-12')
        ->call('save')
        ->assertHasNoErrors();

    $schedule = ExamDateSchedule::query()->sole();
    expect($schedule->exam_name_id)->toBe($examName->id)
        ->and($schedule->subject_id)->toBe($subject->id)
        ->and($schedule->exam_half_id)->toBe($half->id)
        ->and($schedule->is_finalized)->toBeFalse();

    $component->call('finalizeSchedule')->assertHasNoErrors();
    expect($schedule->fresh()->is_finalized)->toBeTrue();
    $component->call('reopenSchedule')->assertHasNoErrors();
    expect($schedule->fresh()->is_finalized)->toBeFalse();
    $component->call('delete', $schedule->id)->assertHasNoErrors();
    expect(ExamDateSchedule::query()->count())->toBe(0);
});