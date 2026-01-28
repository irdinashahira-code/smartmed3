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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        :root {
            --bs-primary: #3498db;
            --bs-secondary: #2ecc71;
            --bs-body-bg: #f8f9fa;
            --bs-body-color: #333333;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
        }
        .btn-primary {
            background-color: #3498db;
            border-color: #3498db;
        }
        .btn-success, .text-success {
            color: #fff;
            background-color: #2ecc71;
            border-color: #2ecc71;
        }
        .text-success { color: #2ecc71 !important; }
        .text-primary { color: #3498db !important; }
        
        /* Force hide modal backdrop if body doesn't have modal-open class */
        body:not(.modal-open) .modal-backdrop {
            display: none !important;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav me-auto">

                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                    <!-- Authentication Links -->
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                @if (Auth::user()->profile_photo_path)
                                    <img src="{{ Storage::url(Auth::user()->profile_photo_path) }}" alt="Profile Photo" class="rounded-circle me-2" width="32" height="32" style="object-fit: cover;">
                                @endif
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                @if(Auth::user()->role == 'patient')
                                    <a class="dropdown-item" href="{{ route('patient.dashboard') }}">
                                        {{ __('Dashboard') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('patient.medical_history.index') }}">
                                        {{ __('Medical History') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('patient.profile.show') }}">
                                        {{ __('My Profile') }}
                                    </a>
                                @elseif(Auth::user()->role == 'doctor')
                                    <a class="dropdown-item" href="{{ route('doctor.dashboard') }}">
                                        {{ __('Dashboard') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('doctor.profile.show') }}">
                                        {{ __('My Profile') }}
                                    </a>
                                @endif
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <div id="app">
        <main class="py-4">
            @yield('content')
        </main>
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
    @stack('scripts')
</body>
</html>
