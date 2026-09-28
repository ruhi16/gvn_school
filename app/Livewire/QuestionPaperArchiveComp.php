<?php

namespace App\Livewire;

use App\Livewire\Concerns\UsesActiveSchoolSession;
use App\Models\ExamName;
use App\Models\ExamPart;
use App\Models\ExamShrenyPartFmPm;
use App\Models\ExamType;
use App\Models\QuestionArchive;
use App\Models\Shreny;
use App\Models\Subject;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class QuestionPaperArchiveComp extends Component
{
    use UsesActiveSchoolSession;
    use WithFileUploads;

    public ?int $selectedShrenyId = null;
    public string $selectedCombinationKey = '';
    public ?int $selectedSubjectId = null;
    public ?int $recordId = null;
    public string $name = '';
    public string $description = '';
    public string $remarks = '';
    public $pdf;
    public $image;
    public bool $is_active = true;
    public bool $showModal = false;

    public function mount(): void
    {
        $this->selectedShrenyId = $this->shrenies()->first()?->id;
    }

    public function selectShreny(int $id): void
    {
        abort_unless($this->shrenies()->contains('id', $id), 404);
        $this->selectedShrenyId = $id;
        $this->selectedCombinationKey = '';
        $this->selectedSubjectId = null;
        $this->resetValidation();
    }

    public function updatedSelectedCombinationKey(): void
    {
        $this->selectedSubjectId = null;
    }

    public function create(): void
    {
        if (!$this->canMutate()) {
            return;
        }
        if (!$this->activeSession()) {
            session()->flash('error', 'Configure an active school session before managing question papers.');
            return;
        }

        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        if (!$this->canMutate()) {
            return;
        }

        $archive = $this->archiveQuery()->findOrFail($id);
        $this->recordId = $archive->id;
        $this->selectedShrenyId = $archive->shreny_id;
        $this->selectedCombinationKey = implode(':', [$archive->exam_name_id, $archive->exam_type_id, $archive->exam_part_id]);
        $this->selectedSubjectId = $archive->subject_id;
        $this->name = $archive->name;
        $this->description = $archive->description ?? '';
        $this->remarks = $archive->remarks ?? '';
        $this->is_active = (bool) $archive->is_active;
        $this->resetValidation();
        $this->showModal = true;
    }

    public function save(): void
    {
        if (!$this->canMutate()) {
            return;
        }
        $session = $this->activeSession();
        if (!$session) {
            session()->flash('error', 'Configure an active school session before managing question papers.');
            return;
        }

        $data = $this->validate([
            'selectedShrenyId' => ['required', 'integer'],
            'selectedCombinationKey' => ['required', 'string'],
            'selectedSubjectId' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'remarks' => ['nullable', 'string', 'max:255'],
            'pdf' => ['nullable', 'file', 'mimes:pdf', 'max:20480'],
            'image' => ['nullable', 'image', 'max:10240'],
            'is_active' => ['boolean'],
        ]);

        if (!$this->pdf && !$this->image && !$this->recordId) {
            $this->addError('pdf', 'Upload a PDF or an image of the question paper.');
            return;
        }

        [$examNameId, $examTypeId, $examPartId] = $this->combinationIds();
        $configuration = $this->subjectConfiguration($examNameId, $examTypeId, $examPartId)
            ->where('subject_id', $this->selectedSubjectId)->first();
        abort_unless($configuration !== null, 422, 'The selected subject is not assigned to this exam combination and class.');
        abort_unless($this->shrenies()->contains('id', $this->selectedShrenyId), 422);

        $editing = $this->recordId !== null;
        $archive = $editing ? $this->archiveQuery()->findOrFail($this->recordId) : new QuestionArchive();
        $attributes = [
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'exam_name_id' => $examNameId,
            'exam_type_id' => $examTypeId,
            'exam_part_id' => $examPartId,
            'shreny_id' => $this->selectedShrenyId,
            'subject_id' => $this->selectedSubjectId,
            'school_id' => $this->activeSchoolId(),
            'session_id' => $session->id,
            'is_active' => $data['is_active'],
            'is_deleted' => false,
            'remarks' => $data['remarks'] ?? null,
        ];

        if ($this->pdf) {
            $newPath = $this->pdf->store('question-papers/pdfs', 'public');
            if ($archive->question_paper_pdf_ref) {
                Storage::disk('public')->delete($archive->question_paper_pdf_ref);
            }
            $attributes['question_paper_pdf_ref'] = $newPath;
        }
        if ($this->image) {
            $newPath = $this->image->store('question-papers/images', 'public');
            if ($archive->question_paper_img_ref) {
                Storage::disk('public')->delete($archive->question_paper_img_ref);
            }
            $attributes['question_paper_img_ref'] = $newPath;
        }

        $archive->fill($attributes)->save();
        $this->showModal = false;
        $this->resetForm();
        session()->flash('success', $editing ? 'Question paper updated.' : 'Question paper added.');
    }

    public function toggleActive(int $id): void
    {
        if (!$this->canMutate()) {
            return;
        }

        $archive = $this->archiveQuery()->findOrFail($id);
        $archive->update(['is_active' => !$archive->is_active]);
    }

    public function delete(int $id): void
    {
        if (!$this->canMutate()) {
            return;
        }

        $archive = $this->archiveQuery()->findOrFail($id);
        Storage::disk('public')->delete(array_filter([$archive->question_paper_pdf_ref, $archive->question_paper_img_ref]));
        $archive->delete();
        session()->flash('success', 'Question paper deleted.');
    }

    private function resetForm(): void
    {
        $this->reset(['recordId', 'selectedCombinationKey', 'selectedSubjectId', 'name', 'description', 'remarks', 'pdf', 'image']);
        $this->is_active = true;
        $this->resetValidation();
    }

    private function combinationIds(): array
    {
        $ids = array_map('intval', explode(':', $this->selectedCombinationKey));
        abort_unless(count($ids) === 3 && !in_array(0, $ids, true), 422);

        return $ids;
    }

    private function shrenies()
    {
        $sessionId = $this->activeSession()?->id;

        return Shreny::query()->where('is_active', true)
            ->where(function ($query) use ($sessionId) {
                $query->whereNull('session_id')->orWhere('session_id', $sessionId);
            })->orderBy('order_id')->orderBy('name')->get();
    }

    private function subjectConfiguration(int $examNameId, int $examTypeId, int $examPartId)
    {
        return ExamShrenyPartFmPm::query()->where('is_active', true)
            ->where('shreny_id', $this->selectedShrenyId)->whereNotNull('subject_id')
            ->where('exam_name_id', $examNameId)->where('exam_type_id', $examTypeId)->where('exam_part_id', $examPartId)
            ->where(fn ($query) => $query->whereNull('school_id')->orWhere('school_id', $this->activeSchoolId()))
            ->where(fn ($query) => $query->whereNull('session_id')->orWhere('session_id', $this->activeSession()?->id));
    }

    private function archiveQuery()
    {
        return QuestionArchive::query()->where('school_id', $this->activeSchoolId())
            ->where('session_id', $this->activeSession()?->id)->where('is_deleted', false);
    }

    public function render()
    {
        $session = $this->activeSession();
        $shrenies = $this->shrenies();
        $selectedShreny = $shrenies->firstWhere('id', $this->selectedShrenyId);
        $subjectConfigurations = $selectedShreny
            ? ExamShrenyPartFmPm::query()->where('is_active', true)->where('shreny_id', $selectedShreny->id)
                ->whereNotNull('subject_id')
                ->where(fn ($query) => $query->whereNull('school_id')->orWhere('school_id', $this->activeSchoolId()))
                ->where(fn ($query) => $query->whereNull('session_id')->orWhere('session_id', $session?->id))
                ->orderBy('exam_name_id')->orderBy('exam_type_id')->orderBy('exam_part_id')->get()
            : collect();
        $combinations = $subjectConfigurations->unique(fn ($row) => $row->exam_name_id . ':' . $row->exam_type_id . ':' . $row->exam_part_id)->values();
        $selectedIds = preg_match('/^\d+:\d+:\d+$/', $this->selectedCombinationKey)
            ? array_map('intval', explode(':', $this->selectedCombinationKey)) : [];
        $selectedConfigurations = $selectedIds ? $this->subjectConfiguration(...$selectedIds)->get() : collect();
        $subjects = Subject::query()->where('is_active', true)->whereIn('id', $selectedConfigurations->pluck('subject_id'))->orderBy('order_id')->orderBy('name')->get();
        $examNames = ExamName::query()->get()->keyBy('id');
        $examTypes = ExamType::query()->get()->keyBy('id');
        $examParts = ExamPart::query()->get()->keyBy('id');
        $archives = $selectedShreny && $session
            ? $this->archiveQuery()->where('shreny_id', $selectedShreny->id)->with(['subject', 'examName', 'examType', 'examPart'])->orderBy('exam_name_id')->orderBy('exam_type_id')->orderBy('exam_part_id')->orderBy('subject_id')->orderByDesc('id')->get()
            : collect();

        return view('livewire.question-paper-archive-comp', compact('session', 'shrenies', 'selectedShreny', 'combinations', 'subjects', 'examNames', 'examTypes', 'examParts', 'archives'));
    }
}