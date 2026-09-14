@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-[1500px] p-5">
    <div class="mb-4 flex justify-end">
        <a href="{{ route('admin.exam-marks.sheet.pdf', $studentCr) }}" class="rounded bg-cyan-600 px-3 py-2 text-xs font-semibold text-white hover:bg-cyan-700">Download PDF</a>
    </div>
    @include('partials.exam-marks-sheet', ['pdf' => false])
</div>
@endsection
