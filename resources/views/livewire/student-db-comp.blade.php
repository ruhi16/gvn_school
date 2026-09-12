<div class="space-y-5">
    <header class="flex flex-wrap items-end justify-between gap-3 border-b border-slate-200 pb-5">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-cyan-600">Administration</p>
            <h1 class="mt-1 text-2xl font-semibold tracking-tight text-slate-950">StudentDB</h1>
            <p class="mt-1 text-sm text-slate-500">Compact student admission records.</p>
        </div>
        <a href="{{ route('admin.students.create') }}"
            class="rounded bg-cyan-600 px-3 py-2 text-xs font-semibold text-white hover:bg-cyan-500">+ New admission</a>
    </header>

    @if (session('success'))
    <div class="rounded border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs text-emerald-700">{{
        session('success') }}</div>
    @endif

    <div class="flex items-center justify-between gap-3">
        <input wire:model.live.debounce.300ms="search" type="search"
            placeholder="Search name, parent, Aadhaar, PEN or mobile..."
            class="w-full max-w-xl rounded border-slate-300 px-3 py-2 text-xs shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
        <span class="text-xs text-slate-500">{{ $students->total() }} records</span>
    </div>

    <div class="overflow-x-auto rounded border border-slate-200 bg-white shadow-sm">
        <table class="w-full min-w-[900px] text-left text-xs">
            <thead class="border-b border-slate-200 bg-slate-50 text-[10px] uppercase tracking-wider text-slate-500">
                <tr>
                    <th class="px-3 py-2">Student</th>
                    <th class="px-3 py-2">Parent</th>
                    <th class="px-3 py-2">DOB / Gender</th>
                    <th class="px-3 py-2">Contact</th>
                    <th class="px-3 py-2">Identifiers</th>
                    <th class="px-3 py-2">Status</th>
                    <th class="px-3 py-2"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($students as $student)
                <tr class="hover:bg-cyan-50/40">
                    <td class="px-3 py-2">
                        <div class="flex items-center gap-2">
                            <div
                                class="grid h-9 w-9 shrink-0 place-items-center overflow-hidden rounded bg-slate-100 text-[10px] font-bold text-slate-400">
                                @if($student->dp_img_ref)<img
                                    src="{{ Storage::disk('public')->url($student->dp_img_ref) }}"
                                    class="h-full w-full object-cover" alt="">@else{{ strtoupper(substr($student->name,
                                0, 1)) }}@endif</div>
                            <div>
                                <div class="font-semibold text-slate-900">{{ $student->name }}</div>
                                <div class="text-[10px] text-slate-500">#{{ $student->id }} · {{ $student->district ?:
                                    'District not set' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-3 py-2 text-slate-600">{{ $student->fname ?: '-' }}<br><span class="text-[10px]">{{
                            $student->mname ?: '' }}</span></td>
                    <td class="px-3 py-2 text-slate-600">{{ $student->dob?->format('d M Y') ?: '-' }}<br>{{
                        $student->gender ?: '-' }}</td>
                    <td class="px-3 py-2 text-slate-600">{{ $student->mobile_1 ?: '-' }}<br>{{ $student->email ?: '' }}
                    </td>
                    <td class="px-3 py-2 text-slate-600">Aadhaar: {{ $student->aadhaar_id ?: '-' }}<br>PEN: {{
                        $student->pen_id ?: '-' }}</td>
                    <td class="px-3 py-2"><span
                            class="rounded px-2 py-1 text-[10px] font-semibold {{ $student->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">{{
                            $student->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td class="px-3 py-2 text-right"><a href="{{ route('admin.students.edit', $student) }}"
                            class="font-semibold text-cyan-700 hover:text-cyan-500">Edit</a><button
                            wire:click="delete({{ $student->id }})" wire:confirm="Delete this student?"
                            class="ml-3 font-semibold text-rose-600 hover:text-rose-500">Delete</button></td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-3 py-10 text-center text-sm text-slate-500">No student records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $students->links() }}
</div>