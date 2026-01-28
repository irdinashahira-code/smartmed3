<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .sidebar {
            width: 250px;
            background-color: #212529;
            color: white;
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
        }
        .sidebar-brand {
            padding: 20px;
            font-size: 1.5rem;
            font-weight: bold;
            text-align: center;
            border-bottom: 1px solid #343a40;
        }
        .sidebar-menu { padding: 20px 10px; }
        .sidebar-link {
            color: #c2c7d0;
            text-decoration: none;
            display: block;
            padding: 10px 15px;
            margin-bottom: 5px;
            border-radius: 5px;
            transition: all 0.3s;
        }
        .sidebar-link:hover, .sidebar-link.active {
            background-color: #0d6efd;
            color: white;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .top-bar {
            background-color: white;
            padding: 15px 30px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-brand">SmartMed Admin</div>
        <nav class="sidebar-menu">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
            <a href="{{ route('admin.doctors') }}" class="sidebar-link {{ request()->routeIs('admin.doctors*') ? 'active' : '' }}">
                <i class="bi bi-person-badge me-2"></i> Doctors
            </a>
            <a href="{{ route('admin.patients') }}" class="sidebar-link {{ request()->routeIs('admin.patients*') ? 'active' : '' }}">
                <i class="bi bi-people me-2"></i> Patients
            </a>
            <a href="{{ route('admin.appointments') }}" class="sidebar-link {{ request()->routeIs('admin.appointments*') ? 'active' : '' }}">
                <i class="bi bi-calendar-check me-2"></i> Appointments
            </a>
            <a href="{{ route('admin.reports.index') }}" class="sidebar-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                <i class="bi bi-graph-up me-2"></i> Reports
            </a>
            <a href="{{ route('admin.activity_logs') }}" class="sidebar-link {{ request()->routeIs('admin.activity_logs*') ? 'active' : '' }}">
                <i class="bi bi-activity me-2"></i> Activity Logs
            </a>
            <hr class="text-secondary">
            <a href="{{ route('logout') }}" class="sidebar-link text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
            </a>
        </nav>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
    </div>

    <div class="main-content">
        <div class="top-bar">
            <h4 class="mb-0">@yield('title', 'Admin Dashboard')</h4>
            <span>{{ Auth::user()->name }}</span>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </div>
</body>
</html>
