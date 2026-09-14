<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Exam Marks Register</title>
    <style>
        @page { size: A3 landscape; margin: 10mm; }
        body { color: #111827; font-family: Arial, sans-serif; font-size: 8px; }
        h1 { font-size: 18px; margin: 0 0 3px; }
        h2 { border-bottom: 1px solid #9ca3af; font-size: 12px; margin: 18px 0 5px; padding-bottom: 3px; }
        p { color: #4b5563; margin: 0 0 8px; }
        table { border-collapse: collapse; margin-bottom: 14px; width: 100%; }
        th, td { border: 1px solid #9ca3af; padding: 4px; text-align: center; }
        th { background: #e5e7eb; font-weight: bold; }
        th.student, td.student { text-align: left; min-width: 120px; }
        th.combination, td.combination { min-width: 120px; text-align: left; }
        .subject { background: #dbeafe; }
        .total { background: #f3f4f6; font-weight: bold; }
        .overall { background: #ecfeff; font-weight: bold; }
        .small { color: #374151; display: block; font-size: 7px; font-weight: normal; }
    </style>
</head>
<body>
    <h1>Exam Marks Register</h1>
    <p>{{ $session?->name ?? 'No active session' }}</p>
    @foreach ($groups as $group)
    <h2>{{ $group['shreny']->name }} &amp; {{ $group['section']->name }}</h2>
    <table>
        <thead>
            <tr>
                <th rowspan="2">Roll</th>
                <th rowspan="2" class="student">Student</th>
                <th rowspan="2" class="combination">Exam combination</th>
                @foreach ($group['subjects'] as $registerSubject)
                <th rowspan="2" class="subject">{{ $registerSubject['subject']->name }}<span class="small">FM: {{ $registerSubject['display_full_marks'] ?? 'Varies' }}</span><span class="small">{{ $registerSubject['full_marks'] }} total FM</span></th>
                @endforeach
                <th rowspan="2" class="total">Overall total</th>
                <th rowspan="2" class="total">Grade</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($group['students'] as $student)
            @php($studentTotal = 0)
            @php($studentFullMarks = 0)
            @php($studentHasMark = false)
            @foreach ($group['combinations'] as $combination)
            @php($combinationTotal = 0)
            <tr>
                <td>{{ $student->curr_roll_no }}</td>
                <td class="student">{{ $student->student?->name }}</td>
                <td class="combination">{{ $examNames[$combination->exam_name_id]?->name }} / {{ $examTypes[$combination->exam_type_id]?->name }} / {{ $examParts[$combination->exam_part_id]?->name }}</td>
                @foreach ($group['subjects'] as $registerSubject)
                @php($part = $registerSubject['assignments']->first(fn ($assignment) => $assignment->exam_name_id === $combination->exam_name_id && $assignment->exam_type_id === $combination->exam_type_id && $assignment->exam_part_id === $combination->exam_part_id))
                @php($mark = $part ? $registerData->mark($group['entries'], $student->id, $registerSubject['subject']->id, $part) : null)
                @php($studentHasMark = $studentHasMark || $mark !== null)
                @php($combinationTotal += is_numeric($mark) ? $mark : 0)
                <td>{{ $mark ?? '-' }}</td>
                @endforeach
                @php($studentTotal += $combinationTotal)
                <td class="total">{{ $combinationTotal }}</td>
                <td class="total">-</td>
            </tr>
            @endforeach
            @foreach ($group['subjects'] as $registerSubject)
            @php($studentFullMarks += $registerSubject['full_marks'])
            @endforeach
            <tr class="overall">
                <td></td>
                <td></td>
                <td class="combination">Overall</td>
                @foreach ($group['subjects'] as $registerSubject)
                @php($subjectTotal = 0)
                @php($subjectHasMark = false)
                @foreach ($group['combinations'] as $combination)
                @php($part = $registerSubject['assignments']->first(fn ($assignment) => $assignment->exam_name_id === $combination->exam_name_id && $assignment->exam_type_id === $combination->exam_type_id && $assignment->exam_part_id === $combination->exam_part_id))
                @php($mark = $part ? $registerData->mark($group['entries'], $student->id, $registerSubject['subject']->id, $part) : null)
                @php($subjectHasMark = $subjectHasMark || $mark !== null)
                @php($subjectTotal += is_numeric($mark) ? $mark : 0)
                @endforeach
                <td>{{ $subjectHasMark ? $subjectTotal : '-' }}</td>
                @endforeach
                <td>{{ $studentHasMark ? $studentTotal : '-' }}</td>
                <td>{{ $studentHasMark ? ($registerData->grade($studentTotal, $studentFullMarks, collect()) ?? '-') : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endforeach
</body>
</html>
