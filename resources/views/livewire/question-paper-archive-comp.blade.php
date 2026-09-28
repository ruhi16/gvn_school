<div class="space-y-4">
    <header class="flex flex-wrap items-end justify-between gap-3 border-b border-slate-200 pb-4">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-cyan-700">Exam resources</p>
            <h2 class="mt-1 text-lg font-semibold text-slate-950">Question paper archive</h2>
            <p class="mt-1 text-xs text-slate-500">{{ $session?->name ?? 'No active session' }}</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <button type="button" wire:click="toggleMutations" role="switch" aria-checked="{{ $mutationsEnabled ? 'true' : 'false' }}" class="rounded border px-3 py-2 text-xs font-semibold {{ $mutationsEnabled ? 'border-emerald-300 bg-emerald-50 text-emerald-700' : 'border-slate-300 text-slate-600' }}">{{ $mutationsEnabled ? 'Editing enabled' : 'Enable editing' }}</button>
            <button type="button" wire:click="create" @disabled(!$mutationsEnabled || !$session || !$selectedShreny) class="rounded-md bg-cyan-700 px-3 py-2 text-xs font-semibold text-white disabled:opacity-50">Add question paper</button>
        </div>
    </header>

    @if (session('success'))<div role="status" class="rounded-md border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs text-emerald-800">{{ session('success') }}</div>@endif
    @if (session('error'))<div role="alert" class="rounded-md border border-amber-200 bg-amber-50 px-3 py-2 text-xs text-amber-800">{{ session('error') }}</div>@endif
    @unless ($session)<div class="rounded-md border border-amber-200 bg-amber-50 px-3 py-2 text-xs text-amber-800">An active school session is required to manage archives.</div>@endunless

    @if ($shrenies->isNotEmpty())
        <div class="flex flex-wrap gap-1 border-b border-slate-200" role="tablist" aria-label="Classes">
            @foreach ($shrenies as $shreny)
                <button type="button" role="tab" aria-selected="{{ $selectedShreny?->id === $shreny->id ? 'true' : 'false' }}" wire:click="selectShreny({{ $shreny->id }})" class="border-b-2 px-3 py-2 text-xs font-semibold {{ $selectedShreny?->id === $shreny->id ? 'border-cyan-700 text-cyan-800' : 'border-transparent text-slate-500 hover:text-slate-900' }}">{{ $shreny->name }}</button>
            @endforeach
        </div>
    @else
        <div class="rounded-md border border-dashed border-slate-300 bg-white px-4 py-8 text-center text-sm text-slate-500">No active classes are configured.</div>
    @endif

    @if ($selectedShreny)
        <div class="overflow-x-auto rounded-md border border-slate-200 bg-white">
            <table class="min-w-full text-left text-xs">
                <thead class="bg-slate-50 text-[10px] uppercase tracking-wider text-slate-500"><tr><th class="px-3 py-2">Exam combination</th><th class="px-3 py-2">Subject</th><th class="px-3 py-2">Files</th><th class="px-3 py-2">Status</th><th class="px-3 py-2 text-right">Actions</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($archives as $archive)
                        <tr wire:key="question-paper-{{ $archive->id }}" class="hover:bg-slate-50">
                            <td class="px-3 py-2.5"><p class="font-semibold text-slate-800">{{ $archive->examName?->name }} / {{ $archive->examType?->name }} / {{ $archive->examPart?->name }}</p><p class="text-slate-500">{{ $archive->name }}</p></td>
                            <td class="px-3 py-2.5 text-slate-700">{{ $archive->subject?->name ?? '—' }}</td>
                            <td class="whitespace-nowrap px-3 py-2.5">@if ($archive->question_paper_pdf_ref)<a href="{{ Storage::url($archive->question_paper_pdf_ref) }}" target="_blank" rel="noopener noreferrer" class="mr-2 font-semibold text-rose-700">PDF</a>@endif @if ($archive->question_paper_img_ref)<a href="{{ Storage::url($archive->question_paper_img_ref) }}" target="_blank" rel="noopener noreferrer" class="font-semibold text-cyan-700">Image</a>@endif</td>
                            <td class="px-3 py-2.5"><span class="rounded px-2 py-1 text-[10px] font-semibold {{ $archive->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">{{ $archive->is_active ? 'Active' : 'Inactive' }}</span></td>
                            <td class="whitespace-nowrap px-3 py-2.5 text-right"><button type="button" wire:click="toggleActive({{ $archive->id }})" @disabled(!$mutationsEnabled) class="mr-2 font-semibold text-amber-700 disabled:opacity-50">{{ $archive->is_active ? 'Deactivate' : 'Activate' }}</button><button type="button" wire:click="edit({{ $archive->id }})" @disabled(!$mutationsEnabled) class="mr-2 font-semibold text-cyan-700 disabled:opacity-50">Edit</button><button type="button" wire:click="delete({{ $archive->id }})" wire:confirm="Delete this question paper and its uploaded files?" @disabled(!$mutationsEnabled) class="font-semibold text-rose-600 disabled:opacity-50">Delete</button></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-3 py-8 text-center text-slate-500">No question papers for {{ $selectedShreny->name }} in this session.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    @if ($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/50 px-4 py-8" wire:click.self="$set('showModal', false)">
            <section role="dialog" aria-modal="true" aria-labelledby="question-paper-modal-title" class="mx-auto max-w-2xl rounded-lg bg-white shadow-xl">
                <header class="flex items-center justify-between border-b border-slate-200 px-5 py-4"><div><h3 id="question-paper-modal-title" class="text-base font-semibold">{{ $recordId ? 'Edit question paper' : 'Add question paper' }}</h3><p class="text-xs text-slate-500">{{ $selectedShreny?->name }} · {{ $session?->name }}</p></div><button type="button" wire:click="$set('showModal', false)" aria-label="Close" class="text-xl text-slate-400">&times;</button></header>
                <form wire:submit="save" enctype="multipart/form-data" class="space-y-3 p-5">
                    <label class="block text-xs font-semibold text-slate-600">Exam combination<select wire:model.live="selectedCombinationKey" class="archive-field"><option value="">Choose exam combination</option>@foreach ($combinations as $combination)@php($combinationKey = $combination->exam_name_id . ':' . $combination->exam_type_id . ':' . $combination->exam_part_id)<option value="{{ $combinationKey }}">{{ $examNames[$combination->exam_name_id]?->name }} / {{ $examTypes[$combination->exam_type_id]?->name }} / {{ $examParts[$combination->exam_part_id]?->name }}</option>@endforeach</select>@error('selectedCombinationKey')<span class="archive-error">{{ $message }}</span>@enderror</label>
                    <label class="block text-xs font-semibold text-slate-600">Subject<select wire:model="selectedSubjectId" class="archive-field"><option value="">Choose subject</option>@foreach ($subjects as $subject)<option value="{{ $subject->id }}">{{ $subject->name }}</option>@endforeach</select>@error('selectedSubjectId')<span class="archive-error">{{ $message }}</span>@enderror</label>
                    <label class="block text-xs font-semibold text-slate-600">Paper title<input wire:model="name" type="text" maxlength="255" class="archive-field">@error('name')<span class="archive-error">{{ $message }}</span>@enderror</label>
                    <label class="block text-xs font-semibold text-slate-600">Description<textarea wire:model="description" rows="2" maxlength="255" class="archive-field"></textarea>@error('description')<span class="archive-error">{{ $message }}</span>@enderror</label>
                    <div class="grid gap-3 sm:grid-cols-2">
                        <label class="block text-xs font-semibold text-slate-600">PDF (max 20 MB)<input wire:model="pdf" type="file" accept="application/pdf" class="archive-field">@error('pdf')<span class="archive-error">{{ $message }}</span>@enderror</label>
                        <label class="block text-xs font-semibold text-slate-600">Paper image (max 10 MB)<input wire:model="image" type="file" accept="image/*" class="archive-field">@error('image')<span class="archive-error">{{ $message }}</span>@enderror</label>
                    </div>
                    <label class="block text-xs font-semibold text-slate-600">Remarks<textarea wire:model="remarks" rows="2" maxlength="255" class="archive-field"></textarea>@error('remarks')<span class="archive-error">{{ $message }}</span>@enderror</label>
                    <label class="flex items-center gap-2 text-xs text-slate-700"><input wire:model="is_active" type="checkbox" class="rounded border-slate-300 text-cyan-700"> Active and visible</label>
                    <div class="flex justify-end gap-2 border-t border-slate-100 pt-4"><button type="button" wire:click="$set('showModal', false)" class="rounded-md border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-600">Cancel</button><button type="submit" wire:loading.attr="disabled" class="rounded-md bg-cyan-700 px-4 py-2 text-xs font-semibold text-white">Save archive</button></div>
                </form>
            </section>
        </div>
    @endif
    <style>.archive-field{display:block;width:100%;margin-top:.3rem;border:1px solid #cbd5e1;border-radius:.375rem;background:#fff;padding:.5rem .65rem;font-size:.75rem}.archive-error{display:block;margin-top:.25rem;color:#e11d48;font-size:.7rem}</style>
</div>