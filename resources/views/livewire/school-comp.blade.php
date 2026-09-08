<div>
    <div class="mb-4 flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
        <div><h2 class="text-base font-semibold tracking-tight">Schools</h2><p class="text-xs text-slate-500">Manage registered schools and location details.</p></div>
        <div class="flex gap-2"><input wire:model.live.debounce.300ms="search" type="search" placeholder="Search schools..." class="w-52 rounded-md border-slate-300 px-3 py-2 text-xs shadow-sm focus:border-cyan-500 focus:ring-cyan-500"><button wire:click="create" type="button" class="rounded-md bg-slate-900 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-700">+ Add school</button></div>
    </div>

    @if (session('success'))
        <div class="mb-3 rounded-md border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs text-emerald-700">{{ session('success') }}</div>
    @endif

    <div class="overflow-x-auto rounded-md border border-slate-200">
        <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
            <thead class="bg-slate-50 text-[10px] uppercase tracking-wider text-slate-500"><tr><th class="px-3 py-2 font-semibold">School</th><th class="px-3 py-2 font-semibold">UDISE / DISE</th><th class="px-3 py-2 font-semibold">Location</th><th class="px-3 py-2 font-semibold">Status</th><th class="px-3 py-2 text-right font-semibold">Actions</th></tr></thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse ($schools as $school)
                    <tr wire:key="school-{{ $school->id }}" class="hover:bg-slate-50"><td class="whitespace-nowrap px-3 py-2.5"><p class="font-semibold text-slate-800">{{ $school->name }}</p><p class="text-[11px] text-slate-500">{{ $school->school_type ?: 'School' }}</p></td><td class="whitespace-nowrap px-3 py-2.5 text-slate-600">{{ $school->udise_code ?: '—' }}<span class="text-slate-400"> / {{ $school->dise_code ?: '—' }}</span></td><td class="px-3 py-2.5 text-slate-600">{{ collect([$school->vill, $school->district])->filter()->join(', ') ?: '—' }}</td><td class="px-3 py-2.5"><span class="rounded-full px-2 py-1 text-[10px] font-semibold {{ $school->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">{{ $school->is_active ? 'Active' : 'Inactive' }}</span></td><td class="whitespace-nowrap px-3 py-2.5 text-right"><button wire:click="edit({{ $school->id }})" class="mr-2 font-semibold text-cyan-700 hover:text-cyan-900">Edit</button><button wire:click="delete({{ $school->id }})" wire:confirm="Delete this school?" class="font-semibold text-rose-600 hover:text-rose-800">Delete</button></td></tr>
                @empty
                    <tr><td colspan="5" class="px-3 py-10 text-center text-xs text-slate-500">No schools found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $schools->links() }}</div>

    @if ($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/50 px-4 py-8" wire:keydown.escape="resetForm">
            <div class="mx-auto max-w-2xl rounded-xl bg-white shadow-xl" wire:click.stop>
                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4"><div><h3 class="text-base font-semibold">{{ $schoolId ? 'Edit school' : 'Add school' }}</h3><p class="text-xs text-slate-500">Enter the school identity and address details.</p></div><button wire:click="$set('showModal', false)" class="text-xl leading-none text-slate-400 hover:text-slate-700" aria-label="Close">&times;</button></div>
                <form wire:submit="save" class="space-y-4 p-5">
                    <div class="grid gap-3 sm:grid-cols-2"><div class="sm:col-span-2"><label class="field-label">School name *</label><input wire:model="name" class="field-input">@error('name')<span class="field-error">{{ $message }}</span>@enderror</div><div><label class="field-label">UDISE code</label><input wire:model="udise_code" class="field-input"></div><div><label class="field-label">DISE code</label><input wire:model="dise_code" class="field-input"></div><div><label class="field-label">School type</label><input wire:model="school_type" class="field-input" placeholder="Primary, High..." ></div><div><label class="field-label">Village / town</label><input wire:model="vill" class="field-input"></div><div><label class="field-label">Post office</label><input wire:model="post_office" class="field-input"></div><div><label class="field-label">Police station</label><input wire:model="police_station" class="field-input"></div><div><label class="field-label">District</label><input wire:model="district" class="field-input"></div><div><label class="field-label">Block</label><input wire:model="block" class="field-input"></div><div><label class="field-label">Pincode</label><input wire:model="pincode" class="field-input"></div><div class="flex items-end pb-2"><label class="flex items-center gap-2 text-xs font-medium text-slate-700"><input wire:model="is_active" type="checkbox" class="rounded border-slate-300 text-cyan-600 focus:ring-cyan-500"> Active school</label></div><div class="sm:col-span-2"><label class="field-label">Remarks</label><textarea wire:model="remarks" rows="2" class="field-input"></textarea></div></div>
                    <div class="flex justify-end gap-2 border-t border-slate-100 pt-4"><button type="button" wire:click="$set('showModal', false)" class="rounded-md border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-600">Cancel</button><button type="submit" class="rounded-md bg-cyan-600 px-4 py-2 text-xs font-semibold text-white hover:bg-cyan-700" wire:loading.attr="disabled">Save school</button></div>
                </form>
            </div>
        </div>
    @endif
    <style>.field-label{display:block;font-size:.7rem;font-weight:600;color:#475569;margin-bottom:.3rem}.field-input{width:100%;border-radius:.375rem;border-color:#cbd5e1;padding:.5rem .65rem;font-size:.75rem;box-shadow:0 1px 2px rgb(15 23 42/.04)}.field-error{display:block;color:#e11d48;font-size:.7rem;margin-top:.25rem}</style>
</div>
