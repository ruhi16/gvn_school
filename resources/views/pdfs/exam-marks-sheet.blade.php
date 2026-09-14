<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Final Progress Report - {{ $student?->name }}</title>
    <style>
        @page { size: A4 landscape; margin: 10mm; }
        body { color: #111827; font-family: Arial, sans-serif; font-size: 9px; }
        h1 { font-size: 22px; margin: 0; text-align: center; text-transform: uppercase; }
        h2 { font-size: 11px; margin: 0 0 6px; text-transform: uppercase; }
        header { border-bottom: 2px solid #111827; padding-bottom: 8px; text-align: center; }
        p { margin: 3px 0; text-align: center; }
        section { margin: 10px 0; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #6b7280; padding: 4px; text-align: center; }
        th:first-child, td:first-child { text-align: left; }
        th { background: #f3f4f6; }
        .sheet-pdf > section:first-of-type { border: 1px solid #9ca3af; padding: 7px; }
        .sheet-pdf > section:first-of-type div { display: inline-block; margin-right: 18px; }
        .sheet-pdf .grid { display: block; }
        .sheet-pdf .grid > div { display: inline-block; vertical-align: top; width: 31%; margin-right: 1%; }
        .sheet-pdf footer { margin-top: 45px; }
        .sheet-pdf footer div { border-top: 1px solid #374151; display: inline-block; margin: 0 3%; padding-top: 5px; text-align: center; width: 26%; }
        .sheet-pdf .border-2 { border: 2px solid #111827; }
        .sheet-pdf .border { border: 1px solid #9ca3af; }
        .sheet-pdf .font-bold { font-weight: bold; }
        .sheet-pdf .text-center { text-align: center; }
    </style>
</head>
<body>
    @include('partials.exam-marks-sheet', ['pdf' => true])
</body>
</html>
