<?php

namespace App\Livewire;

use App\Livewire\Concerns\UsesActiveSchoolSession;
use App\Models\ExamName;
use App\Models\ExamPart;
use App\Models\ExamScriptDistribution;
use App\Models\ExamShrenyPartFmPm;
use App\Models\ExamType;
use App\Models\Section;
use App\Models\Session;
use App\Models\Shreny;
use App\Models\ShrenySection;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherSubjects;
use Livewire\Component;

class ExamScriptDistributionComp extends Component
{
    use UsesActiveSchoolSession;
    public bool $showModal = false;
    public ?int $selectedShrenyId = null;
    public ?int $selectedSectionId = null;
    public ?int $selectedSubjectId = null;
    public ?int $selectedExamNameId = null;
    public ?int $selectedExamTypeId = null;
    public ?int $selectedExamPartId = null;
    public ?int $selectedTeacherId = null;
    public ?int $currentSessionId = null;

    public function mount(): void
    {
        $this->currentSessionId = $this->activeSession()?->id;
    }

    public function openTeacherModal(
        int $shrenyId,
        int $sectionId,
        int $subjectId,
        int $examNameId,
        int $examTypeId,
        int $examPartId,
    ): void {
        $this->selectedShrenyId = $shrenyId;
        $this->selectedSectionId = $sectionId;
        $this->selectedSubjectId = $subjectId;
        $this->selectedExamNameId = $examNameId;
        $this->selectedExamTypeId = $examTypeId;
        $this->selectedExamPartId = $examPartId;
        $this->selectedTeacherId = $this->distribution()?->teacher_id;
        $this->resetValidation();
        $this->showModal = true;
    }

    public function saveTeacherAssignment(): void
    {
        if (!$this->canMutate()) {
            return;
        }

        $this->validate([
            'selectedTeacherId' => ['required', 'integer', 'exists:teachers,id'],
        ]);

        $sessionId = $this->currentSessionId ?? $this->activeSession()?->id;
        abort_unless($sessionId, 422, 'No active session is configured.');
        abort_unless(TeacherSubjects::query()
            ->where('teacher_id', $this->selectedTeacherId)
            ->where('subject_id', $this->selectedSubjectId)
            ->where('is_active', true)
            ->where(function ($query) use ($sessionId) {
                $query->where('session_id', $sessionId)->orWhereNull('session_id');
            })
            ->exists(), 422, 'The selected teacher is not assigned to this subject.');

        ExamScriptDistribution::updateOrCreate(
            [
                'shreny_id' => $this->selectedShrenyId,
                'section_id' => $this->selectedSectionId,
                'subject_id' => $this->selectedSubjectId,
                'exam_name_id' => $this->selectedExamNameId,
                'exam_type_id' => $this->selectedExamTypeId,
                'exam_part_id' => $this->selectedExamPartId,
                'session_id' => $sessionId,
            ],
            [
                'name' => 'Exam script distribution',
                'teacher_id' => $this->selectedTeacherId,
                'is_active' => true,
                'school_id' => $this->activeSchoolId(),
            ],
        );

        $this->closeModal();
        session()->flash('success', 'Teacher assigned for exam script distribution.');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset([
            'selectedShrenyId',
            'selectedSectionId',
            'selectedSubjectId',
            'selectedExamNameId',
            'selectedExamTypeId',
            'selectedExamPartId',
            'selectedTeacherId',
        ]);
        $this->resetValidation();
    }

    private function activeSession(): ?Session
    {
        return Session::query()->where('is_active', true)->orderByDesc('id')->first();
    }

    private function distribution(): ?ExamScriptDistribution
    {
        if (!$this->currentSessionId || !$this->selectedShrenyId || !$this->selectedSectionId || !$this->selectedSubjectId) {
            return null;
        }

        return ExamScriptDistribution::query()
            ->where('shreny_id', $this->selectedShrenyId)
            ->where('section_id', $this->selectedSectionId)
            ->where('subject_id', $this->selectedSubjectId)
            ->where('exam_name_id', $this->selectedExamNameId)
            ->where('exam_type_id', $this->selectedExamTypeId)
            ->where('exam_part_id', $this->selectedExamPartId)
            ->where('session_id', $this->currentSessionId)
            ->where('school_id', $this->activeSchoolId())
            ->first();
    }

    public function render()
    {
        $session = $this->activeSession();
        $this->currentSessionId = $session?->id;

        $shrenySections = ShrenySection::query()
            ->where('is_active', true)
            ->when($session, fn($query) => $query->where(function ($query) use ($session) {
                $query->where('session_id', $session->id)->orWhereNull('session_id');
            }))
            ->orderBy('shreny_id')->orderBy('order_id')->get();
        $shrenies = Shreny::query()->where('is_active', true)->get()->keyBy('id');
        $sections = Section::query()->where('is_active', true)->get()->keyBy('id');
        $subjects = Subject::query()->where('is_active', true)->orderBy('order_id')->orderBy('name')->get()->keyBy('id');
        $examNames = ExamName::query()->where('is_active', true)->get()->keyBy('id');
        $examTypes = ExamType::query()->where('is_active', true)->get()->keyBy('id');
        $examParts = ExamPart::query()->where('is_active', true)->get()->keyBy('id');
        $teachers = Teacher::query()->where('is_active', true)->orderBy('name')->get()->keyBy('id');
        $teacherSubjectIds = TeacherSubjects::query()
            ->where('is_active', true)
            ->when($session, fn($query) => $query->where(function ($query) use ($session) {
                $query->where('session_id', $session->id)->orWhereNull('session_id');
            }))
            ->get(['teacher_id', 'subject_id'])
            ->groupBy('subject_id')
            ->map(fn($rows) => $rows->pluck('teacher_id')->unique()->values())
            ->all();
        $configurations = ExamShrenyPartFmPm::query()
            ->whereNull('shreny_id')->whereNull('subject_id')->whereNotNull('exam_part_id')
            ->orderBy('exam_name_id')->orderBy('exam_type_id')->orderBy('exam_part_id')->get();
        $examSubjects = ExamShrenyPartFmPm::query()
            ->whereNotNull('shreny_id')->whereNotNull('subject_id')
            ->get(['shreny_id', 'subject_id'])
            ->unique(fn($row) => $row->shreny_id . ':' . $row->subject_id)
            ->groupBy('shreny_id');
        $distributions = ExamScriptDistribution::query()
            ->when($session, fn($query) => $query->where('session_id', $session->id))
            ->where('school_id', $this->activeSchoolId())
            ->get()
            ->keyBy(fn($row) => $row->shreny_id . ':' . $row->section_id . ':' . $row->subject_id . ':' . $row->exam_name_id . ':' . $row->exam_type_id . ':' . $row->exam_part_id);

        return view('livewire.exam-script-distribution-comp', compact(
            'session',
            'shrenySections',
            'shrenies',
            'sections',
            'subjects',
            'examNames',
            'examTypes',
            'examParts',
            'teachers',
            'teacherSubjectIds',
            'configurations',
            'examSubjects',
            'distributions',
        ));
    }
}
