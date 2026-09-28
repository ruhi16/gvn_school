<div class="space-y-5">
    <header class="flex flex-wrap items-end justify-between gap-3 border-b border-slate-200 pb-5">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-cyan-600">Administration</p>
            <h1 class="mt-1 text-2xl font-semibold tracking-tight text-slate-950">StudentDB</h1>
            <p class="mt-1 text-sm text-slate-500">Compact student admission records.</p>
        </div>
        <a href="{{ route('admin.students.create') }}"
            @if(!$mutationsEnabled) aria-disabled="true" onclick="event.preventDefault()" @endif
            class="rounded bg-cyan-600 px-3 py-2 text-xs font-semibold text-white hover:bg-cyan-500">+ New admission</a>
        <button type="button" wire:click="toggleMutations" role="switch" aria-checked="{{ $mutationsEnabled ? 'true' : 'false' }}" class="rounded border px-3 py-2 text-xs font-semibold {{ $mutationsEnabled ? 'border-emerald-300 bg-emerald-50 text-emerald-700' : 'border-slate-300 text-slate-600' }}">{{ $mutationsEnabled ? 'Editing enabled' : 'Enable editing' }}</button>
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
                                    src="{{ asset('storage/' . ltrim($student->dp_img_ref, '/')) }}"
                                    class="h-full w-full object-cover" alt="">@else{{ strtoupper(substr($student->name,
                                0, 1)) }}@endif</div>
                            <div>
                                <div class="font-semibold text-slate-900">{{ $student->name }}</div>
                                <div class="text-[10px] text-slate-500">#{{ $student->id }} · {{ $student->district ?:
                                    'District not set' }}</div>
                                <details class="mt-2 max-w-[700px]">
                                    <summary class="cursor-pointer text-[10px] font-semibold text-cyan-700">Full admission record</summary>
                                    @php
                                        $studentFields = [
                                            'Mother' => $student->mname,
                                            'APAAR ID' => $student->apper_id,
                                            'Village' => $student->village,
                                            'Post office' => $student->post_office,
                                            'Police station' => $student->police_station,
                                            'Block' => $student->block,
                                            'Pincode' => $student->pincode,
                                            'State' => $student->state,
                                            'Nationality' => $student->nationality,
                                            'Mobile 2' => $student->mobile_2,
                                            'Admission Shreny ID' => $student->adm_shreny_id,
                                            'Admission Section ID' => $student->adm_section_id,
                                            'Order' => $student->order_id,
                                            'School ID' => $student->school_id,
                                            'Session ID' => $student->session_id,
                                            'Editable' => $student->is_editable ? 'Yes' : 'No',
                                            'Deleted' => $student->is_deleted ? 'Yes' : 'No',
                                            'Finalized' => $student->is_finalized ? 'Yes' : 'No',
                                            'Remarks' => $student->remarks,
                                            'Created' => $student->created_at?->toDateTimeString(),
                                            'Updated' => $student->updated_at?->toDateTimeString(),
                                        ];
                                        $studentImages = [
                                            'Profile photo' => $student->dp_img_ref,
                                            'DOB certificate' => $student->dob_cert_img_ref,
                                            'Aadhaar image' => $student->aadhaar_img_ref,
                                        ];
                                    @endphp
                                    <dl class="mt-2 grid gap-x-4 gap-y-1 sm:grid-cols-2 lg:grid-cols-3">
                                        @foreach ($studentFields as $field => $value)
                                            <div class="min-w-0 text-[10px]">
                                                <dt class="font-semibold text-slate-500">{{ $field }}</dt>
                                                <dd class="break-words text-slate-700">{{ $value ?: '—' }}</dd>
                                            </div>
                                        @endforeach
                                        @foreach ($studentImages as $field => $path)
                                            <div class="min-w-0 text-[10px]">
                                                <dt class="font-semibold text-slate-500">{{ $field }}</dt>
                                                <dd class="break-all">
                                                    @if ($path)
                                                        <a href="{{ asset('storage/' . ltrim($path, '/')) }}" target="_blank" class="text-cyan-700">{{ $path }}</a>
                                                    @else
                                                        <span class="text-slate-700">—</span>
                                                    @endif
                                                </dd>
                                            </div>
                                        @endforeach
                                    </dl>
                                </details>
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