<div class="min-h-[calc(100vh-8rem)] bg-slate-100 text-slate-900" x-data="{ open: true }">
    <div class="mx-auto flex max-w-[1600px] overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <aside class="w-56 shrink-0 border-r border-slate-200 bg-slate-950 text-slate-300">
            <div class="flex h-14 items-center gap-2 border-b border-slate-800 px-4">
                <div class="grid h-7 w-7 place-items-center rounded bg-cyan-400 text-xs font-bold text-slate-950">GV</div>
                <div>
                    <p class="text-xs font-semibold tracking-wide text-white">GVN SCHOOL</p>
                    <p class="text-[10px] text-slate-500">ADMIN CONSOLE</p>
                </div>
            </div>
            <nav class="space-y-1 p-3 text-xs">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 rounded bg-slate-800 px-3 py-2 font-semibold text-cyan-300">⌂ <span>Overview</span></a>
                <p class="px-3 pb-1 pt-5 text-[10px] font-bold uppercase tracking-widest text-slate-600">Academic</p>
                <button type="button" class="flex w-full items-center justify-between rounded px-3 py-2 text-left hover:bg-slate-900" @click="open = !open">
                    <span class="flex items-center gap-2">▦ <span>School data</span></span><span x-text="open ? '−' : '+'"></span>
                </button>
                <div x-show="open" x-cloak class="ml-7 space-y-1 border-l border-slate-800 pl-3">
                    <a href="#schools" class="block py-1.5 text-cyan-300">Schools</a>
                    <a href="#sessions" class="block py-1.5 hover:text-white">Sessions</a>
                    <a href="#shrenies" class="block py-1.5 hover:text-white">Shrenies</a>
                    <a href="#sections" class="block py-1.5 hover:text-white">Sections</a>
                    <a href="#subjects" class="block py-1.5 hover:text-white">Subjects</a>
                </div>
                <a href="#" class="flex items-center gap-2 rounded px-3 py-2 hover:bg-slate-900">◉ <span>Students</span></a>
                <a href="#teachers" class="flex items-center gap-2 rounded px-3 py-2 hover:bg-slate-900">◇ <span>Teachers</span></a>
                <p class="px-3 pb-1 pt-5 text-[10px] font-bold uppercase tracking-widest text-slate-600">Administration</p>
                <a href="{{ route('admin.users') }}" class="flex items-center gap-2 rounded px-3 py-2 hover:bg-slate-900">♙ <span>Users & roles</span></a>
                <a href="#" class="flex items-center gap-2 rounded px-3 py-2 hover:bg-slate-900">⚙ <span>Settings</span></a>
            </nav>
        </aside>

        <section class="min-w-0 flex-1">
            <header class="flex min-h-14 items-center justify-between border-b border-slate-200 px-5">
                <div><p class="text-[10px] font-bold uppercase tracking-widest text-cyan-600">Administration</p><h1 class="text-lg font-semibold tracking-tight">Dashboard</h1></div>
                <div class="flex items-center gap-3 text-xs"><span class="text-slate-500">{{ auth()->user()->name }}</span><span class="rounded-full bg-cyan-50 px-2 py-1 font-semibold text-cyan-700">ADMIN</span></div>
            </header>

            <main class="space-y-5 p-5">
                <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
                    @foreach ([['Schools', $totalSchools, 'text-cyan-600'], ['Users', $totalUsers, 'text-violet-600'], ['Teachers', $totalTeachers, 'text-amber-600'], ['Students', $totalStudents, 'text-emerald-600']] as [$label, $value, $color])
                        <div class="rounded-lg border border-slate-200 bg-white p-4"><p class="text-[11px] font-medium text-slate-500">{{ $label }}</p><p class="mt-1 text-2xl font-semibold tracking-tight {{ $color }}">{{ $value }}</p></div>
                    @endforeach
                </div>
                <div id="schools" class="rounded-lg border border-slate-200 bg-white p-4">
                    <livewire:school-comp />
                </div>
                <div id="sessions" class="rounded-lg border border-slate-200 bg-white p-4"><livewire:session-comp /></div>
                <div id="shrenies" class="rounded-lg border border-slate-200 bg-white p-4"><livewire:shreny-comp /></div>
                <div id="sections" class="rounded-lg border border-slate-200 bg-white p-4"><livewire:section-comp /></div>
                <div id="subjects" class="rounded-lg border border-slate-200 bg-white p-4"><livewire:subject-comp /></div>
                <div id="teachers" class="rounded-lg border border-slate-200 bg-white p-4"><livewire:teacher-comp /></div>
            </main>
        </section>
    </div>
    <style>[x-cloak]{display:none!important}</style>
</div>
