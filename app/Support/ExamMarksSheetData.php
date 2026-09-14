<?php

namespace App\Support;

use App\Models\ExamGrade;
use App\Models\ExamMarksEntry;
use App\Models\ExamName;
use App\Models\ExamPart;
use App\Models\ExamShrenyPartFmPm;
use App\Models\ExamShrenySubjectGrade;
use App\Models\ExamType;
use App\Models\School;
use App\Models\Session;
use App\Models\Shreny;
use App\Models\ShrenySection;
use App\Models\ShrenySubject;
use App\Models\StudentCr;
use App\Models\Subject;
use Illuminate\Support\Collection;

class ExamMarksSheetData
{
    public function build(StudentCr $studentCr): array
    {
        $session = Session::query()->whereKey($studentCr->session_id)
            ->where('is_active', true)->first()
            ?? Session::query()->where('is_active', true)->orderByDesc('id')->first();
        $studentCr->load('student');
        $student = $studentCr->student;
        $shreny = Shreny::query()->find($studentCr->curr_shreny_id);
        $section = \App\Models\Section::query()->find($studentCr->curr_section_id);
        $school = School::query()->find($studentCr->school_id ?: $student?->school_id ?: $session?->school_id);

        if (!$session || !$student || !$shreny) {
            return compact('studentCr', 'student', 'shreny', 'section', 'school', 'session') + ['reportSubjects' => collect(), 'terms' => collect(), 'entries' => collect(), 'grades' => collect(), 'examTypes' => collect(), 'examParts' => collect()];
        }

        $subjectLinks = ShrenySubject::query()->where('shreny_id', $shreny->id)
            ->where('is_active', true)
            ->where(function ($query) use ($session) {
                $query->where('session_id', $session->id)->orWhereNull('session_id');
            })->orderByRaw('CASE WHEN order_id IS NULL THEN 1 ELSE 0 END')->orderBy('order_id')->get();
        $subjectIds = $subjectLinks->pluck('subject_id')->filter()->unique();
        $subjects = Subject::query()->whereIn('id', $subjectIds)->where('is_active', true)->get()->keyBy('id');
        $assignments = ExamShrenyPartFmPm::query()
            ->where('shreny_id', $shreny->id)->whereIn('subject_id', $subjectIds)
            ->where('is_active', true)->whereNotNull('exam_part_id')
            ->where(function ($query) use ($session) {
                $query->where('session_id', $session->id)->orWhereNull('session_id');
            })->get();
        $examNames = ExamName::query()->where('is_active', true)->get()->keyBy('id');
        $examTypes = ExamType::query()->where('is_active', true)->orderBy('order_id')->orderBy('name')->get()->keyBy('id');
        $examParts = ExamPart::query()->where('is_active', true)->get()->keyBy('id');
        $allowedTypes = $assignments->groupBy('exam_name_id')->map(fn (Collection $parts) => $parts
            ->pluck('exam_type_id')->unique()
            ->sortBy(fn ($examTypeId) => [$examTypes[$examTypeId]?->order_id ?? PHP_INT_MAX, $examTypes[$examTypeId]?->name ?? ''])
            ->take(2)->values());
        $assignments = $assignments->filter(fn ($assignment) => $allowedTypes[$assignment->exam_name_id]?->contains($assignment->exam_type_id))->values();
        $terms = $assignments->groupBy('exam_name_id')->map(function (Collection $parts, $examNameId) use ($examNames, $examTypes, $examParts) {
            $parts = $parts->unique(fn ($part) => $part->exam_type_id . ':' . $part->exam_part_id)->sortBy(fn ($part) => [
                $examTypes[$part->exam_type_id]?->order_id ?? PHP_INT_MAX,
                $examParts[$part->exam_part_id]?->order_id ?? PHP_INT_MAX,
                $part->order_id ?? PHP_INT_MAX,
            ])->values();
            return [
                'id' => $examNameId,
                'name' => $examNames[$examNameId]?->name ?? 'Exam term',
                'parts' => $parts,
                'full_marks' => $parts->sum(fn ($part) => (int) ($part->full_marks ?? 0)),
            ];
        })->sortBy(fn ($term) => $examNames[$term['id']]?->order_id ?? PHP_INT_MAX)->values();
        $entries = ExamMarksEntry::query()->where('student_cr_id', $studentCr->id)
            ->where('session_id', $session->id)->get()
            ->keyBy(fn ($entry) => $this->entryKey($entry->subject_id, $entry->exam_name_id, $entry->exam_type_id, $entry->exam_part_id));
        $grades = ExamGrade::query()->where('is_active', true)->orderByDesc('from_percentage')->get();
        $gradeMappings = ExamShrenySubjectGrade::query()->where('shreny_id', $shreny->id)
            ->where('is_active', true)->get()->groupBy('subject_type_id');
        $reportSubjects = $subjectLinks->map(fn ($link) => $subjects[$link->subject_id] ?? null)->filter()
            ->sortBy(fn ($subject) => [$subject->subject_type_id ?? PHP_INT_MAX, $subject->order_id ?? PHP_INT_MAX, $subject->name])
            ->map(function ($subject) use ($assignments, $gradeMappings, $grades) {
                $subjectAssignments = $assignments->where('subject_id', $subject->id);
                $mappedGrades = ($gradeMappings[$subject->subject_type_id] ?? collect())
                    ->map(fn ($mapping) => $grades->firstWhere('id', $mapping->exam_grade_id))->filter()->values();
                return [
                    'subject' => $subject,
                    'assignments' => $subjectAssignments,
                    'full_marks' => $subjectAssignments->sum(fn ($part) => (int) ($part->full_marks ?? 0)),
                    'grades' => $mappedGrades,
                ];
            })->values();

        return compact('studentCr', 'student', 'shreny', 'section', 'school', 'session', 'reportSubjects', 'terms', 'entries', 'examNames', 'examTypes', 'examParts', 'grades');
    }

    public function mark(Collection $entries, int $subjectId, object $part): mixed
    {
        $entry = $entries[$this->entryKey($subjectId, $part->exam_name_id, $part->exam_type_id, $part->exam_part_id)] ?? null;
        return $entry?->obtained_marks === -99 ? 'AB' : $entry?->obtained_marks;
    }

    public function grade(?float $total, int $fullMarks, Collection $grades): ?string
    {
        if ($total === null || $fullMarks <= 0) return null;
        $percentage = $total / $fullMarks * 100;
        return $grades->first(fn ($grade) => $percentage >= (float) $grade->from_percentage && $percentage <= (float) $grade->to_percentage)?->name
            ?? ExamGrade::query()->where('is_active', true)->get()->first(fn ($grade) => $percentage >= (float) $grade->from_percentage && $percentage <= (float) $grade->to_percentage)?->name;
    }

    private function entryKey(int $subjectId, int $examNameId, int $examTypeId, int $examPartId): string
    {
        return $subjectId . ':' . $examNameId . ':' . $examTypeId . ':' . $examPartId;
    }
}
