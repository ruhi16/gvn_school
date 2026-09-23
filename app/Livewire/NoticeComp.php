<?php

namespace App\Livewire;

use App\Livewire\Concerns\UsesActiveSchoolSession;
use App\Models\Notice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class NoticeComp extends Component
{
    use UsesActiveSchoolSession;
    use WithFileUploads;
    use WithPagination;

    public string $search = '';
    public string $title = '';
    public string $description = '';
    public string $upload_dt = '';
    public string $active_dt = '';
    public string $expiry_dt = '';
    public string $remarks = '';
    public $notice_img_ref;
    public $notice_pdf_ref;
    public ?int $recordId = null;
    public ?int $order_id = null;
    public ?int $school_id = null;
    public ?int $session_id = null;
    public bool $is_finalized = false;
    public bool $is_issued = false;
    public bool $is_active = true;
    public bool $showModal = false;

    protected $queryString = ['search'];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        if (!$this->canMutate()) {
            return;
        }

        $this->resetForm();
        $this->school_id = $this->activeSchoolId();
        $this->session_id = $this->activeSession()?->id;
        $this->upload_dt = now()->format('Y-m-d');
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        if (!$this->canMutate()) {
            return;
        }

        $notice = $this->noticeQuery()->findOrFail($id);
        $this->recordId = $notice->id;
        $this->title = $notice->title;
        $this->description = $notice->description ?? '';
        $this->upload_dt = $notice->upload_dt instanceof \DateTimeInterface ? $notice->upload_dt->format('Y-m-d') : '';
        $this->active_dt = $notice->active_dt instanceof \DateTimeInterface ? $notice->active_dt->format('Y-m-d') : '';
        $this->expiry_dt = $notice->expiry_dt instanceof \DateTimeInterface ? $notice->expiry_dt->format('Y-m-d') : '';
        $this->order_id = $notice->order_id;
        $this->school_id = $notice->school_id;
        $this->session_id = $notice->session_id;
        $this->is_finalized = (bool) $notice->is_finalized;
        $this->is_issued = (bool) $notice->is_issued;
        $this->is_active = (bool) $notice->is_active;
        $this->remarks = $notice->remarks ?? '';
        $this->resetValidation();
        $this->showModal = true;
    }

    public function save(): void
    {
        if (!$this->canMutate()) {
            return;
        }

        $editing = $this->recordId !== null;
        $data = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'upload_dt' => ['nullable', 'date'],
            'active_dt' => ['nullable', 'date'],
            'expiry_dt' => ['nullable', 'date', 'after_or_equal:active_dt'],
            'notice_img_ref' => ['nullable', 'image', 'max:5120'],
            'notice_pdf_ref' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'order_id' => ['nullable', 'integer'],
            'is_finalized' => ['boolean'],
            'is_active' => ['boolean'],
            'remarks' => ['nullable', 'string', 'max:255'],
        ]);

        $existing = $editing ? $this->noticeQuery()->findOrFail($this->recordId) : null;
        $data['school_id'] = $this->activeSchoolId();
        $data['session_id'] = $this->session_id ?: $this->activeSession()?->id;
        $data['uploaded_by'] = Auth::id();
        $data['is_issued'] = $this->shouldBeIssued($data['active_dt'] ?? null, $data['expiry_dt'] ?? null, (bool) $data['is_active']);

        if ($this->notice_img_ref) {
            if ($existing?->notice_img_ref) {
                Storage::disk('public')->delete($existing->notice_img_ref);
            }
            $data['notice_img_ref'] = $this->notice_img_ref->store('notices/images', 'public');
        } elseif ($editing) {
            unset($data['notice_img_ref']);
        }

        if ($this->notice_pdf_ref) {
            if ($existing?->notice_pdf_ref) {
                Storage::disk('public')->delete($existing->notice_pdf_ref);
            }
            $data['notice_pdf_ref'] = $this->notice_pdf_ref->store('notices/pdfs', 'public');
        } elseif ($editing) {
            unset($data['notice_pdf_ref']);
        }

        if (!$editing) {
            $data['notice_img_ref'] ??= null;
            $data['notice_pdf_ref'] ??= null;
        }

        Notice::updateOrCreate(
            ['id' => $this->recordId],
            $data,
        );

        $this->showModal = false;
        $this->resetForm();
        session()->flash('success', $editing ? 'Notice updated.' : 'Notice created.');
    }

    public function delete(int $id): void
    {
        if (!$this->canMutate()) {
            return;
        }

        $notice = $this->noticeQuery()->findOrFail($id);
        Storage::disk('public')->delete(array_filter([$notice->notice_img_ref, $notice->notice_pdf_ref]));
        $notice->delete();
        session()->flash('success', 'Notice deleted.');
    }

    private function noticeQuery()
    {
        return Notice::query()->where('school_id', $this->activeSchoolId());
    }

    private function shouldBeIssued(?string $activeDate, ?string $expiryDate, bool $isActive): bool
    {
        $today = now()->startOfDay();

        return $isActive
            && (!$activeDate || $activeDate <= $today->format('Y-m-d'))
            && (!$expiryDate || $expiryDate >= $today->format('Y-m-d'));
    }

    private function syncIssuedFlags(): void
    {
        $today = now()->toDateString();

        $this->noticeQuery()
            ->where('is_issued', '!=', false)
            ->where(function ($query) use ($today) {
                $query->where('is_active', false)
                    ->orWhere('active_dt', '>', $today)
                    ->orWhere('expiry_dt', '<', $today);
            })
            ->update(['is_issued' => false]);

        $this->noticeQuery()
            ->where('is_active', true)
            ->where(function ($query) use ($today) {
                $query->whereNull('active_dt')->orWhere('active_dt', '<=', $today);
            })
            ->where(function ($query) use ($today) {
                $query->whereNull('expiry_dt')->orWhere('expiry_dt', '>=', $today);
            })
            ->update(['is_issued' => true]);
    }

    private function resetForm(): void
    {
        $this->reset([
            'recordId', 'title', 'description', 'upload_dt', 'active_dt', 'expiry_dt',
            'remarks', 'notice_img_ref', 'notice_pdf_ref', 'order_id', 'school_id', 'session_id',
        ]);
        $this->is_finalized = false;
        $this->is_issued = false;
        $this->is_active = true;
        $this->resetValidation();
    }

    public function render()
    {
        $this->syncIssuedFlags();

        $notices = $this->noticeQuery()
            ->when($this->search, fn ($query) => $query->where(function ($query) {
                $query->where('title', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%");
            }))
            ->orderByDesc('upload_dt')
            ->orderByDesc('id')
            ->paginate(10);

        return view('livewire.notice-comp', compact('notices'));
    }
}
