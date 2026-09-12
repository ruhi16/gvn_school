<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Student {{ $student->id }} - {{ $student->name }}</title>
    <style>
        body { color: #1f2937; font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        h1 { color: #0f172a; font-size: 22px; margin: 0 0 4px; }
        h2 { border-bottom: 1px solid #cbd5e1; color: #0e7490; font-size: 13px; margin: 20px 0 8px; padding-bottom: 4px; }
        .muted { color: #64748b; }
        table { border-collapse: collapse; width: 100%; }
        td { border-bottom: 1px solid #e2e8f0; padding: 6px 4px; vertical-align: top; }
        td:first-child { color: #475569; font-weight: bold; width: 28%; }
    </style>
</head>
<body>
    <h1>{{ $student->name }}</h1>
    <div class="muted">Student database record #{{ $student->id }}</div>

    <h2>Student details</h2>
    <table>
        @foreach ($student->getAttributes() as $field => $value)
            <tr>
                <td>{{ str($field)->replace('_', ' ')->title() }}</td>
                <td>{{ $value ?? '-' }}</td>
            </tr>
        @endforeach
    </table>

    <h2>Class records</h2>
    <table>
        @forelse ($student->classRecords as $record)
            <tr>
                <td>{{ $record->shreny?->name ?? $record->curr_shreny_id }} / {{ $record->section?->name ?? $record->curr_section_id }}</td>
                <td>Roll no. {{ $record->curr_roll_no ?? '-' }}</td>
            </tr>
        @empty
            <tr><td colspan="2">No class records.</td></tr>
        @endforelse
    </table>
</body>
</html>