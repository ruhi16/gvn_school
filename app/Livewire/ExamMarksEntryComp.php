<?php

namespace App\Livewire;

use App\Livewire\Concerns\UsesActiveSchoolSession;
use App\Models\ExamMarksEntry;
use App\Models\ExamName;
use App\Models\ExamPart;
use App\Models\ExamShrenyPartFmPm;
use App\Models\ExamType;
use App\Models\Section;
use App\Models\Session;
use App\Models\Shreny;
use App\Models\ShrenySection;
use App\Models\StudentCr;
use App\Models\StudentDb;
use App\Models\Subject;
use App\Models\ExamScriptDistribution;
use App\Models\Teacher;
use Livewire\Component;

class ExamMarksEntryComp extends Component
{
    use UsesActiveSchoolSession;
    public ?int $currentSessionId = null;
    public ?int $selectedShrenyId = null;
    public ?int $selectedSectionId = null;
    public ?int $selectedSubjectId = null;
    public ?int $selectedExamNameId = null;
    public ?int $selectedExamTypeId = null;
    public ?int $selectedExamPartId = null;
    public array $marks = [];
    public array $absent = [];
    public bool $showEntry = false;

    public function mount(): void
    {
        $this->currentSessionId = $this->activeSession()?->id;
    }

    public function openEntry(int $shrenyId, int $sectionId, int $subjectId, int $examNameId, int $examTypeId, int $examPartId): void
    {
        $this->selectedShrenyId = $shrenyId;
        $this->selectedSectionId = $sectionId;
        $this->selectedSubjectId = $subjectId;
        $this->selectedExamNameId = $examNameId;
        $this->selectedExamTypeId = $examTypeId;
        $this->selectedExamPartId = $examPartId;
        $this->showEntry = true;
        $this->loadMarks();
    }

    public function closeEntry(): void
    {
        $this->showEntry = false;
        $this->marks = [];
        $this->absent = [];
    }

    public function saveMark(int $studentId): void
    {
        if (!$this->canMutate()) {
            return;
        }

        $this->ensureEntryScope();
        abort_if(ExamMarksEntry::query()->where($this->scopeKey())->where('is_finalized', true)->exists(), 422, 'Finalized marks cannot be changed.');
        $this->validateMark($studentId);

        $isAbsent = (bool) ($this->absent[$studentId] ?? false);
        $value = $isAbsent ? -99 : ($this->marks[$studentId] ?? null);
        if (!$isAbsent && ($value === null || $value === '')) {
            ExamMarksEntry::query()->where($this->entryKey($studentId))->delete();
            return;
        }

        ExamMarksEntry::updateOrCreate(
            $this->entryKey($studentId),
            [
                'name' => 'Exam marks entry',
                'obtained_marks' => $value,
                'is_finalized' => false,
                'is_active' => true,
                'school_id' => $this->activeSchoolId(),
            ],
        );
    }

    public function updatedAbsent($value, $studentId): void
    {
        if ($value) {
            $this->marks[$studentId] = null;
            $this->saveMark((int) $studentId);
        }
    }

    public function finalizeMarks(): void
    {
        if (!$this->canMutate()) {
            return;
        }

        $this->ensureEntryScope();
        foreach ($this->students() as $student) {
            $this->saveMark($student->id);
        }

        ExamMarksEntry::query()->where($this->scopeKey())->update(['is_finalized' => true]);
        ExamScriptDistribution::query()->where($this->distributionScope())->update(['is_finalized' => true]);
    }

    public function unfinalizeMarks(): void
    {
        if (!$this->canMutate()) {
            return;
        }

        $this->ensureEntryScope();
        ExamMarksEntry::query()->where($this->scopeKey())->update(['is_finalized' => false]);
        ExamScriptDistribution::query()->where($this->distributionScope())->update(['is_finalized' => false]);
    }

    private function distributionScope(): array
    {
        return [
            'shreny_id' => $this->selectedShrenyId,
            'section_id' => $this->selectedSectionId,
            'subject_id' => $this->selectedSubjectId,
            'exam_name_id' => $this->selectedExamNameId,
            'exam_type_id' => $this->selectedExamTypeId,
            'exam_part_id' => $this->selectedExamPartId,
            'session_id' => $this->currentSessionId,
            'school_id' => $this->activeSchoolId(),
        ];
    }

