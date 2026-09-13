<?php

namespace App\Livewire;

use App\Models\ExamName;
use App\Models\ExamPart;
use App\Models\ExamShrenyPartFmPm;
use App\Models\ExamType;
use App\Models\Shreny;
use App\Models\Subject;
use Livewire\Component;

class ExamMarksSettings extends Component
{
    public function updateMarks(int $configurationId, int $shrenyId, int $subjectId, string $field, mixed $value): void
    {
        abort_unless(in_array($field, ['full_marks', 'pass_marks', 'time_alloted'], true), 422);

        $configuration = ExamShrenyPartFmPm::query()
            ->whereKey($configurationId)
            ->whereNull('shreny_id')
            ->whereNull('subject_id')
            ->whereNotNull('exam_part_id')
            ->firstOrFail();

        $validatedValue = $value === '' || $value === null ? null : filter_var($value, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        abort_if($value !== '' && $value !== null && $validatedValue === null, 422);
        abort_if($validatedValue !== null && $validatedValue < 0, 422);

        $mapping = ExamShrenyPartFmPm::query()
            ->where('shreny_id', $shrenyId)
            ->where('subject_id', $subjectId)
            ->where('exam_name_id', $configuration->exam_name_id)
            ->where('exam_type_id', $configuration->exam_type_id)
            ->where('exam_part_id', $configuration->exam_part_id)
            ->firstOrFail();

        $mapping->update([$field => $validatedValue]);
    }

    public function render()
    {
        $examNames = ExamName::query()->where('is_active', true)->orderBy('order_id')->orderBy('name')->get()->keyBy('id');
        $examTypes = ExamType::query()->where('is_active', true)->orderBy('order_id')->orderBy('name')->get()->keyBy('id');
        $examParts = ExamPart::query()->where('is_active', true)->orderBy('order_id')->orderBy('name')->get()->keyBy('id');
        $shrenies = Shreny::query()->where('is_active', true)->orderBy('order_id')->orderBy('name')->get();
        $subjects = Subject::query()->where('is_active', true)->orderBy('order_id')->orderBy('name')->get()->keyBy('id');
        $shrenySubjects = ExamShrenyPartFmPm::query()->whereNotNull('shreny_id')->whereNotNull('subject_id')
            ->get(['shreny_id', 'subject_id']);
        $shrenySubjects = $shrenySubjects->unique(fn ($row) => $row->shreny_id . ':' . $row->subject_id)->groupBy('shreny_id');
        $configurations = ExamShrenyPartFmPm::query()
            ->whereNull('shreny_id')->whereNull('subject_id')->whereNotNull('exam_part_id')
            ->orderBy('exam_name_id')->orderBy('exam_type_id')->orderBy('exam_part_id')->get();
        $marks = ExamShrenyPartFmPm::query()->whereNotNull('shreny_id')->whereNotNull('subject_id')->get()
            ->keyBy(fn ($row) => $row->shreny_id . ':' . $row->subject_id . ':' . $row->exam_name_id . ':' . $row->exam_type_id . ':' . $row->exam_part_id);

        return view('livewire.exam-marks-settings', compact('examNames', 'examTypes', 'examParts', 'shrenies', 'subjects', 'shrenySubjects', 'configurations', 'marks'));
    }
}