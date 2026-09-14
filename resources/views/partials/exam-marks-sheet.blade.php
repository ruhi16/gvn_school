@php
$address = collect([$school?->vill, $school?->post_office, $school?->police_station, $school?->district, $school?->pincode])->filter()->implode(', ');
$overallTotal = 0;
$overallFullMarks = 0;
@endphp
<div class="space-y-5 {{ $pdf ? 'sheet-pdf' : '' }}">
    <header class="border-b-2 border-slate-900 pb-4 text-center">
        @if (!$pdf && file_exists(public_path('storage/logo.png')))
        <img src="{{ asset('storage/logo.png') }}" alt="School logo" class="mx-auto mb-2 h-16 w-16 object-contain">
        @endif
        <h1 class="text-3xl font-bold uppercase tracking-wide text-slate-950">{{ $school?->name ?? 'School' }}</h1>
        @if ($address)<p class="mt-1 text-sm text-slate-600">{{ $address }}</p>@endif
        @if ($school?->udise_code || $school?->dise_code)<p class="text-xs text-slate-500">UDISE: {{ $school?->udise_code ?? $school?->dise_code }}</p>@endif
        <p class="mt-3 text-base font-semibold uppercase">Session: {{ $session?->name }} | Final Progress Report</p>
    </header>

    <section class="grid grid-cols-2 gap-x-8 gap-y-2 border border-slate-300 p-3 text-sm sm:grid-cols-4">
        <div><strong>Student:</strong> {{ $student?->name }}</div>
        <div><strong>Father:</strong> {{ $student?->fname ?? '-' }}</div>
        <div><strong>Shreny:</strong> {{ $shreny?->name ?? '-' }}</div>
        <div><strong>Section:</strong> {{ $section?->name ?? '-' }}</div>
        <div><strong>Roll No:</strong> {{ $studentCr->curr_roll_no ?? '-' }}</div>
        <div><strong>Date of Birth:</strong> {{ $student?->dob?->format('d-m-Y') ?? '-' }}</div>
        <div class="col-span-2"><strong>Address:</strong> {{ collect([$student?->village, $student?->post_office, $student?->district, $student?->state, $student?->pincode])->filter()->implode(', ') ?: '-' }}</div>
    </section>

    <div class="overflow-x-auto">
        <table class="min-w-full border-collapse border border-slate-400 text-xs">
            <thead>
                <tr class="bg-slate-100">
                    <th rowspan="2" class="border border-slate-400 px-2 py-2 text-left">Subject</th>
                    @foreach ($terms as $term)
                    <th colspan="{{ $term['parts']->count() + 1 }}" class="border border-slate-400 px-2 py-2 text-center">{{ $term['name'] }}</th>
                    @endforeach
                    <th rowspan="2" class="border border-slate-400 px-2 py-2">Final Total</th>
                    <th rowspan="2" class="border border-slate-400 px-2 py-2">Grade</th>
                </tr>
                <tr class="bg-slate-50">
                    @foreach ($terms as $term)
                    @foreach ($term['parts'] as $part)
                    <th class="border border-slate-400 px-2 py-1">{{ $examTypes[$part->exam_type_id]?->name }} / {{ $examParts[$part->exam_part_id]?->name }}<span class="block font-semibold">FM {{ $part->full_marks ?? 0 }}</span></th>
                    @endforeach
                    <th class="border border-slate-400 px-2 py-1">Term Total<span class="block font-semibold">FM {{ $term['full_marks'] }}</span></th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($reportSubjects as $reportSubject)
                @php($subjectTotal = 0)
                @php($subjectFullMarks = 0)
                @php($subjectHasMark = false)
                <tr>
                    <td class="border border-slate-400 px-2 py-2 text-left font-semibold">{{ $reportSubject['subject']->name }}<span class="block text-[10px] font-normal text-slate-500">Type {{ $reportSubject['subject']->subject_type_id ?? '-' }}</span></td>
                    @foreach ($terms as $term)
                    @php($termTotal = 0)
                    @php($termFullMarks = 0)
                    @foreach ($term['parts'] as $part)
                    @php($assignedPart = $reportSubject['assignments']->first(fn ($assignment) => $assignment->exam_name_id === $part->exam_name_id && $assignment->exam_type_id === $part->exam_type_id && $assignment->exam_part_id === $part->exam_part_id))
                    @php($mark = $assignedPart ? $registerData->mark($entries, $reportSubject['subject']->id, $assignedPart) : null)
                    @php($subjectHasMark = $subjectHasMark || $mark !== null)
                    @php($termTotal += is_numeric($mark) ? $mark : 0)
                    @php($termFullMarks += $assignedPart?->full_marks ?? 0)
                    <td class="border border-slate-400 px-2 py-2 text-center">{{ $mark ?? '-' }}</td>
                    @endforeach
                    @php($subjectTotal += $termTotal)
                    @php($subjectFullMarks += $termFullMarks)
                    <td class="border border-slate-400 bg-slate-50 px-2 py-2 text-center font-semibold">{{ $termTotal }} / {{ $termFullMarks }}</td>
                    @endforeach
                    @php($overallTotal += $subjectTotal)
                    @php($overallFullMarks += $subjectFullMarks)
                    <td class="border border-slate-400 bg-slate-50 px-2 py-2 text-center font-bold">{{ $subjectHasMark ? $subjectTotal : '-' }} / {{ $subjectFullMarks }}</td>
                    <td class="border border-slate-400 bg-slate-50 px-2 py-2 text-center font-bold">{{ $subjectHasMark ? ($registerData->grade($subjectTotal, $subjectFullMarks, $reportSubject['grades']) ?? '-') : '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <section class="grid grid-cols-2 gap-4 border-2 border-slate-900 p-3 text-center text-base font-bold sm:grid-cols-4">
        <div>Total Marks<br><span class="text-lg">{{ $overallTotal }} / {{ $overallFullMarks }}</span></div>
        <div>Percentage<br><span class="text-lg">{{ $overallFullMarks ? number_format($overallTotal / $overallFullMarks * 100, 2) : '0.00' }}%</span></div>
        <div>Overall Grade<br><span class="text-lg">{{ $registerData->grade($overallTotal, $overallFullMarks, collect()) ?? '-' }}</span></div>
        <div>Result<br><span class="text-lg">{{ $overallFullMarks && $overallTotal >= $overallFullMarks * .33 ? 'Pass' : 'Pending' }}</span></div>
    </section>

    <section class="grid gap-5 md:grid-cols-2">
        <div class="border border-slate-300 p-3">
            <h2 class="mb-2 text-sm font-bold uppercase">Seven Point Grade System</h2>
            <table class="w-full border-collapse border border-slate-300 text-xs"><thead><tr><th class="border border-slate-300 px-2 py-1">Grade</th><th class="border border-slate-300 px-2 py-1">Percentage</th></tr></thead><tbody>
                @foreach ($grades->take(7) as $grade)<tr><td class="border border-slate-300 px-2 py-1 text-center">{{ $grade->name }}</td><td class="border border-slate-300 px-2 py-1 text-center">{{ $grade->from_percentage }}% - {{ $grade->to_percentage }}%</td></tr>@endforeach
            </tbody></table>
        </div>
        <div class="border border-slate-300 p-3">
            <h2 class="mb-2 text-sm font-bold uppercase">Teacher Remarks</h2>
            @foreach ($terms as $term)<div class="mb-3"><strong>{{ $term['name'] }}</strong><div class="mt-2 h-8 border-b border-dashed border-slate-400"></div></div>@endforeach
        </div>
    </section>

    <footer class="grid grid-cols-3 gap-8 pt-12 text-center text-sm">
        <div class="border-t border-slate-700 pt-2">Guardian Signature</div>
        <div class="border-t border-slate-700 pt-2">Shreny Teacher Signature</div>
        <div class="border-t border-slate-700 pt-2">Head Teacher Signature</div>
    </footer>
</div>
