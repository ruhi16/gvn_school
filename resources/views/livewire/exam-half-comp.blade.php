<section class="space-y-4">
    <header class="flex flex-wrap items-end justify-between gap-3 border-b border-slate-200 pb-4">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-cyan-700">Exam settings</p>
            <h2 class="mt-1 text-base font-semibold text-slate-950">Exam halves</h2>
            <p class="mt-1 text-xs text-slate-500">Set half timings and the days each half is available.</p>
        </div>
        <button type="button" wire:click="toggleMutations" role="switch" aria-checked="{{ $mutationsEnabled ? 'true' : 'false' }}"
            class="rounded-md border px-3 py-2 text-xs font-semibold {{ $mutationsEnabled ? 'border-emerald-300 bg-emerald-50 text-emerald-700' : 'border-slate-300 text-slate-600' }}">
            {{ $mutationsEnabled ? 'Editing enabled' : 'Enable editing' }}
        </button>
    </header>

    @if (session('success'))
        <div role="status" class="rounded-md border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs text-emerald-800">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div role="alert" class="rounded-md border border-amber-200 bg-amber-50 px-3 py-2 text-xs text-amber-800">{{ session('error') }}</div>
    @endif
    @unless ($hasActiveSession)
        <div class="rounded-md border border-rose-200 bg-rose-50 px-3 py-2 text-xs text-rose-800">No active school session is configured.</div>
    @endunless

    <div class="flex flex-wrap items-end gap-3">
        <label class="min-w-64 flex-1 text-xs font-semibold text-slate-600">
            Exam combination
            <select wire:model.live="selectedCombinationKey" class="exam-half-field">
                <option value="">Select exam name / type / part</option>
                @foreach ($combinations as $combination)
                    <option value="{{ $combination['key'] }}">{{ $combination['label'] }}</option>
                @endforeach
            </select>
            @error('selectedCombinationKey')<span class="exam-half-error">{{ $message }}</span>@enderror
        </label>
        <button type="button" wire:click="create" @disabled(!$selectedCombinationKey || !$mutationsEnabled)
            class="rounded-md bg-cyan-700 px-3 py-2 text-xs font-semibold text-white hover:bg-cyan-600 disabled:cursor-not-allowed disabled:bg-slate-300">
            Add exam half
        </button>
    </div>

    @if ($combinations->isEmpty())
        <div class="rounded-md border border-dashed border-slate-300 px-4 py-8 text-center text-sm text-slate-500">
            No configured exam combinations are available for this school and session.
        </div>
    @elseif (!$selectedCombinationKey)
        <div class="rounded-md border border-dashed border-slate-300 px-4 py-8 text-center text-sm text-slate-500">
            Select an exam combination to view or manage its halves.
        </div>
    @else
        <div class="overflow-x-auto rounded-md border border-slate-200">
            <table class="w-full min-w-[760px] text-left text-xs">
                <thead class="border-b border-slate-200 bg-slate-50 text-[10px] uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-3 py-2">Half</th>
                        <th class="px-3 py-2">Time</th>
                        <th class="px-3 py-2">Duration</th>
                        <th class="px-3 py-2">Available days</th>
                        <th class="px-3 py-2">Status</th>
                        <th class="px-3 py-2 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($records as $record)
                        <tr wire:key="exam-half-{{ $record->id }}" class="hover:bg-slate-50">
                            <td class="px-3 py-2.5">
                                <p class="font-semibold text-slate-900">{{ $record->name }}</p>
                                @if ($record->description)<p class="mt-0.5 text-slate-500">{{ $record->description }}</p>@endif
                            </td>
                            <td class="whitespace-nowrap px-3 py-2.5 text-slate-700">{{ $record->start_time ? substr((string) $record->start_time, 0, 5) : '—' }}–{{ $record->end_time ? substr((string) $record->end_time, 0, 5) : '—' }}</td>
                            <td class="px-3 py-2.5 text-slate-700">{{ $this->formatDuration($record->start_time, $record->end_time) }}</td>
                            <td class="px-3 py-2.5 text-slate-600">{{ implode(', ', $record->active_exam_days ?? []) ?: '—' }}</td>
                            <td class="px-3 py-2.5"><span class="rounded px-2 py-1 text-[10px] font-semibold {{ $record->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">{{ $record->is_active ? 'Active' : 'Inactive' }}</span></td>
                            <td class="whitespace-nowrap px-3 py-2.5 text-right">
                                <button type="button" wire:click="edit({{ $record->id }})" @disabled(!$mutationsEnabled) class="mr-3 font-semibold text-cyan-700 disabled:opacity-50">Edit</button>
                                <button type="button" wire:click="delete({{ $record->id }})" wire:confirm="Delete this exam half?" @disabled(!$mutationsEnabled) class="font-semibold text-rose-600 disabled:opacity-50">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-3 py-8 text-center text-sm text-slate-500">No halves are set up for this exam combination.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    @if ($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/50 px-4 py-8" role="presentation">
            <div role="dialog" aria-modal="true" aria-labelledby="exam-half-modal-title" class="mx-auto max-w-2xl rounded-lg bg-white shadow-xl">
                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                    <div>
                        <h3 id="exam-half-modal-title" class="text-base font-semibold text-slate-900">{{ $recordId ? 'Edit exam half' : 'Add exam half' }}</h3>
                        <p class="mt-0.5 text-xs text-slate-500">{{ $combinations->firstWhere('key', $selectedCombinationKey)['label'] ?? '' }}</p>
                    </div>
                    <button type="button" wire:click="$set('showModal', false)" aria-label="Close dialog" class="text-xl leading-none text-slate-400 hover:text-slate-700">&times;</button>
                </div>

                <form wire:submit="save" class="space-y-4 p-5">
                    <div class="grid gap-3 sm:grid-cols-2">
                        <label class="text-xs font-semibold text-slate-600">Half name
                            <input wire:model="name" type="text" maxlength="255" class="exam-half-field" placeholder="Morning session">
                            @error('name')<span class="exam-half-error">{{ $message }}</span>@enderror
                        </label>
                        <label class="text-xs font-semibold text-slate-600">Order
                            <input wire:model="order_id" type="number" min="0" class="exam-half-field" placeholder="Optional">
                            @error('order_id')<span class="exam-half-error">{{ $message }}</span>@enderror
                        </label>
                    </div>

                    <label class="block text-xs font-semibold text-slate-600">Description
                        <input wire:model="description" type="text" maxlength="255" class="exam-half-field" placeholder="Optional details">
                        @error('description')<span class="exam-half-error">{{ $message }}</span>@enderror
                    </label>

                    <div class="grid gap-3 sm:grid-cols-2">
                        <label class="text-xs font-semibold text-slate-600">Start time
                            <input wire:model.live="start_time" type="time" class="exam-half-field">
                            @error('start_time')<span class="exam-half-error">{{ $message }}</span>@enderror
                        </label>
                        <label class="text-xs font-semibold text-slate-600">End time
                            <input wire:model.live="end_time" type="time" class="exam-half-field">
                            @error('end_time')<span class="exam-half-error">{{ $message }}</span>@enderror
                        </label>
                    </div>
                    <div aria-live="polite" class="rounded-md border border-cyan-100 bg-cyan-50 px-3 py-2 text-xs text-cyan-950">
                        <span class="font-semibold">Time difference:</span>
                        {{ $durationPreview ?? 'Enter both times to calculate duration.' }}
                        @if ($start_time && $end_time && $start_time > $end_time)<span class="ml-1 text-cyan-800">(ends the following day)</span>@endif
                    </div>

                    <fieldset>
                        <legend class="mb-2 text-xs font-semibold text-slate-700">Available exam days</legend>
                        <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">
                            @foreach ($days as $day)
                                <label wire:key="exam-day-{{ $day }}" class="flex cursor-pointer items-center gap-2 rounded border border-slate-200 px-2.5 py-2 text-xs text-slate-700 hover:bg-slate-50">
                                    <input wire:model="active_exam_days" type="checkbox" value="{{ $day }}" class="rounded border-slate-300 text-cyan-700 focus:ring-cyan-600">
                                    <span>{{ $day }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('active_exam_days')<span class="exam-half-error">{{ $message }}</span>@enderror
                        @error('active_exam_days.*')<span class="exam-half-error">{{ $message }}</span>@enderror
                    </fieldset>

                    <label class="block text-xs font-semibold text-slate-600">Remarks
                        <textarea wire:model="remarks" rows="2" maxlength="255" class="exam-half-field"></textarea>
                        @error('remarks')<span class="exam-half-error">{{ $message }}</span>@enderror
                    </label>

                    <label class="inline-flex items-center gap-2 text-xs font-medium text-slate-700">
                        <input wire:model="is_active" type="checkbox" class="rounded border-slate-300 text-cyan-700 focus:ring-cyan-600">
                        Active
                    </label>

                    <div class="flex justify-end gap-2 border-t border-slate-100 pt-4">
                        <button type="button" wire:click="$set('showModal', false)" class="rounded-md border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">Cancel</button>
                        <button type="submit" class="rounded-md bg-cyan-700 px-4 py-2 text-xs font-semibold text-white hover:bg-cyan-600">{{ $recordId ? 'Save changes' : 'Create half' }}</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <style>
        .exam-half-field { display: block; width: 100%; margin-top: .3rem; border: 1px solid #cbd5e1; border-radius: .375rem; background: #fff; padding: .5rem .65rem; font-size: .75rem; }
        .exam-half-error { display: block; margin-top: .25rem; color: #e11d48; font-size: .7rem; }
    </style>
</section>