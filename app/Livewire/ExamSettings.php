<?php

namespace App\Livewire;

use App\Livewire\Concerns\UsesActiveSchoolSession;
use App\Models\ExamMode;
use App\Models\ExamName;
use App\Models\ExamPart;
use App\Models\ExamShrenyPartFmPm;
use App\Models\ExamType;
use App\Models\Shreny;
use App\Models\ShrenySubject;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ExamSettings extends Component
{
    use UsesActiveSchoolSession;

    public function toggleExamType(int $examNameId, int $examTypeId): void
    {
        if (!$this->canMutate()) return;
        $query = ExamShrenyPartFmPm::query()
            ->whereNull('shreny_id')
            ->whereNull('subject_id')
            ->where('exam_name_id', $examNameId)
            ->where('exam_type_id', $examTypeId);

        if ($query->whereNull('exam_part_id')->exists()) {
            $this->deleteConfiguration($examNameId, $examTypeId);
            return;
        }

        ExamShrenyPartFmPm::create([
            'name' => 'Exam type configuration',
            'exam_name_id' => $examNameId,
            'exam_type_id' => $examTypeId,
            'is_active' => true,
        ]);
    }

    public function toggleExamPart(int $examNameId, int $examTypeId, int $examPartId): void
    {
        if (!$this->canMutate()) return;
        $this->ensureExamType($examNameId, $examTypeId);

        $configuration = $this->globalConfiguration($examNameId, $examTypeId, $examPartId);
        if ($configuration) {
            $configuration->delete();
            $this->subjectConfigurations($examNameId, $examTypeId, $examPartId)->delete();
            return;
        }

        ExamShrenyPartFmPm::create([
            'name' => 'Exam part configuration',
            'exam_name_id' => $examNameId,
            'exam_type_id' => $examTypeId,
            'exam_part_id' => $examPartId,
            'is_active' => true,
        ]);
    }

    public function setExamMode(int $configurationId, int $examModeId): void
    {
        if (!$this->canMutate()) return;
        $configuration = ExamShrenyPartFmPm::query()
            ->whereKey($configurationId)
            ->whereNull('shreny_id')
            ->whereNull('subject_id')
            ->firstOrFail();

        $configuration->update(['exam_mode_id' => $examModeId]);
    }

    public function toggleSubject(int $configurationId, int $shrenyId, int $subjectId): void
    {
        if (!$this->canMutate()) return;
        $configuration = ExamShrenyPartFmPm::query()
            ->whereKey($configurationId)
            ->whereNull('shreny_id')
            ->whereNull('subject_id')
            ->firstOrFail();

        $mapping = ExamShrenyPartFmPm::query()
            ->where('shreny_id', $shrenyId)
            ->where('subject_id', $subjectId)
            ->where('exam_name_id', $configuration->exam_name_id)
            ->where('exam_type_id', $configuration->exam_type_id)
            ->where('exam_part_id', $configuration->exam_part_id)
            ->first();

        if ($mapping) {
            $mapping->delete();
            return;
        }

        ExamShrenyPartFmPm::create([
            'name' => 'Shreny subject configuration',
            'shreny_id' => $shrenyId,
            'exam_name_id' => $configuration->exam_name_id,
            'exam_type_id' => $configuration->exam_type_id,
            'exam_part_id' => $configuration->exam_part_id,
            'exam_mode_id' => $configuration->exam_mode_id,
            'subject_id' => $subjectId,
            'is_active' => true,
        ]);
    }

    public function toggleShrenySubject(int $shrenyId, int $subjectId): void
    {
        if (!$this->canMutate()) return;
        ShrenySubject::query()->firstOrCreate([
            'shreny_id' => $shrenyId,
            'subject_id' => $subjectId,
        ], [
            'is_active' => true,
        ]);

        $configurations = ExamShrenyPartFmPm::query()
            ->whereNull('shreny_id')
            ->whereNull('subject_id')
            ->whereNotNull('exam_part_id')
            ->get();

        $allSelected = $configurations->isNotEmpty()
            && $configurations->every(fn($configuration) => ExamShrenyPartFmPm::query()
                ->where('shreny_id', $shrenyId)
                ->where('subject_id', $subjectId)
                ->where('exam_name_id', $configuration->exam_name_id)
                ->where('exam_type_id', $configuration->exam_type_id)
                ->where('exam_part_id', $configuration->exam_part_id)
                ->exists());

        if ($allSelected) {
            ExamShrenyPartFmPm::query()
                ->where('shreny_id', $shrenyId)
                ->where('subject_id', $subjectId)
                ->delete();
            return;
        }

        foreach ($configurations as $configuration) {
            ExamShrenyPartFmPm::firstOrCreate([
                'shreny_id' => $shrenyId,
                'subject_id' => $subjectId,
                'exam_name_id' => $configuration->exam_name_id,
                'exam_type_id' => $configuration->exam_type_id,
                'exam_part_id' => $configuration->exam_part_id,
            ], [
                'name' => 'Shreny subject configuration',
                'exam_mode_id' => $configuration->exam_mode_id,
                'is_active' => true,
            ]);
        }
    }

    private function ensureExamType(int $examNameId, int $examTypeId): void
    {
        if (
            !ExamShrenyPartFmPm::query()
                ->whereNull('shreny_id')->whereNull('subject_id')
                ->where('exam_name_id', $examNameId)->where('exam_type_id', $examTypeId)
                ->whereNull('exam_part_id')->exists()
        ) {
            ExamShrenyPartFmPm::create([
                'name' => 'Exam type configuration',
                'exam_name_id' => $examNameId,
                'exam_type_id' => $examTypeId,
                'is_active' => true,
            ]);
        }
    }

    private function globalConfiguration(int $examNameId, int $examTypeId, int $examPartId): ?ExamShrenyPartFmPm
    {
        return ExamShrenyPartFmPm::query()
            ->whereNull('shreny_id')->whereNull('subject_id')
            ->where('exam_name_id', $examNameId)->where('exam_type_id', $examTypeId)
            ->where('exam_part_id', $examPartId)->first();
    }

    private function subjectConfigurations(int $examNameId, int $examTypeId, int $examPartId)
    {
        return ExamShrenyPartFmPm::query()
            ->where('exam_name_id', $examNameId)->where('exam_type_id', $examTypeId)
            ->where('exam_part_id', $examPartId)->whereNotNull('shreny_id');
    }

    private function deleteConfiguration(int $examNameId, int $examTypeId): void
    {
        DB::transaction(function () use ($examNameId, $examTypeId): void {
            ExamShrenyPartFmPm::query()
                ->where('exam_name_id', $examNameId)->where('exam_type_id', $examTypeId)
                ->delete();
        });
    }

    public function render()
    {
        $examNames = ExamName::query()->where('is_active', true)->orderBy('order_id')->orderBy('name')->get();
        $examTypes = ExamType::query()->where('is_active', true)->orderBy('order_id')->orderBy('name')->get();
        $examParts = ExamPart::query()->where('is_active', true)->orderBy('order_id')->orderBy('name')->get();
        $examModes = ExamMode::query()->where('is_active', true)->orderBy('order_id')->orderBy('name')->get();
        $shrenies = Shreny::query()->where('is_active', true)->orderBy('order_id')->orderBy('name')->get();
        $subjects = Subject::query()->where('is_active', true)->orderBy('order_id')->orderBy('name')->get();

        $configurations = ExamShrenyPartFmPm::query()
            ->whereNull('shreny_id')->whereNull('subject_id')->whereNotNull('exam_part_id')
            ->orderBy('exam_name_id')->orderBy('exam_type_id')->orderBy('exam_part_id')->get();
        $typeIds = ExamShrenyPartFmPm::query()->whereNull('shreny_id')->whereNull('subject_id')
            ->whereNull('exam_part_id')->get()->groupBy('exam_name_id')->map(fn($rows) => $rows->pluck('exam_type_id')->map(fn($id) => (int) $id)->all())->all();
        $selectedParts = $configurations->groupBy(fn($row) => $row->exam_name_id . ':' . $row->exam_type_id)
            ->map(fn($rows) => $rows->keyBy('exam_part_id'))->all();
        $assignedSubjects = ExamShrenyPartFmPm::query()->whereNotNull('shreny_id')->whereNotNull('subject_id')->get()
            ->groupBy(fn($row) => $row->shreny_id . ':' . $row->exam_name_id . ':' . $row->exam_type_id . ':' . $row->exam_part_id)->all();
        $shrenySubjects = ShrenySubject::query()
            ->where('is_active', true)
            ->whereIn('shreny_id', $shrenies->pluck('id'))
            ->whereIn('subject_id', $subjects->pluck('id'))
            ->get()
            ->groupBy('shreny_id')
            ->map(fn($mappings) => $mappings->sortBy(function ($mapping) use ($subjects) {
                $subject = $subjects->firstWhere('id', $mapping->subject_id);

                return [
                    $subject?->subject_type_id ?? PHP_INT_MAX,
                    $subject?->order_id ?? PHP_INT_MAX,
                    $subject?->name ?? '',
                ];
            }));

        return view('livewire.exam-settings', compact('examNames', 'examTypes', 'examParts', 'examModes', 'shrenies', 'subjects', 'configurations', 'typeIds', 'selectedParts', 'assignedSubjects', 'shrenySubjects'));
    }
}