<section class="space-y-4">
    <header class="flex flex-wrap items-end justify-between gap-3 border-b border-slate-200 pb-4">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-cyan-700">Exam overview</p>
            <h2 class="mt-1 text-base font-semibold text-slate-950">Exam date schedule</h2>
            <p class="mt-1 text-xs text-slate-500">Set subject dates and exam halves for the active school session.</p>
        </div>
        <button type="button" wire:click="toggleMutations" role="switch" aria-checked="{{ $mutationsEnabled ? 'true' : 'false' }}"
            class="rounded-md border px-3 py-2 text-xs font-semibold {{ $mutationsEnabled ? 'border-emerald-300 bg-emerald-50 text-emerald-700' : 'border-slate-300 text-slate-600' }}">
            {{ $mutationsEnabled ? 'Editing enabled' : 'Enable editing' }}
        </button>
    </header>

    @if (session('success'))<div role="status" class="rounded-md border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs text-emerald-800">{{ session('success') }}</div>@endif
    @if (session('error'))<div role="alert" class="rounded-md border border-amber-200 bg-amber-50 px-3 py-2 text-xs text-amber-800">{{ session('error') }}</div>@endif
    @unless ($hasActiveSession)<div class="rounded-md border border-rose-200 bg-rose-50 px-3 py-2 text-xs text-rose-800">No active school session is configured.</div>@endunless

    <div class="flex flex-wrap items-end gap-3">
        <label class="min-w-64 flex-1 text-xs font-semibold text-slate-600">Exam combination
            <select wire:model.live="selectedCombinationKey" class="schedule-field">
                <option value="">Select exam name / type / part</option>
                @foreach ($combinations as $combination)
                    <option value="{{ $combination['key'] }}">{{ $combination['label'] }}</option>
                @endforeach
            </select>
            @error('selectedCombinationKey')<span class="schedule-error">{{ $message }}</span>@enderror
        </label>
        @if ($selectedCombinationKey && $records->isNotEmpty())
            @if ($isFinalized)
                <button type="button" wire:click="reopenSchedule" wire:confirm="Reopen this schedule for editing?" @disabled(!$mutationsEnabled)
                    class="rounded-md border border-amber-300 px-3 py-2 text-xs font-semibold text-amber-800 disabled:opacity-50">Reopen schedule</button>
            @else
                <button type="button" wire:click="finalizeSchedule" wire:confirm="Finalize all dates in this exam combination?" @disabled(!$mutationsEnabled)
                    class="rounded-md bg-emerald-700 px-3 py-2 text-xs font-semibold text-white disabled:cursor-not-allowed disabled:bg-slate-300">Finalize schedule</button>
            @endif
        @endif
        <button type="button" wire:click="create" @disabled(!$selectedCombinationKey || !$mutationsEnabled || $isFinalized)
            class="rounded-md bg-cyan-700 px-3 py-2 text-xs font-semibold text-white hover:bg-cyan-600 disabled:cursor-not-allowed disabled:bg-slate-300">Add schedule</button>
    </div>

    @if ($combinations->isEmpty())
        <div class="rounded-md border border-dashed border-slate-300 px-4 py-8 text-center text-sm text-slate-500">No configured exam combinations are available for this school and session.</div>
    @elseif (!$selectedCombinationKey)
        <div class="rounded-md border border-dashed border-slate-300 px-4 py-8 text-center text-sm text-slate-500">Select an exam combination to view its schedule.</div>
    @else
        <div class="mb-2 flex items-center justify-between gap-2 text-xs text-slate-600">
            <span>{{ $records->count() }} scheduled exam(s)</span>
            @if ($records->isNotEmpty())
                <span class="rounded px-2 py-1 text-[10px] font-semibold {{ $isFinalized ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">{{ $isFinalized ? 'Finalized' : 'Editable' }}</span>
            @endif
        </div>
        <div class="overflow-x-auto rounded-md border border-slate-200">
            <table class="w-full min-w-[980px] text-left text-xs">
                <thead class="border-b border-slate-200 bg-slate-50 text-[10px] uppercase tracking-wider text-slate-500">
                    <tr><th class="px-3 py-2">Date / schedule</th><th class="px-3 py-2">Shreny / section</th><th class="px-3 py-2">Subject / mode</th><th class="px-3 py-2">Half / time</th><th class="px-3 py-2">Status</th><th class="px-3 py-2 text-right">Actions</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($records as $record)
                        <tr wire:key="exam-date-schedule-{{ $record->id }}" class="hover:bg-slate-50">
                            <td class="px-3 py-2.5"><p class="font-semibold text-slate-900">{{ $record->exam_date?->format('D, d M Y') }}</p><p class="mt-0.5 text-slate-500">{{ $record->name }}</p></td>
                            <td class="px-3 py-2.5 text-slate-700">{{ $record->shreny?->name }} / {{ $record->section?->name }}</td>
                            <td class="px-3 py-2.5 text-slate-700">{{ $record->subject?->name }}<span class="mt-0.5 block text-slate-500">{{ $record->examMode?->name }}</span></td>
                            <td class="px-3 py-2.5 text-slate-700">{{ $record->examHalf?->name }}<span class="mt-0.5 block text-slate-500">{{ $record->examHalf?->start_time ? substr((string) $record->examHalf->start_time, 0, 5) : '—' }}–{{ $record->examHalf?->end_time ? substr((string) $record->examHalf->end_time, 0, 5) : '—' }}</span></td>
                            <td class="px-3 py-2.5"><span class="rounded px-2 py-1 text-[10px] font-semibold {{ $record->is_finalized ? 'bg-emerald-100 text-emerald-800' : ($record->is_active ? 'bg-cyan-100 text-cyan-800' : 'bg-slate-100 text-slate-600') }}">{{ $record->is_finalized ? 'Finalized' : ($record->is_active ? 'Enabled' : 'Disabled') }}</span></td>
                            <td class="whitespace-nowrap px-3 py-2.5 text-right">
                                @unless ($isFinalized)
                                    <button type="button" wire:click="edit({{ $record->id }})" @disabled(!$mutationsEnabled) class="mr-3 font-semibold text-cyan-700 disabled:opacity-50">Edit</button>
                                    <button type="button" wire:click="delete({{ $record->id }})" wire:confirm="Delete this scheduled exam?" @disabled(!$mutationsEnabled) class="font-semibold text-rose-600 disabled:opacity-50">Delete</button>
                                @else<span class="text-[10px] text-slate-500">Locked</span>@endunless
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-3 py-8 text-center text-sm text-slate-500">No exam dates are scheduled for this combination.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    @if ($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/50 px-4 py-8" role="presentation">
            <div role="dialog" aria-modal="true" aria-labelledby="schedule-modal-title" class="mx-auto max-w-3xl rounded-lg bg-white shadow-xl">
                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                    <div><h3 id="schedule-modal-title" class="text-base font-semibold text-slate-900">{{ $recordId ? 'Edit exam schedule' : 'Add exam schedule' }}</h3><p class="mt-0.5 text-xs text-slate-500">Choose the class, subject, date, and exam half.</p></div>
                    <button type="button" wire:click="$set('showModal', false)" aria-label="Close dialog" class="text-xl leading-none text-slate-400 hover:text-slate-700">&times;</button>
                </div>
                <form wire:submit="save" class="space-y-4 p-5">
                    <div class="grid gap-3 sm:grid-cols-2">
                        <label class="text-xs font-semibold text-slate-600">Schedule title<input wire:model="name" type="text" maxlength="255" class="schedule-field" placeholder="Mathematics examination">@error('name')<span class="schedule-error">{{ $message }}</span>@enderror</label>
                        <label class="text-xs font-semibold text-slate-600">Date<input wire:model="exam_date" type="date" class="schedule-field">@error('exam_date')<span class="schedule-error">{{ $message }}</span>@enderror</label>
                    </div>
                    <label class="block text-xs font-semibold text-slate-600">Description<input wire:model="description" type="text" maxlength="255" class="schedule-field" placeholder="Optional details">@error('description')<span class="schedule-error">{{ $message }}</span>@enderror</label>

                    <div class="grid gap-3 sm:grid-cols-2">
                        <label class="text-xs font-semibold text-slate-600">Shreny<select wire:model.live="shreny_id" class="schedule-field"><option value="">Select Shreny</option>@foreach ($groups->unique('shreny_id') as $group)<option value="{{ $group['shreny_id'] }}">{{ $group['shreny_name'] }}</option>@endforeach</select>@error('shreny_id')<span class="schedule-error">{{ $message }}</span>@enderror</label>
                        <label class="text-xs font-semibold text-slate-600">Section<select wire:model.live="section_id" @disabled(!$shreny_id) class="schedule-field"><option value="">Select Section</option>@foreach ($sections as $group)<option value="{{ $group['section_id'] }}">{{ $group['section_name'] }}</option>@endforeach</select>@error('section_id')<span class="schedule-error">{{ $message }}</span>@enderror</label>
                    </div>
                    <div class="grid gap-3 sm:grid-cols-2">
                        <label class="text-xs font-semibold text-slate-600">Subject<select wire:model="subject_id" @disabled(!$shreny_id) class="schedule-field"><option value="">Select subject</option>@foreach ($subjects as $subject)<option value="{{ $subject->id }}">{{ $subject->name }}</option>@endforeach</select>@error('subject_id')<span class="schedule-error">{{ $message }}</span>@enderror</label>
                        <label class="text-xs font-semibold text-slate-600">Exam mode<select wire:model="exam_mode_id" class="schedule-field"><option value="">Select mode</option>@foreach ($modes as $mode)<option value="{{ $mode->id }}">{{ $mode->name }}</option>@endforeach</select>@error('exam_mode_id')<span class="schedule-error">{{ $message }}</span>@enderror</label>
                    </div>
                    <div class="grid gap-3 sm:grid-cols-2">
                        <label class="text-xs font-semibold text-slate-600">Exam half<select wire:model="exam_half_id" class="schedule-field"><option value="">Select half</option>@foreach ($halves as $half)<option value="{{ $half->id }}">{{ $half->name }}{{ $half->start_time ? ' · '.substr((string) $half->start_time, 0, 5) : '' }}{{ $half->end_time ? '–'.substr((string) $half->end_time, 0, 5) : '' }}</option>@endforeach</select>@error('exam_half_id')<span class="schedule-error">{{ $message }}</span>@enderror @if($halves->isEmpty())<span class="mt-1 block text-[11px] text-amber-700">Create an active exam half for this combination first.</span>@endif</label>
                        <label class="text-xs font-semibold text-slate-600">Order<input wire:model="order_id" type="number" min="0" class="schedule-field" placeholder="Optional">@error('order_id')<span class="schedule-error">{{ $message }}</span>@enderror</label>
                    </div>
                    <label class="block text-xs font-semibold text-slate-600">Remarks<textarea wire:model="remarks" rows="2" maxlength="255" class="schedule-field"></textarea>@error('remarks')<span class="schedule-error">{{ $message }}</span>@enderror</label>
                    <label class="inline-flex cursor-pointer items-center gap-2 text-xs font-medium text-slate-700"><input wire:model="is_active" type="checkbox" class="rounded border-slate-300 text-cyan-700 focus:ring-cyan-600">Enabled</label>

                    <div class="flex justify-end gap-2 border-t border-slate-100 pt-4">
                        <button type="button" wire:click="$set('showModal', false)" class="rounded-md border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">Cancel</button>
                        <button type="submit" @disabled($halves->isEmpty() || !$mutationsEnabled || $isFinalized) class="rounded-md bg-cyan-700 px-4 py-2 text-xs font-semibold text-white hover:bg-cyan-600 disabled:cursor-not-allowed disabled:bg-slate-300">{{ $recordId ? 'Save changes' : 'Create schedule' }}</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <style>
        .schedule-field { display: block; width: 100%; margin-top: .3rem; border: 1px solid #cbd5e1; border-radius: .375rem; background: #fff; padding: .5rem .65rem; font-size: .75rem; }
        .schedule-error { display: block; margin-top: .25rem; color: #e11d48; font-size: .7rem; }
    </style>
</section>