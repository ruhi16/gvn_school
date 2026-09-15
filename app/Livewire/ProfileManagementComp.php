<?php

namespace App\Livewire;

use App\Enums\UserRole;
use App\Models\StudentDb;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ProfileManagementComp extends Component
{
    public ?int $selectedUserId = null;
    public ?int $assignmentUserId = null;
    public ?int $assignmentTeacherId = null;
    public string $assignmentRole = '';
    public string $studentDobInput = '';
    public string $newPassword = '';
    public string $newPasswordConfirmation = '';
    public bool $showPasswordModal = false;
    public bool $showRoleModal = false;
    public bool $showStudentModal = false;
    public array $roles = [];
    public array $teacherSelections = [];
    public array $studentDob = [];
    public array $studentSelections = [];

    public function openRoleModal(int $userId): void
    {
        $user = User::findOrFail($userId);
        $this->assignmentUserId = $userId;
        $this->assignmentRole = $user->role === UserRole::VISITOR ? '' : $user->role;
        $this->assignmentTeacherId = $user->teacher_id;
        $this->resetValidation();
        $this->showRoleModal = true;
    }

    public function openStudentModal(int $userId): void
    {
        $user = User::findOrFail($userId);
        $this->assignmentUserId = $userId;
        $this->studentDobInput = $user->student?->dob?->format('Y-m-d') ?? '';
        $this->studentSelections[$userId] = $user->student_id;
        $this->resetValidation();
        $this->showStudentModal = true;
    }

    public function assignRole(): void
    {
        $userId = $this->assignmentUserId;
        $user = User::findOrFail($userId);
        $role = $this->assignmentRole;

        validator(['role' => $role], [
            'role' => ['required', Rule::in([UserRole::ADMIN, UserRole::TEACHER, UserRole::STAFF])],
        ])->validate();

        $teacherId = null;
        if ($role === UserRole::TEACHER) {
            $teacherId = (int) ($this->assignmentTeacherId ?? 0);
            validator(['teacher_id' => $teacherId], [
                'teacher_id' => ['required', 'integer', Rule::exists('teachers', 'id')],
            ])->validate();

            if (User::where('teacher_id', $teacherId)->where('id', '!=', $user->id)->exists()) {
                $this->addError('assignmentTeacherId', 'This teacher is already assigned to another user.');
                return;
            }
        }

        $user->role = $role;
        $user->teacher_id = $teacherId;
        $user->student_id = null;
        $user->save();
        $this->showRoleModal = false;
        $this->assignmentUserId = null;
        $this->assignmentTeacherId = null;
        $this->assignmentRole = '';
        session()->flash('success', 'User role assigned.');
    }

    public function clearRole(int $userId): void
    {
        $user = User::findOrFail($userId);
        $user->role = UserRole::VISITOR;
        $user->teacher_id = null;
        $user->save();
        $this->roles[$userId] = UserRole::VISITOR;
        unset($this->teacherSelections[$userId]);
        session()->flash('success', 'Role assignment removed.');
    }

    public function assignStudent(int $userId): void
    {
        $user = User::findOrFail($userId);
        $dob = $userId === $this->assignmentUserId
            ? $this->studentDobInput
            : ($this->studentDob[$userId] ?? null);
        $studentId = (int) ($this->studentSelections[$userId] ?? 0);

        validator(['dob' => $dob], ['dob' => ['required', 'date']])->validate();
        $student = StudentDb::query()
            ->whereKey($studentId)
            ->whereDate('dob', $dob)
            ->first();

        if (!$student) {
            $this->addError("studentSelections.{$userId}", 'Select a student whose date of birth matches.');
            return;
        }

        if (User::where('student_id', $student->id)->where('id', '!=', $user->id)->exists()) {
            $this->addError("studentSelections.{$userId}", 'This student is already assigned to another user.');
            return;
        }

        $user->role = UserRole::STUDENT;
        $user->student_id = $student->id;
        $user->teacher_id = null;
        $user->save();
        session()->flash('success', 'Student profile assigned.');
    }

    public function assignSelectedStudent(): void
    {
        $this->assignStudent($this->assignmentUserId);
        if (!$this->getErrorBag()->isNotEmpty()) {
            $this->showStudentModal = false;
            $this->assignmentUserId = null;
            $this->studentDobInput = '';
        }
    }

    public function removeStudent(int $userId): void
    {
        $user = User::findOrFail($userId);
        $user->student_id = null;
        $user->save();
        unset($this->studentSelections[$userId], $this->studentDob[$userId]);
        session()->flash('success', 'Student profile removed.');
    }

    public function openPasswordModal(int $userId): void
    {
        $this->selectedUserId = $userId;
        $this->newPassword = '';
        $this->newPasswordConfirmation = '';
        $this->resetValidation();
        $this->showPasswordModal = true;
    }

    public function updatePassword(): void
    {
        $this->validate([
            'newPassword' => ['required', 'string', 'min:8', 'same:newPasswordConfirmation'],
        ]);

        User::findOrFail($this->selectedUserId)->update([
            'password' => Hash::make($this->newPassword),
        ]);
        $this->showPasswordModal = false;
        session()->flash('success', 'Password changed.');
    }

    public function deleteUser(int $userId): void
    {
        User::findOrFail($userId)->delete();
        unset($this->roles[$userId], $this->teacherSelections[$userId], $this->studentDob[$userId], $this->studentSelections[$userId]);
        session()->flash('success', 'User deleted.');
    }

    public function render()
    {
        $users = User::query()->where('role', '!=', UserRole::STUDENT)->latest()->get();
        $students = User::query()->where('role', UserRole::STUDENT)->latest()->get();
        $assignedTeacherIds = User::query()->whereNotNull('teacher_id')->pluck('teacher_id');
        $assignedStudentIds = User::query()->whereNotNull('student_id')->pluck('student_id');
        $currentTeacherId = $this->assignmentUserId ? User::find($this->assignmentUserId)?->teacher_id : null;
        $teachers = Teacher::query()
            ->where(function ($query) use ($assignedTeacherIds, $currentTeacherId) {
                $query->whereNotIn('id', $assignedTeacherIds);
                if ($currentTeacherId) {
                    $query->orWhere('id', $currentTeacherId);
                }
            })
            ->orderBy('name')
            ->get(['id', 'name']);
        $studentProfiles = StudentDb::query()
            ->whereNotIn('id', $assignedStudentIds)
            ->orderBy('name')
            ->get(['id', 'name', 'fname', 'dob', 'gender', 'mobile_1']);
        $assignedProfiles = StudentDb::query()
            ->whereIn('id', $students->pluck('student_id')->filter())
            ->get(['id', 'name', 'fname', 'dob', 'gender', 'mobile_1'])
            ->keyBy('id');

        foreach ($users as $user) {
            $this->roles[$user->id] ??= $user->role;
        }

        $studentMatches = collect();
        if ($this->studentDobInput !== '') {
            $studentMatches = StudentDb::query()
                ->whereDate('dob', $this->studentDobInput)
                ->where(function ($query) use ($assignedStudentIds) {
                    $query->whereNotIn('id', $assignedStudentIds)
                        ->orWhere('id', $this->studentSelections[$this->assignmentUserId] ?? 0);
                })
                ->orderBy('name')
                ->get();
        }

        return view('livewire.profile-management-comp', compact('users', 'students', 'teachers', 'studentProfiles', 'assignedProfiles', 'studentMatches'));
    }
}