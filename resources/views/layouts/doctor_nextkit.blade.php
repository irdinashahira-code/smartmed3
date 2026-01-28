<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Doctor</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Flowbite -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        primary: '#1750eb',
                        secondary: '#3dd9eb',
                        info: '#539BFF',
                        success: '#13DEB9',
                        warning: '#FFAE1F',
                        error: '#FA896B',
                        dark: '#202936',
                        lightprimary: '#1750eb1a',
                        lightsecondary: '#3dd9eb2a',
                        border: '#ebf1f6',
                        darkborder: '#333f55',
                        muted: '#5a6a85',
                        bodytext: '#5a6a85bf',
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f6f9fc; /* color-lightgray */
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-slate-600 dark:text-slate-300">
    
    <!-- Sidebar -->
    <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full lg:translate-x-0 bg-white border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700" aria-label="Sidebar">
        <div class="h-full px-3 py-4 overflow-y-auto bg-white dark:bg-gray-800">
            <a href="{{ route('doctor.dashboard') }}" class="flex items-center ps-2.5 mb-5">
                <span class="self-center text-xl font-bold whitespace-nowrap dark:text-white text-primary">SmartMed</span>
            </a>
            <ul class="space-y-2 font-medium">
                <li>
                    <a href="{{ route('doctor.dashboard') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('doctor.dashboard') ? 'bg-primary text-white hover:bg-blue-700' : '' }}">
                        <i class="bi bi-calendar-check text-xl transition duration-75 group-hover:text-gray-900 dark:group-hover:text-white {{ request()->routeIs('doctor.dashboard') ? 'text-white' : 'text-gray-500 dark:text-gray-400' }}"></i>
                        <span class="ms-3">Appointments</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('doctor.schedule.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('doctor.schedule.*') ? 'bg-primary text-white hover:bg-blue-700' : '' }}">
                        <i class="bi bi-calendar-week text-xl transition duration-75 group-hover:text-gray-900 dark:group-hover:text-white {{ request()->routeIs('doctor.schedule.*') ? 'text-white' : 'text-gray-500 dark:text-gray-400' }}"></i>
                        <span class="ms-3">My Schedule</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('doctor.feedback') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('doctor.feedback') ? 'bg-primary text-white hover:bg-blue-700' : '' }}">
                        <i class="bi bi-star text-xl transition duration-75 group-hover:text-gray-900 dark:group-hover:text-white {{ request()->routeIs('doctor.feedback') ? 'text-white' : 'text-gray-500 dark:text-gray-400' }}"></i>
                        <span class="ms-3">Patient Feedback</span>
                    </a>
                </li>

                <li class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
                    <span class="px-2 text-xs font-semibold text-gray-500 uppercase dark:text-gray-400">Account</span>
                </li>

                <li>
                    <a href="{{ route('doctor.profile.show') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('doctor.profile.*') ? 'bg-primary text-white hover:bg-blue-700' : '' }}">
                        <i class="bi bi-person text-xl transition duration-75 group-hover:text-gray-900 dark:group-hover:text-white {{ request()->routeIs('doctor.profile.*') ? 'text-white' : 'text-gray-500 dark:text-gray-400' }}"></i>
                        <span class="ms-3">My Profile</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <i class="bi bi-box-arrow-right text-xl transition duration-75 group-hover:text-gray-900 dark:group-hover:text-white text-gray-500 dark:text-gray-400"></i>
                        <span class="ms-3">Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </aside>

    <!-- Content -->
    <div class="p-4 lg:ml-64">
        <div class="mb-4 flex justify-between items-center bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm">
            <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg lg:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                <span class="sr-only">Open sidebar</span>
                <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                   <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                </svg>
            </button>
            <h1 class="text-xl font-semibold text-gray-800 dark:text-white">@yield('title')</h1>
            <div class="flex items-center gap-3">
                <!-- Theme Toggle -->
                <button id="theme-toggle" type="button" class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5">
                    <i id="theme-toggle-dark-icon" class="bi bi-moon hidden text-lg"></i>
                    <i id="theme-toggle-light-icon" class="bi bi-sun hidden text-lg"></i>
                </button>

                <div class="text-right hidden sm:block">
                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Doctor</div>
                </div>
                @if (Auth::user()->profile_photo_path)
                    <img class="w-10 h-10 rounded-full object-cover" src="{{ Storage::url(Auth::user()->profile_photo_path) }}" alt="{{ Auth::user()->name }}">
                @else
                    <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                @endif
            </div>
        </div>

        <div class="min-h-screen">
            @yield('content')
        </div>

        <footer class="mt-8 text-center text-sm text-gray-500 dark:text-gray-400 pb-4">
            &copy; {{ date('Y') }} SmartMed. All rights reserved.
        </footer>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
    <script>
        var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        // Change the icons inside the button based on previous settings
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            themeToggleLightIcon.classList.remove('hidden');
            themeToggleDarkIcon.classList.add('hidden');
        } else {
            themeToggleDarkIcon.classList.remove('hidden');
            themeToggleLightIcon.classList.add('hidden');
        }

        var themeToggleBtn = document.getElementById('theme-toggle');

        if (themeToggleBtn) {
            themeToggleBtn.addEventListener('click', function() {
                // toggle icons inside button
                themeToggleDarkIcon.classList.toggle('hidden');
                themeToggleLightIcon.classList.toggle('hidden');

                // if set via local storage previously
                if (localStorage.getItem('color-theme')) {
                    if (localStorage.getItem('color-theme') === 'light') {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('color-theme', 'dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('color-theme', 'light');
                    }
                } else {
                    // if NOT set via local storage previously
                    if (document.documentElement.classList.contains('dark')) {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('color-theme', 'light');
                    } else {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('color-theme', 'dark');
                    }
                }
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
