<div class="min-h-[calc(100vh-8rem)] bg-slate-100 text-slate-900" x-data="{ open: true, activeTab: 'overview' }">
    <div class="mx-auto flex max-w-[1600px] overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <aside class="w-56 shrink-0 border-r border-slate-200 bg-slate-950 text-slate-300">
            <div class="flex h-14 items-center gap-2 border-b border-slate-800 px-4">
                <div class="grid h-7 w-7 place-items-center rounded bg-cyan-400 text-xs font-bold text-slate-950">GV
                </div>
                <div>
                    <p class="text-xs font-semibold tracking-wide text-white">GVN SCHOOL</p>
                    <p class="text-[10px] text-slate-500">ADMIN CONSOLE</p>
                </div>
            </div>
            <nav class="space-y-1 p-3 text-xs">
                <button type="button" @click="activeTab = 'overview'"
                    :class="activeTab === 'overview' ? 'bg-slate-800 text-cyan-300 font-semibold' : 'hover:bg-slate-900'"
                    class="flex w-full items-center gap-2 rounded px-3 py-2 text-left">
                    ⌂ <span>Overview</span>
                </button>

                <p class="px-3 pb-1 pt-5 text-[10px] font-bold uppercase tracking-widest text-slate-600">Academic</p>
                <button type="button"
                    class="flex w-full items-center justify-between rounded px-3 py-2 text-left hover:bg-slate-900"
                    @click="open = !open">
                    <span class="flex items-center gap-2">▦ <span>School data</span></span><span
                        x-text="open ? '−' : '+'"></span>
                </button>
                <div x-show="open" x-cloak class="ml-7 space-y-1 border-l border-slate-800 pl-3">
                    <button type="button" @click="activeTab = 'schools'"
                        :class="activeTab === 'schools' ? 'text-cyan-300 font-semibold' : 'hover:text-white'"
                        class="block py-1.5 text-left">Schools</button>
                    <button type="button" @click="activeTab = 'sessions'"
                        :class="activeTab === 'sessions' ? 'text-cyan-300 font-semibold' : 'hover:text-white'"
                        class="block py-1.5 text-left">Sessions</button>
                    <button type="button" @click="activeTab = 'shrenies'"
                        :class="activeTab === 'shrenies' ? 'text-cyan-300 font-semibold' : 'hover:text-white'"
                        class="block py-1.5 text-left">Shrenies</button>
                    <button type="button" @click="activeTab = 'sections'"
                        :class="activeTab === 'sections' ? 'text-cyan-300 font-semibold' : 'hover:text-white'"
                        class="block py-1.5 text-left">Sections</button>
                    <a href="{{ route('admin.shreny-sections') }}"
                        class="block py-1.5 text-left hover:text-white">Shreny assignments</a>
                    <button type="button" @click="activeTab = 'subjects'"
                        :class="activeTab === 'subjects' ? 'text-cyan-300 font-semibold' : 'hover:text-white'"
                        class="block py-1.5 text-left">Subjects</button>
                    <button type="button" @click="activeTab = 'teachers'"
                        :class="activeTab === 'teachers' ? 'text-cyan-300 font-semibold' : 'hover:text-white'"
                        class="block py-1.5 text-left">Teachers</button>
                </div>

                <button type="button" @click="activeTab = 'students'"
                    :class="activeTab === 'students' ? 'bg-slate-800 text-cyan-300 font-semibold' : 'hover:bg-slate-900'"
                    class="flex w-full items-center gap-2 rounded px-3 py-2 text-left">
                    ◉ <span>Students</span>
                </button>
                <button type="button" @click="activeTab = 'teachers'"
                    :class="activeTab === 'teachers' ? 'bg-slate-800 text-cyan-300 font-semibold' : 'hover:bg-slate-900'"
                    class="flex w-full items-center gap-2 rounded px-3 py-2 text-left">
                    ◇ <span>Teachers</span>
                </button>

                <p class="px-3 pb-1 pt-5 text-[10px] font-bold uppercase tracking-widest text-slate-600">Administration
                </p>
                <a href="{{ route('admin.users') }}"
                    class="flex items-center gap-2 rounded px-3 py-2 hover:bg-slate-900">♙ <span>Users &
                        roles</span></a>
                <button type="button" @click="activeTab = 'settings'"
                    :class="activeTab === 'settings' ? 'bg-slate-800 text-cyan-300 font-semibold' : 'hover:bg-slate-900'"
                    class="flex w-full items-center gap-2 rounded px-3 py-2 text-left">
                    ⚙ <span>Settings</span>
                </button>
            </nav>
        </aside>

        <section class="min-w-0 flex-1">
            <header class="flex min-h-14 items-center justify-between border-b border-slate-200 px-5">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-cyan-600">Administration</p>
                    <h1 class="text-lg font-semibold tracking-tight capitalize" x-text="activeTab"></h1>
                </div>
                <div class="flex items-center gap-3 text-xs">
                    <span class="text-slate-500">{{ auth()->user()->name }}</span>
                    <span class="rounded-full bg-cyan-50 px-2 py-1 font-semibold text-cyan-700">ADMIN</span>
                </div>
            </header>

            <main class="space-y-5 p-5">
                <!-- Summary Cards (Visible in Overview mode) -->
                <div x-show="activeTab === 'overview'" x-cloak class="grid grid-cols-2 gap-3 lg:grid-cols-4">
                    @php
                    $summaryCards = [
                    ['label' => 'Schools', 'value' => $totalSchools, 'color' => 'text-cyan-600'],
                    ['label' => 'Users', 'value' => $totalUsers, 'color' => 'text-violet-600'],
                    ['label' => 'Teachers', 'value' => $totalTeachers, 'color' => 'text-amber-600'],
                    ['label' => 'Students', 'value' => $totalStudents, 'color' => 'text-emerald-600'],
                    ];
                    @endphp
                    @foreach ($summaryCards as $card)
                    <div class="rounded-lg border border-slate-200 bg-white p-4">
                        <p class="text-[11px] font-medium text-slate-500">{{ $card['label'] }}</p>
                        <p class="mt-1 text-2xl font-semibold tracking-tight {{ $card['color'] }}">{{ $card['value'] }}
                        </p>
                    </div>
                    @endforeach
                </div>

                <!-- Livewire Components (Visible in Overview OR when explicitly selected) -->
                <div x-show="activeTab === 'overview' || activeTab === 'schools'" x-cloak
                    class="rounded-lg border border-slate-200 bg-white p-4">
                    <livewire:school-comp />
                </div>
                <div x-show="activeTab === 'overview' || activeTab === 'sessions'" x-cloak
                    class="rounded-lg border border-slate-200 bg-white p-4">
                    <livewire:session-comp />
                </div>
                <div x-show="activeTab === 'overview' || activeTab === 'shrenies'" x-cloak
                    class="rounded-lg border border-slate-200 bg-white p-4">
                    <livewire:shreny-comp />
                </div>
                <div x-show="activeTab === 'overview' || activeTab === 'sections'" x-cloak
                    class="rounded-lg border border-slate-200 bg-white p-4">
                    <livewire:section-comp />
                </div>
                <div x-show="activeTab === 'overview' || activeTab === 'subjects'" x-cloak
                    class="rounded-lg border border-slate-200 bg-white p-4">
                    <livewire:subject-comp />
                </div>
                <div x-show="activeTab === 'overview' || activeTab === 'teachers'" x-cloak
                    class="rounded-lg border border-slate-200 bg-white p-4">
                    <livewire:teacher-comp />
                </div>
            </main>
        </section>
    </div>
    <style>
        [x-cloak] {
            display: none !important
        }
    </style>
</div>