    private function validateMark(int $studentId): void
    {
        if (($this->absent[$studentId] ?? false) || ($this->marks[$studentId] ?? '') === '') {
            return;
        }

        $value = filter_var($this->marks[$studentId], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        abort_if($value === null || $value < 0, 422, 'Marks must be a non-negative whole number.');
        $this->marks[$studentId] = $value;
    }

    private function ensureEntryScope(): void
    {
        abort_unless($this->currentSessionId && $this->selectedShrenyId && $this->selectedSectionId && $this->selectedSubjectId && $this->selectedExamNameId && $this->selectedExamTypeId && $this->selectedExamPartId, 422);
    }

    private function activeSession(): ?Session
    {
        return Session::query()->where('is_active', true)
            ->where('school_id', $this->activeSchoolId())
            ->where(function ($query) {
                $query->whereRaw('LOWER(status) = ?', ['active'])->orWhereNull('status');
            })->orderByDesc('id')->first();
    }

    private function students()
    {
        return StudentCr::query()
            ->with('student')
            ->where('session_id', $this->currentSessionId)
            ->where('school_id', $this->activeSchoolId())
            ->where('curr_shreny_id', $this->selectedShrenyId)
            ->where('curr_section_id', $this->selectedSectionId)
            ->where('is_active', true)
            ->get()
            ->sortBy(fn($student) => [$student->curr_roll_no ?? PHP_INT_MAX, $student->student?->name ?? ''])
            ->values();
    }

    private function scopeKey(): array
    {
        return [
            'shreny_id' => $this->selectedShrenyId,
            'section_id' => $this->selectedSectionId,
            'subject_id' => $this->selectedSubjectId,
            'exam_name_id' => $this->selectedExamNameId,
            'exam_type_id' => $this->selectedExamTypeId,
            'exam_part_id' => $this->selectedExamPartId,
            'session_id' => $this->currentSessionId,
            'school_id' => $this->activeSchoolId(),
        ];
    }

    private function entryKey(int $studentCrId): array
    {
        return $this->scopeKey() + ['student_cr_id' => $studentCrId];
    }

    private function loadMarks(): void
    {
        $this->marks = [];
        $this->absent = [];
        $entries = ExamMarksEntry::query()->where($this->scopeKey())->get();
        foreach ($entries as $entry) {
            $this->marks[$entry->student_cr_id] = $entry->obtained_marks === -99 ? null : $entry->obtained_marks;
            $this->absent[$entry->student_cr_id] = $entry->obtained_marks === -99;
        }
    }

    public function render()
    {
        $session = $this->activeSession();
        $this->currentSessionId = $session?->id;
        $shrenySections = ShrenySection::query()->where('is_active', true)
            ->when($session, fn($query) => $query->where(function ($query) use ($session) {
                $query->where('session_id', $session->id)->orWhereNull('session_id');
            }))->orderBy('shreny_id')->orderBy('order_id')->get();
        $shrenies = Shreny::query()->where('is_active', true)->get()->keyBy('id');
        $sections = Section::query()->where('is_active', true)->get()->keyBy('id');
        $subjects = Subject::query()->where('is_active', true)->orderBy('subject_type_id')->orderBy('order_id')->orderBy('name')->get()->keyBy('id');
        $examNames = ExamName::query()->where('is_active', true)->get()->keyBy('id');
        $examTypes = ExamType::query()->where('is_active', true)->get()->keyBy('id');
        $examParts = ExamPart::query()->where('is_active', true)->get()->keyBy('id');
        $configurations = ExamShrenyPartFmPm::query()->whereNull('shreny_id')->whereNull('subject_id')->whereNotNull('exam_part_id')->orderBy('exam_name_id')->orderBy('exam_type_id')->orderBy('exam_part_id')->get();
        $examSubjects = ExamShrenyPartFmPm::query()->whereNotNull('shreny_id')->whereNotNull('subject_id')->get(['shreny_id', 'subject_id'])->unique(fn($row) => $row->shreny_id . ':' . $row->subject_id)->groupBy('shreny_id');
        $examAssignments = ExamShrenyPartFmPm::query()->whereNotNull('shreny_id')->whereNotNull('subject_id')->get()
            ->keyBy(fn($row) => $row->shreny_id . ':' . $row->subject_id . ':' . $row->exam_name_id . ':' . $row->exam_type_id . ':' . $row->exam_part_id);
        $distributions = ExamScriptDistribution::query()
            ->when($session, fn($query) => $query->where('session_id', $session->id))
            ->where('school_id', $this->activeSchoolId())
            ->get()
            ->keyBy(fn($distribution) => $distribution->shreny_id . ':' . $distribution->section_id . ':' . $distribution->subject_id . ':' . $distribution->exam_name_id . ':' . $distribution->exam_type_id . ':' . $distribution->exam_part_id);
        $entries = ExamMarksEntry::query()->when($session, fn($query) => $query->where('session_id', $session->id))->where('school_id', $this->activeSchoolId())->get()->keyBy(fn($row) => $row->shreny_id . ':' . $row->section_id . ':' . $row->subject_id . ':' . $row->exam_name_id . ':' . $row->exam_type_id . ':' . $row->exam_part_id . ':' . $row->student_cr_id);
        $selectedStudents = $this->showEntry ? $this->students() : collect();
        $selectedSubject = $subjects[$this->selectedSubjectId] ?? null;
        $selectedShreny = $shrenies[$this->selectedShrenyId] ?? null;
        $selectedSection = $sections[$this->selectedSectionId] ?? null;
        $selectedConfiguration = $configurations->first(fn($configuration) => $configuration->exam_name_id === $this->selectedExamNameId && $configuration->exam_type_id === $this->selectedExamTypeId && $configuration->exam_part_id === $this->selectedExamPartId);
        $scopeEntries = $this->showEntry ? ExamMarksEntry::query()->where($this->scopeKey())->get() : collect();
        $isFinalized = $scopeEntries->isNotEmpty() && $scopeEntries->every(fn($entry) => $entry->is_finalized);
        $isIssued = $scopeEntries->contains(fn($entry) => $entry->is_issued);
        $selectedCombinations = $this->showEntry
            ? $configurations->filter(fn($configuration) => isset($examAssignments[$this->selectedShrenyId . ':' . $this->selectedSubjectId . ':' . $configuration->exam_name_id . ':' . $configuration->exam_type_id . ':' . $configuration->exam_part_id]))
            : collect();
        $selectedCombinationEntries = $this->showEntry
            ? ExamMarksEntry::query()->where('shreny_id', $this->selectedShrenyId)->where('section_id', $this->selectedSectionId)->where('subject_id', $this->selectedSubjectId)->where('session_id', $this->currentSessionId)->where('school_id', $this->activeSchoolId())->get()
                ->keyBy(fn($entry) => $entry->exam_name_id . ':' . $entry->exam_type_id . ':' . $entry->exam_part_id . ':' . $entry->student_cr_id)
            : collect();
        $selectedCombinationTeachers = $this->showEntry
            ? ExamScriptDistribution::query()->where('shreny_id', $this->selectedShrenyId)->where('section_id', $this->selectedSectionId)->where('subject_id', $this->selectedSubjectId)->where('session_id', $this->currentSessionId)->where('school_id', $this->activeSchoolId())->get()
                ->keyBy(fn($distribution) => $distribution->exam_name_id . ':' . $distribution->exam_type_id . ':' . $distribution->exam_part_id)
            : collect();
        $teachers = Teacher::query()->where('is_active', true)->get()->keyBy('id');
        $selectedCombinationStates = $this->showEntry
            ? $selectedCombinations->mapWithKeys(function ($configuration) use ($selectedCombinationEntries) {
                $prefix = $configuration->exam_name_id . ':' . $configuration->exam_type_id . ':' . $configuration->exam_part_id . ':';
                $entries = $selectedCombinationEntries->filter(fn($entry, $key) => str_starts_with($key, $prefix));

                return [
                    $prefix => [
                        'is_finalized' => $entries->isNotEmpty() && $entries->every(fn($entry) => $entry->is_finalized),
                        'is_issued' => $entries->contains(fn($entry) => $entry->is_issued),
                    ]
                ];
            })
            : collect();

        return view('livewire.exam-marks-entry-comp', compact('session', 'shrenySections', 'shrenies', 'sections', 'subjects', 'examNames', 'examTypes', 'examParts', 'configurations', 'examSubjects', 'examAssignments', 'distributions', 'entries', 'selectedStudents', 'selectedSubject', 'selectedShreny', 'selectedSection', 'selectedConfiguration', 'isFinalized', 'isIssued', 'selectedCombinations', 'selectedCombinationEntries', 'selectedCombinationTeachers', 'selectedCombinationStates', 'teachers'));
    }
}
