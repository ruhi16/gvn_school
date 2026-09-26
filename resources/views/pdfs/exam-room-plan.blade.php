<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Exam Room Plan</title>
    <style>
        @page { size: A4 landscape; margin: 10mm; }
        body { color: #17212b; font-family: DejaVu Sans, sans-serif; font-size: 9px; }
        h1 { font-size: 20px; margin: 0 0 4px; text-align: center; }
        .subtitle { color: #52606d; margin: 0 0 14px; text-align: center; }
        h2 { border-bottom: 2px solid #34495e; font-size: 13px; margin: 16px 0 5px; padding-bottom: 4px; }
        table { border-collapse: collapse; margin-bottom: 10px; width: 100%; }
        th, td { border: 1px solid #9aa5af; padding: 5px; text-align: left; vertical-align: top; }
        th { background: #e8eef2; }
        .capacity { float: right; font-size: 9px; font-weight: normal; }
        .assigned { background: #e8f5ec; }
        .student-list { color: #394b59; }
        .empty { color: #697780; text-align: center; }
    </style>
</head>
<body>
    <h1>Exam Room Plan</h1>
    <p class="subtitle">{{ $examName?->name }} / {{ $examType?->name }} / {{ $examPart?->name }} · {{ $session?->name }}</p>
    @forelse ($roomPlans as $plan)
        <h2>{{ $plan['room']?->name ?? 'Room removed' }} <span class="capacity">{{ $plan['students'] }} assigned / {{ $plan['capacity'] ?: 'Capacity not set' }} capacity</span></h2>
        <table>
            <thead><tr><th>Shreny-Section</th><th>Roll range</th><th>Students assigned</th><th>Bench arrangement</th></tr></thead>
            <tbody>
                @foreach ($plan['entries'] as $entry)
                    <tr class="assigned">
                        <td>{{ $entry['allocation']->shreny?->name }} / {{ $entry['allocation']->section?->name }}</td>
                        <td>{{ $entry['allocation']->roll_no_range_start }}–{{ $entry['allocation']->roll_no_range_end }}</td>
                        <td class="student-list">@foreach ($entry['students'] as $student){{ $student->curr_roll_no }}. {{ $student->student?->name ?? 'Student' }}@if (!$loop->last), @endif @endforeach</td>
                        <td>{{ $entry['allocation']->no_of_students_per_bench ?? $plan['room']?->no_of_students_per_bench ?? '—' }} students per bench · {{ $plan['room']?->no_of_benches ?? '—' }} benches</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @empty
        <p class="empty">No room allocations were found for this exam combination.</p>
    @endforelse
</body>
</html>