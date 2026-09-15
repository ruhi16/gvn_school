<div class="space-y-8">
    @if (session('success'))
    <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{
        session('success') }}</div>
    @endif

    <section class="space-y-3">
        <div>
            <h2 class="text-base font-semibold text-slate-900">Staff and teacher accounts</h2>
            <p class="text-xs text-slate-500">Choose a role first. Teacher accounts can be linked only to an unassigned
                teacher record.</p>
        </div>
        <div class="overflow-x-auto rounded-lg border border-slate-200">
            <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
                <thead class="bg-slate-50 text-[10px] uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Role</th>
                        <th class="px-4 py-3">Teacher</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($users as $user)
                    <tr wire:key="user-{{ $user->id }}">
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $user->name }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $user->email }}</td>
                        <td class="px-4 py-3 capitalize">{{ $user->role }}</td>
                        <td class="px-4 py-3">
                            {{ $user->teacher?->name ?? 'Not assigned' }}
                        </td>
                        <td class="space-x-2 whitespace-nowrap px-4 py-3">
                            <button wire:click="openRoleModal({{ $user->id }})"
                                class="font-semibold text-cyan-700 hover:text-cyan-900">Assign role</button>
                            <button wire:click="clearRole({{ $user->id }})"
                                wire:confirm="Remove this user's role assignment?"
                                class="font-semibold text-amber-700 hover:text-amber-900">Change role</button>
                            <button wire:click="openPasswordModal({{ $user->id }})"
                                class="font-semibold text-slate-600 hover:text-slate-900">Password</button>
                            <button wire:click="deleteUser({{ $user->id }})"
                                wire:confirm="Delete this user permanently?"
                                class="font-semibold text-rose-700 hover:text-rose-900">Delete</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-slate-500">No non-student users found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="space-y-3">
        <div>
            <h2 class="text-base font-semibold text-slate-900">Student accounts</h2>
            <p class="text-xs text-slate-500">Confirm the date of birth before selecting a matching student profile.</p>
        </div>
        <div class="overflow-x-auto rounded-lg border border-slate-200">
            <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
                <thead class="bg-slate-50 text-[10px] uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Date of birth</th>
                        <th class="px-4 py-3">Student profile</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($students as $user)
                    @php($profile = $user->student_id ? $assignedProfiles->get($user->student_id) : null)
                    <tr wire:key="student-user-{{ $user->id }}">
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $user->name }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $user->email }}</td>
                        <td class="px-4 py-3">
                            <input type="date" wire:model.live="studentDob.{{ $user->id }}"
                                class="rounded border-slate-300 text-xs">
                            @error("studentDob.{$user->id}") <p class="mt-1 text-[11px] text-rose-600">{{ $message }}
                            </p> @enderror
                        </td>
                        <td class="px-4 py-3">
                            <select wire:model="studentSelections.{{ $user->id }}"
                                class="w-52 rounded border-slate-300 text-xs">
                                <option value="">Select matching student</option>
                                @foreach ($studentProfiles as $student)
                                @if (($studentDob[$user->id] ?? '') === ($student->dob?->format('Y-m-d') ?? ''))
                                <option value="{{ $student->id }}">{{ $student->name }}{{ $student->fname ? ' -
                                    '.$student->fname : '' }}</option>
                                @endif
                                @endforeach
                            </select>
                            @if ($profile)
                            <div class="mt-2 text-[11px] text-slate-600">{{ $profile->name }} | DOB: {{
                                $profile->dob?->format('d M Y') }} | {{ $profile->mobile_1 ?: 'No mobile' }}</div>
                            @else
                            <div class="mt-2 text-[11px] text-slate-400">No student profile assigned</div>
                            @endif
                            @error("studentSelections.{$user->id}") <p class="mt-1 text-[11px] text-rose-600">{{
                                $message }}</p> @enderror
                        </td>
                        <td class="space-x-2 whitespace-nowrap px-4 py-3">
                            <button wire:click="openStudentModal({{ $user->id }})"
                                class="font-semibold text-cyan-700 hover:text-cyan-900">Assign student</button>
                            <button wire:click="removeStudent({{ $user->id }})"
                                wire:confirm="Remove this student profile assignment?"
                                class="font-semibold text-amber-700 hover:text-amber-900">Remove student</button>
                            <button wire:click="openPasswordModal({{ $user->id }})"
                                class="font-semibold text-slate-600 hover:text-slate-900">Password</button>
                            <button wire:click="deleteUser({{ $user->id }})"
                                wire:confirm="Delete this user permanently?"
                                class="font-semibold text-rose-700 hover:text-rose-900">Delete</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-slate-500">No student users found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    @if ($showPasswordModal)
    <div class="fixed inset-0 z-50 grid place-items-center bg-slate-950/50 px-4" role="dialog" aria-modal="true">
        <div class="w-full max-w-md rounded-lg bg-white p-5 shadow-xl">
            <div class="flex items-center justify-between">
                <h2 class="text-base font-semibold text-slate-900">Change password</h2>
                <button wire:click="$set('showPasswordModal', false)"
                    class="text-xl text-slate-400 hover:text-slate-700" aria-label="Close">&times;</button>
            </div>
            <div class="mt-4 space-y-3">
                <div><label class="text-xs font-medium text-slate-600">New password</label><input type="password"
                        wire:model="newPassword"
                        class="mt-1 w-full rounded border-slate-300 text-sm">@error('newPassword') <p
                        class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror</div>
                <div><label class="text-xs font-medium text-slate-600">Confirm password</label><input type="password"
                        wire:model="newPasswordConfirmation" class="mt-1 w-full rounded border-slate-300 text-sm"></div>
            </div>
            <div class="mt-5 flex justify-end gap-2">
                <button wire:click="$set('showPasswordModal', false)"
                    class="rounded border border-slate-300 px-3 py-2 text-xs font-semibold text-slate-700">Cancel</button>
                <button wire:click="updatePassword"
                    class="rounded bg-cyan-700 px-3 py-2 text-xs font-semibold text-white hover:bg-cyan-800">Change
                    password</button>
            </div>
        </div>
    </div>
    @endif

    @if ($showRoleModal)
    <div class="fixed inset-0 z-50 grid place-items-center bg-slate-950/50 px-4" role="dialog" aria-modal="true">
        <div class="w-full max-w-md rounded-lg bg-white p-5 shadow-xl">
            <div class="flex items-center justify-between">
                <h2 class="text-base font-semibold text-slate-900">Assign role</h2><button
                    wire:click="$set('showRoleModal', false)" class="text-xl text-slate-400"
                    aria-label="Close">&times;</button>
            </div>
            <div class="mt-4 space-y-3">
                <div><label class="text-xs font-medium text-slate-600">Role</label><select
                        wire:model.live="assignmentRole" class="mt-1 w-full rounded border-slate-300 text-sm">
                        <option value="">Select role</option>
                        <option value="admin">Admin</option>
                        <option value="teacher">Teacher</option>
                        <option value="staff">Staff</option>
                    </select>@error('assignmentRole') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
                @if ($assignmentRole === 'teacher')
                <div><label class="text-xs font-medium text-slate-600">Teacher profile</label><select
                        wire:model="assignmentTeacherId" class="mt-1 w-full rounded border-slate-300 text-sm">
                        <option value="">Select teacher</option>@foreach ($teachers as $teacher)<option
                            value="{{ $teacher->id }}">{{ $teacher->name }}</option>@endforeach
                    </select>@error('assignmentTeacherId') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror</div>
                @endif
            </div>
            <div class="mt-5 flex justify-end gap-2"><button wire:click="$set('showRoleModal', false)"
                    class="rounded border border-slate-300 px-3 py-2 text-xs font-semibold">Cancel</button><button
                    wire:click="assignRole"
                    class="rounded bg-cyan-700 px-3 py-2 text-xs font-semibold text-white">Assign role</button></div>
        </div>
    </div>
    @endif

    @if ($showStudentModal)
    <div class="fixed inset-0 z-50 grid place-items-center bg-slate-950/50 px-4" role="dialog" aria-modal="true">
        <div class="w-full max-w-3xl rounded-lg bg-white p-5 shadow-xl">
            <div class="flex items-center justify-between">
                <h2 class="text-base font-semibold text-slate-900">Verify student profile</h2><button
                    wire:click="$set('showStudentModal', false)" class="text-xl text-slate-400"
                    aria-label="Close">&times;</button>
            </div>
            <p class="mt-1 text-xs text-slate-500">Enter the date of birth. Matching StudentDB records will appear
                below.</p>
            <input type="date" wire:model.live="studentDobInput" class="mt-4 rounded border-slate-300 text-sm">
            @error('studentDobInput') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            @error('studentSelections.'.$assignmentUserId) <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
            <div class="mt-4 overflow-x-auto rounded border border-slate-200">
                <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
                    <thead class="bg-slate-50 text-[10px] uppercase text-slate-500">
                        <tr>
                            <th class="px-3 py-2">Select</th>
                            <th class="px-3 py-2">Name</th>
                            <th class="px-3 py-2">Father</th>
                            <th class="px-3 py-2">Gender</th>
                            <th class="px-3 py-2">Mobile</th>
                            <th class="px-3 py-2">DOB</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($studentMatches as $student)<tr>
                            <td class="px-3 py-2"><input type="radio"
                                    wire:model="studentSelections.{{ $assignmentUserId }}" value="{{ $student->id }}">
                            </td>
                            <td class="px-3 py-2 font-medium">{{ $student->name }}</td>
                            <td class="px-3 py-2">{{ $student->fname ?: '-' }}</td>
                            <td class="px-3 py-2">{{ $student->gender ?: '-' }}</td>
                            <td class="px-3 py-2">{{ $student->mobile_1 ?: '-' }}</td>
                            <td class="px-3 py-2">{{ $student->dob?->format('d M Y') }}</td>
                        </tr>@empty<tr>
                            <td colspan="6" class="px-3 py-6 text-center text-slate-500">No matching records.</td>
                        </tr>@endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-5 flex justify-end gap-2"><button wire:click="$set('showStudentModal', false)"
                    class="rounded border border-slate-300 px-3 py-2 text-xs font-semibold">Cancel</button><button
                    wire:click="assignSelectedStudent"
                    class="rounded bg-cyan-700 px-3 py-2 text-xs font-semibold text-white">Assign selected
                    student</button></div>
        </div>
    </div>
    @endif
</div>