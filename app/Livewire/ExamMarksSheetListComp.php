<?php

namespace App\Livewire;

use App\Livewire\Concerns\UsesActiveSchoolSession;
use App\Models\Section;
use App\Models\Session;
use App\Models\Shreny;
use App\Models\StudentCr;
use Livewire\Component;

class ExamMarksSheetListComp extends Component
{
    use UsesActiveSchoolSession;
    public function render()
    {
        $session = Session::query()->where('is_active', true)
            ->where(function ($query) {
                $query->whereRaw('LOWER(status) = ?', ['active'])->orWhereNull('status');
            })->orderByDesc('id')->first();
        $students = $session
            ? StudentCr::query()->with('student')
                ->where('session_id', $session->id)->where('is_active', true)
                ->orderBy('curr_shreny_id')->orderBy('curr_section_id')
                ->orderByRaw('CASE WHEN curr_roll_no IS NULL THEN 1 ELSE 0 END')
                ->orderBy('curr_roll_no')->get()
            : collect();
        $shrenies = Shreny::query()->where('is_active', true)->get()->keyBy('id');
        $sections = Section::query()->where('is_active', true)->get()->keyBy('id');
        $groups = $students->groupBy(fn ($student) => $student->curr_shreny_id . ':' . $student->curr_section_id)
            ->map(function ($students) use ($shrenies, $sections) {
                $first = $students->first();
                return [
                    'shreny' => $shrenies[$first->curr_shreny_id] ?? null,
                    'section' => $sections[$first->curr_section_id] ?? null,
                    'students' => $students,
                ];
            })->filter(fn ($group) => $group['shreny'] && $group['section'])->values();

        return view('livewire.exam-marks-sheet-list-comp', compact('session', 'groups'));
    }
}
