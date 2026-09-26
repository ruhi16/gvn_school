@php
$examSettingsActive = request()->routeIs('admin.exam*');
$schoolActive = request()->routeIs('admin.school*', 'admin.rooms', 'admin.students*', 'admin.shreny-sections', 'admin.shreny-subjects');
@endphp

<aside class="w-56 shrink-0 border-r border-slate-200 bg-slate-950 text-slate-300"
    x-data="{ examOpen: {{ $examSettingsActive ? 'true' : 'false' }}, schoolOpen: {{ $schoolActive ? 'true' : 'false' }} }">
    <div class="flex h-14 items-center gap-2 border-b border-slate-800 px-4">
        <div class="grid h-7 w-7 place-items-center rounded bg-cyan-400 text-xs font-bold text-slate-950">GV</div>
        <div>
            <p class="text-xs font-semibold tracking-wide text-white">GVN SCHOOL</p>
            <p class="text-[10px] text-slate-500">ADMIN CONSOLE</p>
        </div>
    </div>
    <nav class="space-y-1 p-3 text-xs">
        <a href="{{ route('admin.dashboard') }}"
            class="flex w-full items-center gap-2 rounded px-3 py-2 hover:bg-slate-900">
            <span>⌂</span><span>Overview</span>
        </a>

        <p class="px-3 pb-1 pt-5 text-[10px] font-bold uppercase tracking-widest text-slate-600">School</p>
        <button type="button" @click="schoolOpen = !schoolOpen"
            class="flex w-full items-center justify-between rounded px-3 py-2 {{ $schoolActive ? 'bg-slate-800 text-cyan-300 font-semibold' : 'hover:bg-slate-900' }}">
            <span>School management</span><span x-text="schoolOpen ? '−' : '+'"></span>
        </button>
        <div x-show="schoolOpen" x-cloak class="ml-4 space-y-1 border-l border-slate-800 pl-3">
            <a href="{{ route('admin.school') }}" class="block rounded px-2 py-1.5 {{ request()->routeIs('admin.school') ? 'bg-cyan-950 text-cyan-300 font-semibold' : 'hover:text-white' }}">School setup & rooms</a>
            <a href="{{ route('admin.students') }}" class="block rounded px-2 py-1.5 {{ request()->routeIs('admin.students*') ? 'bg-cyan-950 text-cyan-300 font-semibold' : 'hover:text-white' }}">StudentDB</a>
            <a href="{{ route('admin.shreny-sections') }}" class="block rounded px-2 py-1.5 {{ request()->routeIs('admin.shreny-sections') ? 'bg-cyan-950 text-cyan-300 font-semibold' : 'hover:text-white' }}">Shreny assignments</a>
            <a href="{{ route('admin.shreny-subjects') }}" class="block rounded px-2 py-1.5 {{ request()->routeIs('admin.shreny-subjects') ? 'bg-cyan-950 text-cyan-300 font-semibold' : 'hover:text-white' }}">Shreny subjects</a>
        </div>

        <button type="button" @click="examOpen = !examOpen"
            class="flex w-full items-center justify-between rounded px-3 py-2 {{ $examSettingsActive ? 'bg-slate-800 text-cyan-300 font-semibold' : 'hover:bg-slate-900' }}">
            <span class="flex items-center gap-2"><span>▣</span><span>Exam Settings</span></span>
            <span x-text="examOpen ? '−' : '+'"></span>
        </button>
        <div x-show="examOpen" x-cloak class="ml-4 space-y-1 border-l border-slate-800 pl-3">
            <p class="px-2 py-1.5 font-semibold text-slate-500">Exam Basics</p>
            <a href="{{ route('admin.exam-names') }}"
                class="block rounded px-2 py-1.5 {{ request()->routeIs('admin.exam-names') ? 'bg-cyan-950 text-cyan-300 font-semibold' : 'hover:text-white' }}">Exam
                Name</a>
            <a href="{{ route('admin.exam-types') }}"
                class="block rounded px-2 py-1.5 {{ request()->routeIs('admin.exam-types') ? 'bg-cyan-950 text-cyan-300 font-semibold' : 'hover:text-white' }}">Exam
                Type</a>
            <a href="{{ route('admin.exam-parts') }}"
                class="block rounded px-2 py-1.5 {{ request()->routeIs('admin.exam-parts') ? 'bg-cyan-950 text-cyan-300 font-semibold' : 'hover:text-white' }}">Exam
                Parts</a>
            <a href="{{ route('admin.exam-modes') }}"
                class="block rounded px-2 py-1.5 {{ request()->routeIs('admin.exam-modes') ? 'bg-cyan-950 text-cyan-300 font-semibold' : 'hover:text-white' }}">Exam
                Mode</a>
            <a href="{{ route('admin.exam-halves') }}"
                class="block rounded px-2 py-1.5 {{ request()->routeIs('admin.exam-halves') ? 'bg-cyan-950 text-cyan-300 font-semibold' : 'hover:text-white' }}">Exam
                Halves</a>
            <a href="{{ route('admin.exam-date-schedules') }}"
                class="block rounded px-2 py-1.5 {{ request()->routeIs('admin.exam-date-schedules') ? 'bg-cyan-950 text-cyan-300 font-semibold' : 'hover:text-white' }}">Exam
                date schedule</a>
            <a href="{{ route('admin.exam-grades') }}"
                class="block rounded px-2 py-1.5 {{ request()->routeIs('admin.exam-grades') ? 'bg-cyan-950 text-cyan-300 font-semibold' : 'hover:text-white' }}">Exam
                Grade</a>
            <a href="{{ route('admin.exam-details') }}"
                class="block rounded px-2 py-1.5 {{ request()->routeIs('admin.exam-details') ? 'bg-cyan-950 text-cyan-300 font-semibold' : 'hover:text-white' }}">Exam
                Details</a>
            <a href="{{ route('admin.exam-rooms') }}"
                class="block rounded px-2 py-1.5 {{ request()->routeIs('admin.exam-rooms*') ? 'bg-cyan-950 text-cyan-300 font-semibold' : 'hover:text-white' }}">Exam
                room allotment</a>
            <a href="{{ route('admin.exam-marks') }}"
                class="block rounded px-2 py-1.5 {{ request()->routeIs('admin.exam-marks') ? 'bg-cyan-950 text-cyan-300 font-semibold' : 'hover:text-white' }}">Marks
                & time</a>
        </div>

        <p class="px-3 pb-1 pt-5 text-[10px] font-bold uppercase tracking-widest text-slate-600">Administration</p>
        <a href="{{ route('admin.users') }}" class="flex items-center gap-2 rounded px-3 py-2 hover:bg-slate-900">♙
            <span>Users & roles</span></a>
    </nav>
</aside>