@php
    $panelTitles = [
        'overview' => 'Teacher overview',
        'profile' => 'My profile',
        'school' => 'School information',
        'academic' => 'Academic snapshot',
        'exams' => 'Exam centre',
        'notices' => 'School notices',
    ];
    $address = $teacher ? collect([$teacher->vill, $teacher->district, $teacher->block, $teacher->pincode])->filter()->join(', ') : null;
@endphp

<div class="min-h-[calc(100vh-8rem)] bg-slate-100 text-slate-900">
    <div class="mx-auto flex max-w-[1600px] flex-col overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm lg:flex-row">
        <aside class="w-full shrink-0 bg-[#172554] text-blue-100 lg:min-h-[calc(100vh-8rem)] lg:w-64">
            <div class="flex h-16 items-center gap-3 border-b border-blue-900 px-5">
                <div class="grid h-9 w-9 place-items-center rounded-lg bg-amber-400 text-sm font-black text-blue-950">GV</div>
                <div>
                    <p class="text-xs font-bold tracking-[0.18em] text-white">GVN SCHOOL</p>
                    <p class="text-[10px] uppercase tracking-widest text-blue-300">Teacher workspace</p>
                </div>
            </div>
            <nav class="grid gap-1 p-3 text-sm sm:grid-cols-3 lg:block">
                <p class="px-3 pb-1 pt-2 text-[10px] font-bold uppercase tracking-[0.18em] text-blue-300 sm:col-span-3 lg:pt-3">Workspace</p>
                @foreach ([['overview', 'Overview'], ['profile', 'My profile'], ['school', 'School information']] as [$panel, $label])
                    <button wire:click="selectPanel('{{ $panel }}')" class="w-full rounded-lg px-3 py-2.5 text-left transition {{ $activePanel === $panel ? 'bg-amber-400 font-semibold text-blue-950 shadow-sm' : 'text-blue-100 hover:bg-blue-900' }}">{{ $label }}</button>
                @endforeach
                <p class="px-3 pb-1 pt-4 text-[10px] font-bold uppercase tracking-[0.18em] text-emerald-300 sm:col-span-3 lg:pt-7">Academic</p>
                @foreach ([['academic', 'Classes & subjects'], ['exams', 'Exam centre'], ['notices', 'Notices']] as [$panel, $label])
                    <button wire:click="selectPanel('{{ $panel }}')" class="w-full rounded-lg px-3 py-2.5 text-left transition {{ $activePanel === $panel ? 'bg-emerald-400 font-semibold text-emerald-950 shadow-sm' : 'text-blue-100 hover:bg-blue-900' }}">{{ $label }}</button>
                @endforeach
            </nav>
            <div class="mx-4 mt-3 rounded-lg border border-blue-800 bg-blue-950/60 p-3 text-xs sm:hidden lg:block">
                <p class="font-semibold text-amber-300">View only</p>
                <p class="mt-1 leading-relaxed text-blue-300">This workspace shows information assigned to your school and active session.</p>
            </div>
        </aside>

        <section class="min-w-0 flex-1 bg-slate-50">
            <header class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 bg-white px-5 py-4 sm:px-7">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-blue-600">Teacher workspace</p>
                    <h1 class="text-xl font-semibold tracking-tight text-slate-950">{{ $panelTitles[$activePanel] ?? 'Teacher overview' }}</h1>
                </div>
                <div class="flex items-center gap-2">
                    <span class="rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700">VIEW ONLY</span>
                    <span class="rounded-full bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700">{{ $session?->name ?? 'No active session' }}</span>
                </div>
            </header>

            <main class="space-y-5 p-5 sm:p-7">
                @if (session('success'))
                    <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('success') }}</div>
                @endif
                @if (!$teacher)
                    <div class="rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">Your teacher profile is not linked yet. Ask an administrator to assign your teacher profile.</div>
                @endif

                @if ($activePanel === 'overview' || $activePanel === 'academic')
                    <div class="grid grid-cols-2 gap-3 xl:grid-cols-4">
                        @foreach ([['Active students', $totalStudents, 'text-blue-700', 'bg-blue-50'], ['Class records', $totalClassRecords, 'text-emerald-700', 'bg-emerald-50'], ['Shrenies', $totalShrenies, 'text-amber-700', 'bg-amber-50'], ['Sections', $totalSections, 'text-rose-700', 'bg-rose-50']] as [$label, $value, $color, $background])
                            <div class="rounded-lg border border-slate-200 bg-white p-4">
                                <div class="mb-3 grid h-8 w-8 place-items-center rounded {{ $background }} text-xs font-black {{ $color }}">{{ substr($label, 0, 1) }}</div>
                                <p class="text-xs text-slate-500">{{ $label }}</p>
                                <p class="mt-1 text-2xl font-semibold {{ $color }}">{{ number_format($value) }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if ($activePanel === 'overview' || $activePanel === 'profile')
                    <div class="grid gap-5 xl:grid-cols-[1.15fr_0.85fr]">
                        <section class="rounded-lg border border-slate-200 bg-white p-5">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    @if ($teacher?->prof_img_ref)
                                        <img src="{{ asset('storage/' . $teacher->prof_img_ref) }}" alt="{{ $teacher->name }}" class="h-16 w-16 rounded-full object-cover ring-4 ring-blue-50">
                                    @else
                                        <div class="grid h-16 w-16 place-items-center rounded-full bg-blue-100 text-xl font-bold text-blue-700 ring-4 ring-blue-50">{{ strtoupper(substr($teacher?->name ?? 'T', 0, 1)) }}</div>
                                    @endif
                                    <div>
                                    <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-blue-600">Your profile</p>
                                    <h2 class="mt-1 text-lg font-semibold text-slate-950">{{ $teacher?->name ?? auth()->user()?->name ?? 'Teacher' }}</h2>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2"><span class="rounded-full bg-blue-50 px-2.5 py-1 text-[10px] font-bold text-blue-700">TEACHER</span>@if($teacher)<button wire:click="openProfileEditor" class="rounded-lg bg-amber-400 px-3 py-2 text-xs font-bold text-amber-950 hover:bg-amber-300">Edit details</button>@endif</div>
                            </div>
                            <dl class="mt-5 grid gap-4 text-sm sm:grid-cols-2">
                                <div><dt class="text-xs text-slate-500">Email</dt><dd class="mt-1 font-medium text-slate-800">{{ $teacher?->email ?: auth()->user()?->email ?: 'Not available' }}</dd></div>
                                <div><dt class="text-xs text-slate-500">Mobile</dt><dd class="mt-1 font-medium text-slate-800">{{ $teacher?->mobile ?: 'Not available' }}</dd></div>
                                <div><dt class="text-xs text-slate-500">Date of birth</dt><dd class="mt-1 font-medium text-slate-800">{{ $teacher?->dob ?: 'Not available' }}</dd></div>
                                <div><dt class="text-xs text-slate-500">Gender</dt><dd class="mt-1 font-medium text-slate-800">{{ $teacher?->gender ?: 'Not available' }}</dd></div>
                                <div><dt class="text-xs text-slate-500">Highest qualification</dt><dd class="mt-1 font-medium text-slate-800">{{ $teacher?->high_qual ?: 'Not available' }}</dd></div>
                                <div><dt class="text-xs text-slate-500">Higher qualification subject</dt><dd class="mt-1 font-medium text-slate-800">{{ $teacher?->high_qual_subject ?: 'Not available' }}</dd></div>
                                <div><dt class="text-xs text-slate-500">Professional qualification</dt><dd class="mt-1 font-medium text-slate-800">{{ $teacher?->prof_qual ?: 'Not available' }}</dd></div>
                                <div><dt class="text-xs text-slate-500">Professional subject</dt><dd class="mt-1 font-medium text-slate-800">{{ $teacher?->prof_qual_subject ?: 'Not available' }}</dd></div>
                                <div><dt class="text-xs text-slate-500">Address</dt><dd class="mt-1 font-medium text-slate-800">{{ $address ?: 'Not available' }}</dd></div>
                                <div><dt class="text-xs text-slate-500">Post office</dt><dd class="mt-1 font-medium text-slate-800">{{ $teacher?->post_office ?: 'Not available' }}</dd></div>
                                <div><dt class="text-xs text-slate-500">Police station</dt><dd class="mt-1 font-medium text-slate-800">{{ $teacher?->police_station ?: 'Not available' }}</dd></div>
                                <div><dt class="text-xs text-slate-500">Remarks</dt><dd class="mt-1 font-medium text-slate-800">{{ $teacher?->remarks ?: 'Not available' }}</dd></div>
                            </dl>
                            <p class="mt-5 text-xs text-slate-400">Name, email, qualification level, school, session, and account status can be changed by an administrator only.</p>
                        </section>
                        <section class="rounded-lg border border-slate-200 bg-white p-5">
                            <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-emerald-600">School context</p>
                            <h2 class="mt-1 text-lg font-semibold text-slate-950">{{ $school?->name ?? 'School not assigned' }}</h2>
                            <dl class="mt-5 space-y-3 text-sm">
                                <div class="flex justify-between gap-4 border-b border-slate-100 pb-3"><dt class="text-slate-500">Active session</dt><dd class="text-right font-medium">{{ $session?->name ?? 'Not configured' }}</dd></div>
                                <div class="flex justify-between gap-4 border-b border-slate-100 pb-3"><dt class="text-slate-500">School type</dt><dd class="text-right font-medium">{{ $school?->school_type ?: 'Not available' }}</dd></div>
                                <div class="flex justify-between gap-4"><dt class="text-slate-500">Location</dt><dd class="text-right font-medium">{{ collect([$school?->vill, $school?->district])->filter()->join(', ') ?: 'Not available' }}</dd></div>
                            </dl>
                        </section>
                    </div>
                @endif

                @if ($activePanel === 'school')
                    <section class="rounded-lg border border-slate-200 bg-white p-5">
                        <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-blue-600">School information</p>
                        <h2 class="mt-1 text-xl font-semibold">{{ $school?->name ?? 'School not assigned' }}</h2>
                        <div class="mt-6 grid gap-4 text-sm sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ([['DISE code', $school?->dise_code], ['UDISE code', $school?->udise_code], ['Type', $school?->school_type], ['Village / town', $school?->vill], ['District', $school?->district], ['Block', $school?->block], ['Session', $session?->name]] as [$label, $value])
                                <div class="rounded-lg bg-slate-50 p-4"><p class="text-xs text-slate-500">{{ $label }}</p><p class="mt-1 font-semibold text-slate-800">{{ $value ?: 'Not available' }}</p></div>
                            @endforeach
                        </div>
                    </section>
                @endif

                @if ($activePanel === 'overview' || $activePanel === 'academic')
                    <section class="rounded-lg border border-slate-200 bg-white p-5">
                        <div class="flex flex-wrap items-end justify-between gap-3"><div><p class="text-[10px] font-bold uppercase tracking-[0.18em] text-emerald-600">Academic snapshot</p><h2 class="mt-1 text-lg font-semibold">Classes, subjects and learners</h2></div><span class="text-xs text-slate-500">{{ number_format($totalSubjects) }} active subjects</span></div>
                        <div class="mt-5 grid gap-3 sm:grid-cols-3"><div class="rounded-lg bg-emerald-50 p-4"><p class="text-xs text-emerald-700">Subjects</p><p class="mt-1 text-2xl font-semibold text-emerald-800">{{ number_format($totalSubjects) }}</p></div><div class="rounded-lg bg-amber-50 p-4"><p class="text-xs text-amber-700">Shrenies</p><p class="mt-1 text-2xl font-semibold text-amber-800">{{ number_format($totalShrenies) }}</p></div><div class="rounded-lg bg-rose-50 p-4"><p class="text-xs text-rose-700">Sections</p><p class="mt-1 text-2xl font-semibold text-rose-800">{{ number_format($totalSections) }}</p></div></div>
                    </section>
                @endif

                @if ($activePanel === 'overview' || $activePanel === 'exams')
                    <section class="rounded-lg border border-slate-200 bg-white p-5">
                        <div class="flex flex-wrap items-end justify-between gap-3"><div><p class="text-[10px] font-bold uppercase tracking-[0.18em] text-amber-600">Exam centre</p><h2 class="mt-1 text-lg font-semibold">Exam setup and marks status</h2></div><span class="text-xs text-slate-500">{{ number_format($marksTotal) }} mark entries</span></div>
                        <div class="mt-5 grid grid-cols-2 gap-3 md:grid-cols-5">@foreach ($examCounts as $label => $value)<div class="rounded-lg border border-slate-100 bg-slate-50 p-3"><p class="text-xs text-slate-500">{{ $label }}</p><p class="mt-1 text-xl font-semibold text-slate-800">{{ number_format($value) }}</p></div>@endforeach</div>
                        <div class="mt-4 grid gap-3 sm:grid-cols-3"><div class="rounded-lg bg-blue-50 p-3 text-sm"><span class="text-blue-700">All marks</span><strong class="float-right text-blue-900">{{ number_format($marksTotal) }}</strong></div><div class="rounded-lg bg-emerald-50 p-3 text-sm"><span class="text-emerald-700">Finalized</span><strong class="float-right text-emerald-900">{{ number_format($marksFinalized) }}</strong></div><div class="rounded-lg bg-violet-50 p-3 text-sm"><span class="text-violet-700">Issued</span><strong class="float-right text-violet-900">{{ number_format($marksIssued) }}</strong></div></div>
                    </section>
                    <section class="rounded-lg border border-slate-200 bg-white p-5"><div class="flex items-center justify-between"><div><p class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-500">Assigned scripts</p><h2 class="mt-1 text-lg font-semibold">Exam script distribution</h2></div><span class="text-xs text-slate-500">{{ $scriptAssignments->count() }} shown</span></div><div class="mt-4 divide-y divide-slate-100">@forelse ($scriptAssignments as $assignment)<div class="flex flex-wrap items-center justify-between gap-3 py-3 text-sm"><div><p class="font-medium text-slate-800">{{ $assignment->name }}</p><p class="mt-1 text-xs text-slate-500">{{ $assignment->description ?: 'No description' }}</p></div><span class="rounded-full px-2.5 py-1 text-xs {{ $assignment->submited_date ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">{{ $assignment->submited_date ? 'Submitted' : 'Pending' }}</span></div>@empty<p class="py-4 text-sm text-slate-500">No exam script assignments found for this teacher and session.</p>@endforelse</div></section>
                @endif

                @if ($activePanel === 'overview' || $activePanel === 'notices')
                    <section class="rounded-lg border border-slate-200 bg-white p-5"><div><p class="text-[10px] font-bold uppercase tracking-[0.18em] text-rose-600">School notices</p><h2 class="mt-1 text-lg font-semibold">Latest updates</h2></div><div class="mt-4 divide-y divide-slate-100">@forelse ($notices as $notice)<article class="py-3"><div class="flex flex-wrap items-center justify-between gap-2"><h3 class="font-medium text-slate-800">{{ $notice->title }}</h3><time class="text-xs text-slate-500">{{ $notice->upload_dt?->format('d M Y') }}</time></div><p class="mt-1 text-sm leading-relaxed text-slate-600">{{ $notice->description ?: 'No additional details.' }}</p></article>@empty<p class="py-4 text-sm text-slate-500">No active notices for this school and session.</p>@endforelse</div></section>
                @endif
            </main>
        </section>
    </div>
</div>

@if ($showProfileEditor)
    <div class="fixed inset-0 z-50 grid place-items-center bg-slate-950/50 px-4" role="dialog" aria-modal="true">
        <div class="max-h-[90vh] w-full max-w-3xl overflow-y-auto rounded-xl bg-white p-5 shadow-xl sm:p-6">
            <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4"><div><p class="text-[10px] font-bold uppercase tracking-[0.18em] text-amber-600">Self-service profile</p><h2 class="mt-1 text-lg font-semibold text-slate-950">Update your details</h2></div><button wire:click="$set('showProfileEditor', false)" class="text-2xl leading-none text-slate-400 hover:text-slate-700" aria-label="Close">&times;</button></div>
            @error('profile') <p class="mt-4 rounded-lg bg-rose-50 px-3 py-2 text-xs text-rose-700">{{ $message }}</p> @enderror
            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2"><label class="text-xs font-medium text-slate-600">Profile image</label><input type="file" wire:model="profileImage" accept="image/*" class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">@error('profileImage')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror</div>
                <div><label class="text-xs font-medium text-slate-600">Mobile</label><input wire:model="mobile" type="text" class="mt-1 w-full rounded-lg border-slate-300 text-sm">@error('mobile')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror</div>
                <div><label class="text-xs font-medium text-slate-600">Date of birth</label><input wire:model="dob" type="date" class="mt-1 w-full rounded-lg border-slate-300 text-sm"></div>
                <div><label class="text-xs font-medium text-slate-600">Gender</label><input wire:model="gender" type="text" class="mt-1 w-full rounded-lg border-slate-300 text-sm"></div>
                <div><label class="text-xs font-medium text-slate-600">Higher qualification subject</label><input wire:model="high_qual_subject" type="text" class="mt-1 w-full rounded-lg border-slate-300 text-sm"></div>
                <div><label class="text-xs font-medium text-slate-600">Professional qualification subject</label><input wire:model="prof_qual_subject" type="text" class="mt-1 w-full rounded-lg border-slate-300 text-sm"></div>
                @foreach ([['vill', 'Village / town'], ['post_office', 'Post office'], ['police_station', 'Police station'], ['district', 'District'], ['block', 'Block'], ['pincode', 'Pincode']] as [$field, $label])
                    <div><label class="text-xs font-medium text-slate-600">{{ $label }}</label><input wire:model="{{ $field }}" type="text" class="mt-1 w-full rounded-lg border-slate-300 text-sm"></div>
                @endforeach
                <div class="sm:col-span-2"><label class="text-xs font-medium text-slate-600">Additional remarks</label><textarea wire:model="remarks" rows="2" class="mt-1 w-full rounded-lg border-slate-300 text-sm"></textarea></div>
            </div>
            <div class="mt-6 flex justify-end gap-2 border-t border-slate-100 pt-4"><button wire:click="$set('showProfileEditor', false)" class="rounded-lg border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-700">Cancel</button><button wire:click="saveProfile" wire:loading.attr="disabled" class="rounded-lg bg-amber-400 px-4 py-2 text-xs font-bold text-amber-950 hover:bg-amber-300">Save profile</button></div>
        </div>
    </div>
@endif