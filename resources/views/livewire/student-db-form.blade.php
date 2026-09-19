<div class="space-y-5">
    <header class="flex flex-wrap items-end justify-between gap-3 border-b border-slate-200 pb-5">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-cyan-600">StudentDB</p>
            <h1 class="mt-1 text-2xl font-semibold tracking-tight text-slate-950">{{ $studentId ? 'Edit student' : 'New
                admission' }}</h1>
            <p class="mt-1 text-sm text-slate-500">Keep the admission record complete and easy to scan.</p>
        </div>
        <a href="{{ route('admin.students') }}"
            class="rounded border border-slate-300 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">Back
            to StudentDB</a>
        <button type="button" wire:click="toggleMutations" role="switch" aria-checked="{{ $mutationsEnabled ? 'true' : 'false' }}" class="rounded border px-3 py-2 text-xs font-semibold {{ $mutationsEnabled ? 'border-emerald-300 bg-emerald-50 text-emerald-700' : 'border-slate-300 text-slate-600' }}">{{ $mutationsEnabled ? 'Editing enabled' : 'Enable editing' }}</button>
    </header>

    <form wire:submit="save" class="space-y-4">
        @include('livewire.partials.student-db-fields')
        <div class="flex items-center justify-end gap-3 border-t border-slate-200 pt-4"><a
                href="{{ route('admin.students') }}"
                class="px-3 py-2 text-xs font-semibold text-slate-600">Cancel</a><button type="submit"
                @disabled(!$mutationsEnabled) class="rounded bg-cyan-600 px-4 py-2 text-xs font-semibold text-white hover:bg-cyan-500"
                wire:loading.attr="disabled">Save student</button></div>
    </form>
</div>