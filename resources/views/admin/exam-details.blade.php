@extends('layouts.app')

@section('content')
<div class="space-y-5">
    <header class="border-b border-slate-200 pb-5">
        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-cyan-600">Exam Settings</p>
        <h1 class="mt-1 text-2xl font-semibold tracking-tight text-slate-950">Exam Details</h1>
        <p class="mt-1 text-sm text-slate-500">Detailed exam configuration will be managed here.</p>
    </header>
    <livewire:exam-settings />
</div>
@endsection
