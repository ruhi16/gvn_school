@php
$panelTitles = [
'overview' => 'Overview', 'students' => 'Student admissions', 'student-crs' => 'Shreny & Section students',
'school' => 'School', 'session' => 'Sessions', 'shreny' => 'Shrenies', 'section' => 'Sections',
'subject' => 'Subjects', 'teacher' => 'Teachers', 'shreny-sections' => 'Shreny-Sections',
'shreny-subjects' => 'Shreny-Subjects', 'exam-overview' => 'Exam overview', 'exam-basics' => 'Exam basic settings',
'exam-names' => 'Exam names', 'exam-types' => 'Exam types', 'exam-parts' => 'Exam parts', 'exam-modes' => 'Exam modes',
'exam-grades' => 'Exam grades', 'exam-combinations' => 'Exam combination settings', 'exam-marks-settings' => 'Marks
settings',
'exam-marks-entry' => 'Marks entry', 'exam-marks-register' => 'Marks register', 'exam-marks-sheets' => 'Marks sheets',
'exam-script-distribution' => 'Script distribution', 'notices' => 'Notices', 'user-profile' => 'User Profile',
];
@endphp
<div class="min-h-[calc(100vh-8rem)] bg-slate-100 text-slate-900">
    <div class="mx-auto flex max-w-[1600px] overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <aside class="w-60 shrink-0 border-r border-slate-200 bg-slate-950 text-slate-300">
            <div class="flex h-14 items-center gap-2 border-b border-slate-800 px-4">
                <div class="grid h-7 w-7 place-items-center rounded bg-cyan-400 text-xs font-bold text-slate-950">GV
                </div>
                <div>
                    <p class="text-xs font-semibold tracking-wide text-white">GVN SCHOOL</p>
                    <p class="text-[10px] text-slate-500">ADMIN CONSOLE</p>
                </div>
            </div>
            <nav class="space-y-1 p-3 text-xs">
                <button wire:click="selectPanel('overview')"
                    class="w-full rounded px-3 py-2 text-left {{ $activePanel === 'overview' ? 'bg-cyan-950 font-semibold text-cyan-300' : 'hover:bg-slate-900' }}">⌂
                    <span>General overview</span></button>

                <p class="px-3 pb-1 pt-5 text-[10px] font-bold uppercase tracking-widest text-slate-600">Students</p>
                @foreach ([['students', 'StudentDB (New Adm)'], ['student-crs', 'StudentCR']] as [$panel, $label])
                    <button wire:click="selectPanel('{{ $panel }}')"
                        class="w-full rounded px-3 py-2 text-left {{ $activePanel === $panel ? 'bg-cyan-950 font-semibold text-cyan-300' : 'hover:bg-slate-900' }}">{{ $label }}</button>
                @endforeach

                <p class="px-3 pb-1 pt-5 text-[10px] font-bold uppercase tracking-widest text-slate-600">School</p>
                <button wire:click="selectPanel('overview')"
                    class="w-full rounded px-3 py-2 text-left {{ $activePanel === 'overview' ? 'bg-cyan-950 font-semibold text-cyan-300' : 'hover:bg-slate-900' }}">Overview</button>
                <button wire:click="selectPanel('notices')"
                    class="w-full rounded px-3 py-2 text-left {{ $activePanel === 'notices' ? 'bg-cyan-950 font-semibold text-cyan-300' : 'hover:bg-slate-900' }}">Notices</button>

                <p class="px-3 pb-1 pt-5 text-[10px] font-bold uppercase tracking-widest text-slate-600">Academic combinations</p>
                @foreach ([['shreny-sections', 'Shreny sections'], ['shreny-subjects', 'Shreny subjects']] as [$panel, $label])
                    <button wire:click="selectPanel('{{ $panel }}')"
                        class="w-full rounded px-3 py-2 text-left {{ $activePanel === $panel ? 'bg-cyan-950 font-semibold text-cyan-300' : 'hover:bg-slate-900' }}">{{ $label }}</button>
                @endforeach

                <p class="px-3 pb-1 pt-5 text-[10px] font-bold uppercase tracking-widest text-cyan-400">Exam overview</p>
                <button wire:click="selectPanel('exam-overview')"
                    class="w-full rounded px-3 py-2 text-left {{ $activePanel === 'exam-overview' ? 'bg-cyan-950 font-semibold text-cyan-300' : 'hover:bg-slate-900' }}">Overview</button>
                @foreach ([['exam-combinations', 'Exam subjects'], ['exam-marks-settings', 'Exam FM / PM'], ['exam-script-distribution', 'Script distribution'], ['exam-marks-entry', 'Marks entry'], ['exam-marks-register', 'Mark register'], ['exam-marks-sheets', 'Mark sheet']] as [$panel, $label])
                    <button wire:click="selectPanel('{{ $panel }}')"
                        class="w-full rounded px-3 py-2 text-left {{ $activePanel === $panel ? 'bg-cyan-950 font-semibold text-cyan-300' : 'hover:bg-slate-900' }}">{{ $label }}</button>
                @endforeach

                <p class="px-3 pb-1 pt-5 text-[10px] font-bold uppercase tracking-widest text-slate-600">System settings</p>
                <button wire:click="selectPanel('user-profile')"
                    class="w-full rounded px-3 py-2 text-left {{ $activePanel === 'user-profile' ? 'bg-cyan-950 font-semibold text-cyan-300' : 'hover:bg-slate-900' }}">User Profile</button>
            </nav>
        </aside>
        <section class="min-w-0 flex-1">
            <header class="flex min-h-14 items-center justify-between border-b border-slate-200 px-5">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-cyan-600">Administration</p>
                    <h1 class="text-lg font-semibold tracking-tight">{{ $panelTitles[$activePanel] ?? 'Admin panel' }}
                    </h1>
                </div><div class="flex items-center gap-2"><button type="button" wire:click="toggleMutations" role="switch" aria-checked="{{ $mutationsEnabled ? 'true' : 'false' }}" class="rounded border px-3 py-2 text-xs font-semibold {{ $mutationsEnabled ? 'border-emerald-300 bg-emerald-50 text-emerald-700' : 'border-slate-300 text-slate-600' }}">{{ $mutationsEnabled ? 'Editing enabled' : 'Enable editing' }}</button><span class="rounded-full bg-cyan-50 px-2 py-1 text-xs font-semibold text-cyan-700">ADMIN</span></div>
            </header>
            <main class="space-y-5 p-5">
                @if($activePanel === 'overview' || $activePanel === 'exam-overview')
                    <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
                        @foreach ([['Schools', $totalSchools, 'text-cyan-600'], ['Users', $totalUsers, 'text-violet-600'], ['Teachers', $totalTeachers, 'text-amber-600'], ['Students', $totalStudents, 'text-emerald-600']] as [$label, $value, $color])
                            <div class="rounded-lg border border-slate-200 bg-white p-4">
                                <p class="text-[11px] text-slate-500">{{ $label }}</p>
                                <p class="mt-1 text-2xl font-semibold {{ $color }}">{{ $value }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
                @if($activePanel === 'overview')
                    <div class="grid gap-4 lg:grid-cols-2">
                        <div class="rounded-lg border border-slate-200 bg-white p-4">
                            <p class="mb-3 text-xs font-bold uppercase tracking-wider text-slate-500">General settings</p>
                            <div class="grid grid-cols-2 gap-2 text-xs text-slate-600">
                                <span>Schools and sessions</span>
                                <span>Shrenies and sections</span>
                                <span>Subjects and teachers</span>
                                <span>Combinations</span>
                            </div>
                        </div>
                        <div class="rounded-lg border border-slate-200 bg-white p-4">
                            <p class="mb-3 text-xs font-bold uppercase tracking-wider text-slate-500">Exam settings</p>
                            <p class="text-xs text-slate-600">Basic definitions, exam structure, marks, modes, grades, and Shreny-subject combinations.</p>
                        </div>
                    </div>
                    <div class="space-y-6">
                        <livewire:school-comp />
                        <livewire:session-comp />
                        <livewire:shreny-comp />
                        <livewire:section-comp />
                        <livewire:subject-comp />
                        <livewire:teacher-comp />
                    </div>
                @elseif($activePanel === 'students')
                <livewire:student-db-comp />
                @elseif($activePanel === 'student-crs')
                <livewire:student-cr-comp />
                @elseif($activePanel === 'school')
                <livewire:school-comp />
                @elseif($activePanel === 'session')
                <livewire:session-comp />
                @elseif($activePanel === 'shreny')
                <livewire:shreny-comp />
                @elseif($activePanel === 'section')
                <livewire:section-comp />
                @elseif($activePanel === 'subject')
                <livewire:subject-comp />
                @elseif($activePanel === 'teacher')
                <livewire:teacher-comp />
                @elseif($activePanel === 'notices')
                <livewire:notice-comp />
                @elseif($activePanel === 'user-profile')
                <livewire:profile-management-comp />
                @elseif($activePanel === 'shreny-sections')
                <livewire:shreny-section-comp />
                @elseif($activePanel === 'shreny-subjects')
                <livewire:shreny-subject-comp />
                @elseif($activePanel === 'exam-basics') <div
                    class="space-y-4 rounded-lg border border-slate-200 bg-white p-4">
                    <livewire:exam-name-comp />
                    <livewire:exam-type-comp />
                    <livewire:exam-part-comp />
                    <livewire:exam-mode-comp />
                    <livewire:exam-grade-comp />
                </div>
                @elseif($activePanel === 'exam-names')
                <livewire:exam-name-comp />
                @elseif($activePanel === 'exam-types')
                <livewire:exam-type-comp />
                @elseif($activePanel === 'exam-parts')
                <livewire:exam-part-comp />
                @elseif($activePanel === 'exam-modes')
                <livewire:exam-mode-comp />
                @elseif($activePanel === 'exam-grades')
                <livewire:exam-grade-comp />
                @elseif($activePanel === 'exam-combinations' || $activePanel === 'exam-overview')
                <livewire:exam-settings />
                @elseif($activePanel === 'exam-marks-settings')
                <livewire:exam-marks-settings />
                @elseif($activePanel === 'exam-marks-entry')
                <livewire:exam-marks-entry-comp />
                @elseif($activePanel === 'exam-marks-register')
                <livewire:exam-marks-register-comp />
                @elseif($activePanel === 'exam-marks-sheets')
                <livewire:exam-marks-sheet-list-comp />
                @elseif($activePanel === 'exam-script-distribution')
                <livewire:exam-script-distribution-comp />
                @endif
            </main>
        </section>
    </div>
</div>