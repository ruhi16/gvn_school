<?php

namespace App\Support;

use App\Models\ExamGrade;
use App\Models\ExamMarksEntry;
use App\Models\ExamName;
use App\Models\ExamPart;
use App\Models\ExamShrenyPartFmPm;
use App\Models\ExamShrenySubjectGrade;
use App\Models\ExamType;
use App\Models\Section;
use App\Models\Session;
use App\Models\Shreny;
use App\Models\ShrenySection;
use App\Models\StudentCr;
use App\Models\Subject;
use Illuminate\Support\Collection;

class ExamMarksRegisterData
{
    public function build(?int $shrenySectionId = null): array
    {
        $session = Session::query()->where('is_active', true)
            ->where(function ($query) {
                $query->whereRaw('LOWER(status) = ?', ['active'])->orWhereNull('status');
            })->orderByDesc('id')->first();

        if (!$session) {
            return [
                'session' => null,
                'groups' => collect(),
                'examNames' => collect(),
                'examTypes' => collect(),
                'examParts' => collect(),
            ];
        }

        $shrenySections = ShrenySection::query()
            ->where('is_active', true)
            ->when($shrenySectionId, fn ($query) => $query->whereKey($shrenySectionId))
            ->where(function ($query) use ($session) {
                $query->where('session_id', $session->id)->orWhereNull('session_id');
            })
            ->orderBy('shreny_id')->orderBy('order_id')->get();
        $shrenies = Shreny::query()->where('is_active', true)->get()->keyBy('id');
        $sections = Section::query()->where('is_active', true)->get()->keyBy('id');
        $subjects = Subject::query()->where('is_active', true)
            ->orderBy('subject_type_id')->orderBy('order_id')->orderBy('name')->get()->keyBy('id');
        $examNames = ExamName::query()->where('is_active', true)->get()->keyBy('id');
        $examTypes = ExamType::query()->where('is_active', true)->orderBy('order_id')->orderBy('name')->get()->keyBy('id');
        $examParts = ExamPart::query()->where('is_active', true)->get()->keyBy('id');
        $assignments = ExamShrenyPartFmPm::query()
            ->where('is_active', true)
            ->whereNotNull('shreny_id')->whereNotNull('subject_id')->whereNotNull('exam_part_id')
            ->where(function ($query) use ($session) {
                $query->where('session_id', $session->id)->orWhereNull('session_id');
            })->orderBy('exam_name_id')->orderBy('exam_type_id')->orderBy('exam_part_id')->get();
        $entries = ExamMarksEntry::query()->where('session_id', $session->id)->get()
            ->keyBy(fn ($entry) => $this->entryKey($entry->student_cr_id, $entry->subject_id, $entry->exam_name_id, $entry->exam_type_id, $entry->exam_part_id));
        $grades = ExamGrade::query()->where('is_active', true)->orderBy('order_id')->orderByDesc('from_percentage')->get()->keyBy('id');
        $gradeMappings = ExamShrenySubjectGrade::query()->where('is_active', true)->get()
            ->groupBy(fn ($mapping) => $mapping->shreny_id . ':' . $mapping->subject_type_id);

        $groups = $shrenySections->map(function ($shrenySection) use ($session, $shrenies, $sections, $subjects, $assignments, $entries, $grades, $gradeMappings, $examTypes) {
            $shreny = $shrenies[$shrenySection->shreny_id] ?? null;
            $section = $sections[$shrenySection->section_id] ?? null;
            $students = StudentCr::query()->with('student')
                ->where('session_id', $session->id)
                ->where('curr_shreny_id', $shrenySection->shreny_id)
                ->where('curr_section_id', $shrenySection->section_id)
                ->where('is_active', true)->get()
                ->sortBy(fn ($student) => [$student->curr_roll_no ?? PHP_INT_MAX, $student->student?->name ?? ''])->values();
            $groupAssignments = $assignments->where('shreny_id', $shrenySection->shreny_id);
            $groupSubjects = $groupAssignments->pluck('subject_id')->unique()->map(fn ($id) => $subjects[$id] ?? null)->filter()
                ->sortBy(fn ($subject) => [
                    $groupAssignments->where('subject_id', $subject->id)
                        ->map(fn ($assignment) => $examTypes[$assignment->exam_type_id]?->order_id ?? PHP_INT_MAX)->min(),
                    $subject->order_id ?? PHP_INT_MAX,
                    $subject->name,
                ])->values();
            $combinations = $groupAssignments->groupBy(fn ($assignment) => $assignment->exam_name_id . ':' . $assignment->exam_type_id . ':' . $assignment->exam_part_id)
                ->map(fn ($parts) => $parts->sortBy('subject_id')->first())
                ->sortBy(fn ($combination) => [
                    $examTypes[$combination->exam_type_id]?->order_id ?? PHP_INT_MAX,
                    $combination->exam_name_id,
                    $combination->order_id ?? PHP_INT_MAX,
                    $combination->exam_part_id,
                ])->values();

            $registerSubjects = $groupSubjects->map(function ($subject) use ($groupAssignments, $entries, $grades, $gradeMappings, $shrenySection) {
                $subjectAssignments = $groupAssignments->where('subject_id', $subject->id);
                $terms = $subjectAssignments->groupBy('exam_name_id')->map(function ($parts, $examNameId) {
                    $parts = $parts->sortBy(['exam_type_id', 'order_id', 'exam_part_id'])->values();
                    return [
                        'id' => $examNameId,
                        'name' => $parts->first()->exam_name_id,
                        'parts' => $parts,
                        'full_marks' => $parts->sum(fn ($part) => (int) ($part->full_marks ?? 0)),
                    ];
                })->values();
                $fullMarks = $subjectAssignments->sum(fn ($part) => (int) ($part->full_marks ?? 0));
                $mappingKey = $shrenySection->shreny_id . ':' . ($subject->subject_type_id ?? '');
                $mappedGrades = $gradeMappings[$mappingKey] ?? collect();

                return [
                    'subject' => $subject,
                    'assignments' => $subjectAssignments,
                    'terms' => $terms,
                    'full_marks' => $fullMarks,
                    'display_full_marks' => $subjectAssignments->pluck('full_marks')->filter(fn ($marks) => $marks !== null)->unique()->count() === 1
                        ? $subjectAssignments->first()->full_marks
                        : null,
                    'grades' => $mappedGrades->map(fn ($mapping) => $grades[$mapping->exam_grade_id] ?? null)->filter()->values(),
                ];
            })->values();

            return [
                'id' => $shrenySection->id,
                'shreny' => $shreny,
                'section' => $section,
                'students' => $students,
                'subjects' => $registerSubjects,
                'combinations' => $combinations,
                'entries' => $entries->filter(fn ($entry) => $entry->shreny_id === $shrenySection->shreny_id && $entry->section_id === $shrenySection->section_id),
            ];
        })->filter(fn ($group) => $group['shreny'] && $group['section'])->values();

        return compact('session', 'groups', 'examNames', 'examTypes', 'examParts');
    }

    public function mark(Collection $entries, int $studentId, int $subjectId, object $part): mixed
    {
        $entry = $entries[$this->entryKey($studentId, $subjectId, $part->exam_name_id, $part->exam_type_id, $part->exam_part_id)] ?? null;

        return $entry?->obtained_marks === -99 ? 'AB' : $entry?->obtained_marks;
    }

    public function grade(?float $total, int $fullMarks, Collection $grades): ?string
    {
        if ($total === null || $fullMarks <= 0) {
            return null;
        }

        $percentage = ($total / $fullMarks) * 100;
        return $grades->first(fn ($grade) => $percentage >= (float) $grade->from_percentage && $percentage <= (float) $grade->to_percentage)?->name
            ?? ExamGrade::query()->where('is_active', true)->get()->first(fn ($grade) => $percentage >= (float) $grade->from_percentage && $percentage <= (float) $grade->to_percentage)?->name;
    }

    private function entryKey(int $studentId, int $subjectId, int $examNameId, int $examTypeId, int $examPartId): string
    {
        return $studentId . ':' . $subjectId . ':' . $examNameId . ':' . $examTypeId . ':' . $examPartId;
    }
}
