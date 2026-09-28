<?php

use App\Livewire\ExamHalfComp;
use App\Livewire\AdminDashboardComp;
use App\Models\ExamHalf;
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
    $user = User::factory()->create(['school_id' => $school->id, 'role' => 'admin']);

    Livewire::actingAs($user)->test(ExamHalfComp::class)
        ->set('mutationsEnabled', true)
        ->call('create')
        ->set('name', 'Morning')
        ->set('start_time', '09:00')
        ->set('end_time', '10:30')
        ->set('active_exam_days', ['Monday', 'Wednesday'])
        ->assertSee('1 hr 30 min')
        ->call('save')
        ->assertHasNoErrors();

    $half = ExamHalf::query()->sole();
    expect($half->school_id)->toBe($school->id)
        ->and($half->session_id)->toBe($session->id)
        ->and($half->active_exam_days)->toBe(['Monday', 'Wednesday']);

    Livewire::actingAs($user)->test(ExamHalfComp::class)
        ->set('mutationsEnabled', true)
        ->call('delete', $half->id)
        ->assertHasNoErrors();

    expect(ExamHalf::query()->count())->toBe(0);

    Livewire::actingAs($user)->test(AdminDashboardComp::class, ['activePanel' => 'exam-overview'])
        ->assertSee('Exam halves');
});