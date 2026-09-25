<?php

namespace App\Livewire;

use App\Models\ExamGrade;
use App\Models\ExamMarksEntry;
use App\Models\ExamMode;
use App\Models\ExamName;
use App\Models\ExamPart;
use App\Models\ExamScriptDistribution;
use App\Models\ExamType;
use App\Models\Notice;
use App\Models\School;
use App\Models\Section;
use App\Models\Session;
use App\Models\Shreny;
use App\Models\StudentCr;
use App\Models\StudentDb;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class TeacherDashboardComp extends Component
{
    use WithFileUploads;

    public string $activePanel = 'overview';
    public string $mobile = '';
    public string $dob = '';
    public string $gender = '';
    public string $high_qual_subject = '';
    public string $prof_qual_subject = '';
    public string $vill = '';
    public string $post_office = '';
    public string $police_station = '';
    public string $district = '';
    public string $block = '';
    public string $pincode = '';
    public string $remarks = '';
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
        $teacher = Auth::user()?->teacher;
        if (!$teacher) {
            $this->addError('profile', 'Your teacher profile is not linked yet.');
            return;
        }

        $data = $this->validate([
            'mobile' => ['nullable', 'string', 'max:255'],
            'dob' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:50'],
            'high_qual_subject' => ['nullable', 'string', 'max:255'],
            'prof_qual_subject' => ['nullable', 'string', 'max:255'],
            'vill' => ['nullable', 'string', 'max:255'],
            'post_office' => ['nullable', 'string', 'max:255'],
            'police_station' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'block' => ['nullable', 'string', 'max:255'],
            'pincode' => ['nullable', 'string', 'max:255'],
            'remarks' => ['nullable', 'string', 'max:255'],
            'profileImage' => ['nullable', 'image', 'max:5120'],
        ]);

        unset($data['profileImage']);
        $teacher->update($data);

        if ($this->profileImage) {
            if ($teacher->prof_img_ref) {
                Storage::disk('public')->delete($teacher->prof_img_ref);
            }
            $teacher->prof_img_ref = $this->profileImage->store('teachers/' . $teacher->id, 'public');
            $teacher->save();
        }

        $this->profileImage = null;
        $this->showProfileEditor = false;
        session()->flash('success', 'Your profile details were updated.');
    }

    private function loadProfileFields(): void
    {
        $teacher = Auth::user()?->teacher;
        if (!$teacher) {
            return;
        }

        foreach (['mobile', 'gender', 'high_qual_subject', 'prof_qual_subject', 'vill', 'post_office', 'police_station', 'district', 'block', 'pincode', 'remarks'] as $field) {
            $this->{$field} = (string) ($teacher->{$field} ?? '');
        }
        $this->dob = $teacher->dob ? (string) $teacher->dob : '';
    }

    public function render()
    {
        $user = Auth::user();
        $schoolId = $user?->school_id;
        $session = Session::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->latest('id')
            ->first();

        $scoped = function (string $model) use ($schoolId, $session) {
            $query = $model::query()->where('school_id', $schoolId);

            return $session ? $query->where('session_id', $session->id) : $query;
        };

        $active = fn ($query) => $query->where('is_active', true);

        return view('livewire.teacher-dashboard-comp', [
            'teacher' => $user?->teacher,
            'school' => $schoolId ? School::find($schoolId) : null,
            'session' => $session,
            'totalStudents' => $active($scoped(StudentDb::class))->count(),
            'totalClassRecords' => $active($scoped(StudentCr::class))->count(),
            'totalShrenies' => $active($scoped(Shreny::class))->count(),
            'totalSections' => $active($scoped(Section::class))->count(),
            'totalSubjects' => $active($scoped(Subject::class))->count(),
            'examCounts' => [
                'Exams' => $active($scoped(ExamName::class))->count(),
                'Types' => $active($scoped(ExamType::class))->count(),
                'Parts' => $active($scoped(ExamPart::class))->count(),
                'Modes' => $active($scoped(ExamMode::class))->count(),
                'Grades' => $active($scoped(ExamGrade::class))->count(),
            ],
            'marksTotal' => $active($scoped(ExamMarksEntry::class))->count(),
            'marksFinalized' => $active($scoped(ExamMarksEntry::class))->where('is_finalized', true)->count(),
            'marksIssued' => $active($scoped(ExamMarksEntry::class))->where('is_issued', true)->count(),
            'scriptAssignments' => $active($scoped(ExamScriptDistribution::class))
                ->where('teacher_id', $user?->teacher_id)
                ->latest('id')
                ->limit(6)
                ->get(),
            'notices' => $active($scoped(Notice::class))
                ->latest('upload_dt')
                ->latest('id')
                ->limit(5)
                ->get(),
        ]);
    }
}