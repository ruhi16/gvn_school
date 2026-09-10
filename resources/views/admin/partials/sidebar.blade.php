@php
$examSettingsActive = request()->routeIs('admin.exam*');
@endphp

<aside class="w-56 shrink-0 border-r border-slate-200 bg-slate-950 text-slate-300" x-data="{ examOpen: {{ $examSettingsActive ? 'true' : 'false' }} }">
    <div class="flex h-14 items-center gap-2 border-b border-slate-800 px-4">
        <div class="grid h-7 w-7 place-items-center rounded bg-cyan-400 text-xs font-bold text-slate-950">GV</div>
        <div>
            <p class="text-xs font-semibold tracking-wide text-white">GVN SCHOOL</p>
            <p class="text-[10px] text-slate-500">ADMIN CONSOLE</p>
        </div>
    </div>
    <nav class="space-y-1 p-3 text-xs">
        <a href="{{ route('admin.dashboard') }}" class="flex w-full items-center gap-2 rounded px-3 py-2 hover:bg-slate-900">
            <span>⌂</span><span>Overview</span>
        </a>

        <p class="px-3 pb-1 pt-5 text-[10px] font-bold uppercase tracking-widest text-slate-600">Academic</p>
        <a href="{{ route('admin.dashboard') }}" class="block rounded px-3 py-2 hover:bg-slate-900">School data</a>
        <a href="{{ route('admin.shreny-sections') }}" class="block rounded px-3 py-2 hover:bg-slate-900">Shreny assignments</a>
        <a href="{{ route('admin.shreny-subjects') }}" class="block rounded px-3 py-2 hover:bg-slate-900">Shreny subjects</a>

        <button type="button" @click="examOpen = !examOpen"
            class="flex w-full items-center justify-between rounded px-3 py-2 {{ $examSettingsActive ? 'bg-slate-800 text-cyan-300 font-semibold' : 'hover:bg-slate-900' }}">
            <span class="flex items-center gap-2"><span>▣</span><span>Exam Settings</span></span>
            <span x-text="examOpen ? '−' : '+'"></span>
        </button>
        <div x-show="examOpen" x-cloak class="ml-4 space-y-1 border-l border-slate-800 pl-3">
            <p class="px-2 py-1.5 font-semibold text-slate-500">Exam Basics</p>
            <a href="{{ route('admin.exam-names') }}" class="block rounded px-2 py-1.5 {{ request()->routeIs('admin.exam-names') ? 'bg-cyan-950 text-cyan-300 font-semibold' : 'hover:text-white' }}">Exam Name</a>
            <a href="{{ route('admin.exam-types') }}" class="block rounded px-2 py-1.5 {{ request()->routeIs('admin.exam-types') ? 'bg-cyan-950 text-cyan-300 font-semibold' : 'hover:text-white' }}">Exam Type</a>
            <a href="{{ route('admin.exam-parts') }}" class="block rounded px-2 py-1.5 {{ request()->routeIs('admin.exam-parts') ? 'bg-cyan-950 text-cyan-300 font-semibold' : 'hover:text-white' }}">Exam Parts</a>
            <a href="{{ route('admin.exam-modes') }}" class="block rounded px-2 py-1.5 {{ request()->routeIs('admin.exam-modes') ? 'bg-cyan-950 text-cyan-300 font-semibold' : 'hover:text-white' }}">Exam Mode</a>
            <a href="{{ route('admin.exam-grades') }}" class="block rounded px-2 py-1.5 {{ request()->routeIs('admin.exam-grades') ? 'bg-cyan-950 text-cyan-300 font-semibold' : 'hover:text-white' }}">Exam Grade</a>
            <a href="{{ route('admin.exam-details') }}" class="block rounded px-2 py-1.5 {{ request()->routeIs('admin.exam-details') ? 'bg-cyan-950 text-cyan-300 font-semibold' : 'hover:text-white' }}">Exam Details</a>
        </div>

        <p class="px-3 pb-1 pt-5 text-[10px] font-bold uppercase tracking-widest text-slate-600">Administration</p>
        <a href="{{ route('admin.users') }}" class="flex items-center gap-2 rounded px-3 py-2 hover:bg-slate-900">♙ <span>Users & roles</span></a>
    </nav>
</aside>
