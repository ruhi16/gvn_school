<?php

use App\Livewire\ExamHalfComp;
use App\Livewire\AdminDashboardComp;
use App\Models\ExamHalf;
use App\Models\ExamName;
use App\Models\ExamPart;
use App\Models\ExamShrenyPartFmPm;
use App\Models\ExamType;
use App\Models\School;
use App\Models\Session;
use App\Models\User;
use Livewire\Livewire;

it('manages halves for a configured exam combination with days and duration', function () {
    $school = School::query()->create(['name' => 'Test School']);
    $session = Session::query()->create([
        'name' => '2026',
        'school_id' => $school->id,
        'is_active' => true,
    ]);
    $examName = ExamName::query()->create(['name' => 'Term One', 'school_id' => $school->id, 'is_active' => true]);
    $examType = ExamType::query()->create(['name' => 'Written', 'school_id' => $school->id, 'is_active' => true]);
    $examPart = ExamPart::query()->create(['name' => 'Part One', 'school_id' => $school->id, 'is_active' => true]);

    ExamShrenyPartFmPm::query()->create([
        'name' => 'Term One Written Part One',
        'exam_name_id' => $examName->id,
        'exam_type_id' => $examType->id,
        'exam_part_id' => $examPart->id,
        'school_id' => $school->id,
        'session_id' => $session->id,
        'is_active' => true,
    ]);

    $user = User::factory()->create(['school_id' => $school->id, 'role' => 'admin']);
    $combinationKey = "{$examName->id}:{$examType->id}:{$examPart->id}";

    Livewire::actingAs($user)->test(ExamHalfComp::class)
        ->set('mutationsEnabled', true)
        ->set('selectedCombinationKey', $combinationKey)
        ->call('create')
        ->set('name', 'Morning')
        ->set('start_time', '09:00')
        ->set('end_time', '10:30')
        ->set('active_exam_days', ['Monday', 'Wednesday'])
        ->assertSee('1 hr 30 min')
        ->call('save')
        ->assertHasNoErrors();

    $half = ExamHalf::query()->sole();
    expect($half->exam_name_id)->toBe($examName->id)
        ->and($half->exam_type_id)->toBe($examType->id)
        ->and($half->exam_part_id)->toBe($examPart->id)
        ->and($half->active_exam_days)->toBe(['Monday', 'Wednesday']);

    Livewire::actingAs($user)->test(ExamHalfComp::class)
        ->set('mutationsEnabled', true)
        ->set('selectedCombinationKey', $combinationKey)
        ->call('delete', $half->id)
        ->assertHasNoErrors();

    expect(ExamHalf::query()->count())->toBe(0);

    Livewire::actingAs($user)->test(AdminDashboardComp::class, ['activePanel' => 'exam-overview'])
        ->assertSee('Exam halves');
});