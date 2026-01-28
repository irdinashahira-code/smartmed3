<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        /* Custom Dashboard Layout Styles */
        body {
            background-color: #f8f9fa;
        }
        .doctor-layout {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: white;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            z-index: 1000;
        }
        .sidebar-brand {
            padding: 20px;
            font-size: 1.5rem;
            font-weight: bold;
            text-align: center;
            border-bottom: 1px solid #4b545c;
        }
        .sidebar-menu {
            padding: 20px 10px;
            flex-grow: 1;
        }
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
            background-color: #007bff;
            color: white;
            text-decoration: none;
        }
        .main-content {
            flex: 1;
            margin-left: 250px;
            display: flex;
            flex-direction: column;
            width: calc(100% - 250px);
        }
        .top-bar {
            background-color: white;
            padding: 15px 30px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
        }
        .page-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #343a40;
            margin: 0;
        }
        .welcome-msg {
            font-weight: 600;
            color: #495057;
        }
        .dashboard-container {
            padding: 30px;
            flex-grow: 1;
        }
        .overview-card {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            border-left: 5px solid #007bff;
            height: 100%;
        }
        .overview-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .overview-label {
            color: #6c757d;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }
        .overview-value {
            font-size: 2.5rem;
            font-weight: bold;
            color: #343a40;
            margin: 0;
        }
        
        /* Force hide modal backdrop if body doesn't have modal-open class */
        body:not(.modal-open) .modal-backdrop {
            display: none !important;
        }
    </style>
</head>
<body>
    <div id="app" class="doctor-layout">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-brand">SmartMed</div>
            <nav class="sidebar-menu">
                <a href="{{ route('doctor.dashboard') }}" class="sidebar-link {{ request()->routeIs('doctor.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check me-2"></i> Appointments
                </a>
                <a href="{{ route('doctor.schedule.index') }}" class="sidebar-link {{ request()->routeIs('doctor.schedule.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-week me-2"></i> My Schedule
                </a>
                <a href="{{ route('doctor.feedback') }}" class="sidebar-link {{ request()->routeIs('doctor.feedback') ? 'active' : '' }}">
                    <i class="bi bi-star me-2"></i> Patient Feedback
                </a>
                <a href="{{ route('doctor.profile.show') }}" class="sidebar-link {{ request()->routeIs('doctor.profile.*') ? 'active' : '' }}">
                    <i class="bi bi-person me-2"></i> My Profile
                </a>
            </nav>
            <div class="p-3 border-top border-secondary">
                <a href="{{ route('logout') }}" class="sidebar-link text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Bar -->
            <div class="top-bar">
                <h1 class="page-title">Doctor Dashboard</h1>
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="doctorDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="color: inherit;">
                        @if (Auth::user()->profile_photo_path)
                            <img src="{{ Storage::url(Auth::user()->profile_photo_path) }}" alt="Profile Photo" class="rounded-circle me-2" width="32" height="32" style="object-fit: cover;">
                        @endif
                        <span class="welcome-msg me-2">Welcome, Dr. {{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="doctorDropdown">
                        <li><a class="dropdown-item" href="{{ route('doctor.profile.show') }}">My Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                        </li>
                    </ul>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>

            <!-- Dashboard Content -->
            <div class="dashboard-container">
                @yield('content')
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Check if there are any open modals
            const openModals = document.querySelectorAll('.modal.show');
            // If no modals are visible, ensure backdrop and body classes are cleared
            if (openModals.length === 0) {
                const backdrops = document.querySelectorAll('.modal-backdrop');
                backdrops.forEach(backdrop => backdrop.remove());
                document.body.classList.remove('modal-open');
                document.body.style.overflow = 'auto';
                document.body.style.paddingRight = '';
            }
        });
    </script>
    @stack('modals')
    @stack('scripts')
</body>
</html>