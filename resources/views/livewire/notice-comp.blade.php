<div>
    <div class="mb-4 flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
        <div>
            <h2 class="text-base font-semibold tracking-tight">Notices</h2>
            <p class="text-xs text-slate-500">Publish dated announcements with one image and one PDF attachment.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <input wire:model.live.debounce.300ms="search" type="search" placeholder="Search notices..." class="w-52 rounded-md border-slate-300 px-3 py-2 text-xs shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
            <button type="button" wire:click="toggleMutations" role="switch" aria-checked="{{ $mutationsEnabled ? 'true' : 'false' }}" class="rounded border px-3 py-2 text-xs font-semibold {{ $mutationsEnabled ? 'border-emerald-300 bg-emerald-50 text-emerald-700' : 'border-slate-300 text-slate-600' }}">{{ $mutationsEnabled ? 'Editing enabled' : 'Enable editing' }}</button>
            <button wire:click="create" type="button" @disabled(!$mutationsEnabled) class="rounded-md bg-slate-900 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-700">+ Add notice</button>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-3 rounded-md border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs text-emerald-700">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-3 rounded-md border border-rose-200 bg-rose-50 px-3 py-2 text-xs text-rose-700">{{ session('error') }}</div>
    @endif

    <div class="overflow-x-auto rounded-md border border-slate-200">
        <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
            <thead class="bg-slate-50 text-[10px] uppercase tracking-wider text-slate-500">
                <tr>
                    <th class="px-3 py-2 font-semibold">Notice</th>
                    <th class="px-3 py-2 font-semibold">Upload date</th>
                    <th class="px-3 py-2 font-semibold">Activation</th>
                    <th class="px-3 py-2 font-semibold">Expiry</th>
                    <th class="px-3 py-2 font-semibold">Files</th>
                    <th class="px-3 py-2 font-semibold">Status</th>
                    <th class="px-3 py-2 text-right font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse ($notices as $notice)
                    <tr wire:key="notice-{{ $notice->id }}" class="hover:bg-slate-50">
                        <td class="max-w-sm px-3 py-2.5"><p class="font-semibold text-slate-800">{{ $notice->title }}</p><p class="line-clamp-2 text-[11px] text-slate-500">{{ $notice->description ?: '—' }}</p></td>
                        <td class="whitespace-nowrap px-3 py-2.5 text-slate-600">{{ $notice->upload_dt?->format('d M Y') ?: '—' }}</td>
                        <td class="whitespace-nowrap px-3 py-2.5 text-slate-600">{{ $notice->active_dt?->format('d M Y') ?: 'Immediately' }}</td>
                        <td class="whitespace-nowrap px-3 py-2.5 text-slate-600">{{ $notice->expiry_dt?->format('d M Y') ?: 'No expiry' }}</td>
                        <td class="whitespace-nowrap px-3 py-2.5">
                            @if ($notice->notice_img_ref)<a href="{{ Storage::url($notice->notice_img_ref) }}" target="_blank" rel="noopener noreferrer" class="mr-2 font-semibold text-cyan-700 hover:text-cyan-900">Image</a>@endif
                            @if ($notice->notice_pdf_ref)<a href="{{ Storage::url($notice->notice_pdf_ref) }}" target="_blank" rel="noopener noreferrer" class="font-semibold text-rose-600 hover:text-rose-800">PDF</a>@endif
                            @if (!$notice->notice_img_ref && !$notice->notice_pdf_ref)<span class="text-slate-400">—</span>@endif
                        </td>
                        <td class="whitespace-nowrap px-3 py-2.5"><span class="rounded-full px-2 py-1 text-[10px] font-semibold {{ $notice->is_issued ? 'bg-emerald-50 text-emerald-700' : ($notice->is_active ? 'bg-amber-50 text-amber-700' : 'bg-slate-100 text-slate-500') }}">{{ $notice->is_issued ? 'Issued' : ($notice->is_active ? 'Scheduled' : 'Inactive') }}</span>@if ($notice->is_finalized)<span class="ml-1 rounded-full bg-cyan-50 px-2 py-1 text-[10px] font-semibold text-cyan-700">Final</span>@endif</td>
                        <td class="whitespace-nowrap px-3 py-2.5 text-right"><button wire:click="edit({{ $notice->id }})" class="mr-2 font-semibold text-cyan-700 hover:text-cyan-900">Edit</button><button wire:click="delete({{ $notice->id }})" wire:confirm="Delete this notice and its files?" class="font-semibold text-rose-600 hover:text-rose-800">Delete</button></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-3 py-10 text-center text-xs text-slate-500">No notices found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $notices->links() }}</div>

    @if ($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/50 px-4 py-8" wire:keydown.escape="resetForm">
            <div class="mx-auto max-w-3xl rounded-xl bg-white shadow-xl" wire:click.stop>
                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4"><div><h3 class="text-base font-semibold">{{ $recordId ? 'Edit notice' : 'Add notice' }}</h3><p class="text-xs text-slate-500">Set the dates and optional notice attachments.</p></div><button wire:click="$set('showModal', false)" class="text-xl leading-none text-slate-400 hover:text-slate-700" aria-label="Close">&times;</button></div>
                <form wire:submit="save" enctype="multipart/form-data" class="space-y-4 p-5">
                    <div class="grid gap-3 sm:grid-cols-2">
                        <div class="sm:col-span-2"><label class="field-label">Title *</label><input wire:model="title" class="field-input">@error('title')<span class="field-error">{{ $message }}</span>@enderror</div>
                        <div class="sm:col-span-2"><label class="field-label">Description</label><textarea wire:model="description" rows="3" class="field-input"></textarea>@error('description')<span class="field-error">{{ $message }}</span>@enderror</div>
                        <div><label class="field-label">Upload date</label><input wire:model="upload_dt" type="date" class="field-input">@error('upload_dt')<span class="field-error">{{ $message }}</span>@enderror</div>
                        <div><label class="field-label">Activation date</label><input wire:model="active_dt" type="date" class="field-input">@error('active_dt')<span class="field-error">{{ $message }}</span>@enderror</div>
                        <div><label class="field-label">Expiry date</label><input wire:model="expiry_dt" type="date" class="field-input">@error('expiry_dt')<span class="field-error">{{ $message }}</span>@enderror</div>
                        <div><label class="field-label">Display order</label><input wire:model="order_id" type="number" class="field-input">@error('order_id')<span class="field-error">{{ $message }}</span>@enderror</div>
                        <div><label class="field-label">Notice image <span class="font-normal text-slate-400">(optional, max 5 MB)</span></label><input wire:model="notice_img_ref" type="file" accept="image/*" class="field-input">@error('notice_img_ref')<span class="field-error">{{ $message }}</span>@enderror</div>
                        <div><label class="field-label">Notice PDF <span class="font-normal text-slate-400">(optional, max 10 MB)</span></label><input wire:model="notice_pdf_ref" type="file" accept="application/pdf" class="field-input">@error('notice_pdf_ref')<span class="field-error">{{ $message }}</span>@enderror</div>
                        <div class="flex items-end gap-4 pb-2"><label class="flex items-center gap-2 text-xs font-medium text-slate-700"><input wire:model="is_active" type="checkbox" class="rounded border-slate-300 text-cyan-600 focus:ring-cyan-500"> Active</label><label class="flex items-center gap-2 text-xs font-medium text-slate-700"><input wire:model="is_finalized" type="checkbox" class="rounded border-slate-300 text-cyan-600 focus:ring-cyan-500"> Finalized</label></div>
                        <div class="flex items-end pb-2"><span class="text-xs text-slate-500">Issued flag: <strong class="text-slate-700">automatic</strong></span></div>
                        <div class="sm:col-span-2"><label class="field-label">Remarks</label><textarea wire:model="remarks" rows="2" class="field-input"></textarea>@error('remarks')<span class="field-error">{{ $message }}</span>@enderror</div>
                    </div>
                    <div class="flex justify-end gap-2 border-t border-slate-100 pt-4"><button type="button" wire:click="$set('showModal', false)" class="rounded-md border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-600">Cancel</button><button type="submit" class="rounded-md bg-cyan-600 px-4 py-2 text-xs font-semibold text-white hover:bg-cyan-700" wire:loading.attr="disabled">Save notice</button></div>
                </form>
            </div>
        </div>
    @endif
    <style>.field-label{display:block;font-size:.7rem;font-weight:600;color:#475569;margin-bottom:.3rem}.field-input{width:100%;border-radius:.375rem;border-color:#cbd5e1;padding:.5rem .65rem;font-size:.75rem;box-shadow:0 1px 2px rgb(15 23 42/.04)}.field-error{display:block;color:#e11d48;font-size:.7rem;margin-top:.25rem}</style>
</div>
