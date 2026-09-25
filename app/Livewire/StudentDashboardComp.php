<?php

namespace App\Livewire;

use App\Models\ExamMarksEntry;
use App\Models\ExamName;
use App\Models\Notice;
use App\Models\School;
use App\Models\Section;
use App\Models\Session;
use App\Models\Shreny;
use App\Models\StudentCr;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class StudentDashboardComp extends Component
{
    use WithFileUploads;

    public string $activePanel = 'overview';
    public string $mobile_1 = '';
    public string $mobile_2 = '';
    public string $email = '';
    public string $village = '';
    public string $post_office = '';
    public string $police_station = '';
    public string $district = '';
    public string $block = '';
    public string $pincode = '';
    public string $state = '';
    public string $nationality = '';
    public $profileImage;
    public bool $showProfileEditor = false;

    public function mount(string $activePanel = 'overview'): void
    {
        $this->activePanel = $activePanel;
        $this->loadProfileFields();
    }

    public function selectPanel(string $panel): void
    {
        $this->activePanel = $panel;
    }

    public function openProfileEditor(): void
    {
        $this->loadProfileFields();
        $this->resetValidation();
        $this->showProfileEditor = true;
    }

    public function saveProfile(): void
    {
        $student = Auth::user()?->student;
        if (!$student) {
            $this->addError('profile', 'Your student profile is not linked yet.');
            return;
        }

        $data = $this->validate([
            'mobile_1' => ['nullable', 'string', 'max:255'],
            'mobile_2' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'village' => ['nullable', 'string', 'max:255'],
            'post_office' => ['nullable', 'string', 'max:255'],
            'police_station' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'block' => ['nullable', 'string', 'max:255'],
            'pincode' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'nationality' => ['nullable', 'string', 'max:255'],
            'profileImage' => ['nullable', 'image', 'max:5120'],
        ]);

        unset($data['profileImage']);
        $student->update($data);

        if ($this->profileImage) {
            if ($student->dp_img_ref) {
                Storage::disk('public')->delete($student->dp_img_ref);
            }
            $student->dp_img_ref = $this->profileImage->store('students/' . $student->id, 'public');
            $student->save();
        }

        $this->profileImage = null;
        $this->showProfileEditor = false;
        session()->flash('success', 'Your profile details were updated.');
    }

    private function loadProfileFields(): void
    {
        $student = Auth::user()?->student;
        if (!$student) {
            return;
        }

        foreach (['mobile_1', 'mobile_2', 'email', 'village', 'post_office', 'police_station', 'district', 'block', 'pincode', 'state', 'nationality'] as $field) {
            $this->{$field} = (string) ($student->{$field} ?? '');
        }
    }

    public function render()
    {
        $user = Auth::user();
        $student = $user?->student;

        if (!$student) {
            return view('livewire.student-dashboard-comp', [
                'student' => null,
                'school' => null,
                'session' => null,
                'classRecords' => collect(),
                'marksTotal' => 0,
                'examCount' => 0,
                'subjectCount' => 0,
                'notices' => collect(),
            ]);
        }

        $schoolId = $student->school_id ?: $user?->school_id;
        $session = Session::query()
            ->where('school_id', $schoolId)
            ->where(function ($query) use ($student) {
                $query->where('id', $student->session_id)->orWhere('is_active', true);
            })
            ->latest('id')
            ->first();
        $classRecords = StudentCr::query()
            ->with(['shreny', 'section'])
            ->where('studentdb_id', $student->id)
            ->where('is_active', true)
            ->latest('id')
            ->get();
        $classRecordIds = $classRecords->pluck('id');
        $scoped = fn (string $model) => $model::query()->where('school_id', $schoolId)->when($session, fn ($query) => $query->where('session_id', $session->id));

        return view('livewire.student-dashboard-comp', [
            'student' => $student,
            'school' => $schoolId ? School::find($schoolId) : null,
            'session' => $session,
            'classRecords' => $classRecords,
            'marksTotal' => $classRecordIds->isEmpty() ? 0 : ExamMarksEntry::query()->whereIn('student_cr_id', $classRecordIds)->where('is_active', true)->count(),
            'examCount' => $scoped(ExamName::class)->where('is_active', true)->count(),
            'subjectCount' => $scoped(Subject::class)->where('is_active', true)->count(),
            'notices' => $scoped(Notice::class)->where('is_active', true)->latest('upload_dt')->latest('id')->limit(5)->get(),
        ]);
    }
}