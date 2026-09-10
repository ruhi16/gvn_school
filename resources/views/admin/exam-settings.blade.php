@extends('layouts.app')

@section('content')
<div class="space-y-5">
    <header class="border-b border-slate-200 pb-5">
        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-cyan-600">Administration</p>
        <h1 class="mt-1 text-2xl font-semibold tracking-tight text-slate-950">Exam Settings</h1>
        <p class="mt-1 text-sm text-slate-500">Configure the exam catalogs used throughout the school system.</p>
    </header>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <a href="{{ route('admin.exam-names') }}" class="rounded-lg border border-slate-200 bg-white p-5 hover:border-cyan-300 hover:shadow-sm"><p class="font-semibold text-slate-900">Exam Basics</p><p class="mt-1 text-xs text-slate-500">Manage names, types, parts, modes, and grades.</p></a>
        <a href="{{ route('admin.exam-details') }}" class="rounded-lg border border-slate-200 bg-white p-5 hover:border-cyan-300 hover:shadow-sm"><p class="font-semibold text-slate-900">Exam Details</p><p class="mt-1 text-xs text-slate-500">Open detailed exam configuration.</p></a>
    </div>
</div>
@endsection
