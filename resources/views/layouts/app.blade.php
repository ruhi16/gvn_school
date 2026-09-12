<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'School Management') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body>
    <nav class="border-b border-slate-200 bg-slate-900 text-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
            <a class="text-sm font-semibold" href="{{ url('/') }}">School Management</a>

            <div class="flex items-center gap-3 text-xs">
                @auth
                <span class="hidden text-slate-300 sm:inline">Welcome, {{ Auth::user()->name }}</span>
                @if(Auth::user()->isAdmin())
                <a class="font-medium hover:text-cyan-300" href="{{ route('admin.dashboard') }}">Admin Panel</a>
                <a class="font-medium {{ request()->routeIs('admin.students*') ? 'text-cyan-300' : 'hover:text-cyan-300' }}"
                    href="{{ route('admin.students') }}">StudentDB</a>
                <a class="font-medium {{ request()->routeIs('admin.shreny-sections') ? 'text-cyan-300' : 'hover:text-cyan-300' }}"
                    href="{{ route('admin.shreny-sections') }}">Shreny Section Tasks</a>
                @endif
                @if(Auth::user()->isTeacher())
                <a class="font-medium hover:text-cyan-300" href="{{ route('teacher.dashboard') }}">Teacher Panel</a>
                @endif
                @if(Auth::user()->isStudent())
                <a class="font-medium hover:text-cyan-300" href="{{ route('student.dashboard') }}">Student Panel</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="rounded bg-rose-600 px-2.5 py-1.5 font-semibold hover:bg-rose-500">Logout</button>
                </form>
                @else
                <a class="hover:text-cyan-300" href="{{ route('login') }}">Login</a>
                <a class="rounded bg-cyan-600 px-2.5 py-1.5 font-semibold hover:bg-cyan-500"
                    href="{{ route('register') }}">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="min-h-screen bg-slate-50 py-6">
        @if(request()->routeIs('admin.exam*') || request()->routeIs('admin.students*'))
        <div class="mx-auto flex max-w-[1600px] overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            @include('admin.partials.sidebar')
            <div class="min-w-0 flex-1 px-4 sm:px-6 lg:px-8 py-6">
                @yield('content')
            </div>
        </div>
        @else
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @yield('content')
        </div>
        @endif
    </main>
    @livewireScripts
</body>

</html>