<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multi-Role Auth System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">School Management</a>
            
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item">
                            <span class="nav-link">Welcome, {{ Auth::user()->name }}</span>
                        </li>
                        <li class="nav-item">
                            <span class="nav-link badge bg-info">
                                Role: {{ ucfirst(Auth::user()->role) }}
                            </span>
                        </li>
                        
                        <!-- Show menu based on role -->
                        @if(Auth::user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">Admin Panel</a>
                            </li>
                        @endif
                        
                        @if(Auth::user()->isTeacher())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('teacher.dashboard') }}">Teacher Panel</a>
                            </li>
                        @endif
                        
                        @if(Auth::user()->isStudent())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('student.dashboard') }}">Student Panel</a>
                            </li>
                        @endif
                        
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">Logout</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            @yield('content')
        </div>
    </main>
</body>
</html>