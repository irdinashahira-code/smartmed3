<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Admin</title>
    
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
    <script>
        // Dark mode init
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f6f9fc; /* color-lightgray */
        }
        .sidebar-item:hover {
            background-color: rgba(23, 80, 235, 0.1);
            color: #1750eb;
        }
        .sidebar-item.active {
            background-color: #1750eb;
            color: white;
            box-shadow: 0 9px 17.5px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-slate-600 dark:text-slate-300">
    
    <!-- Sidebar -->
    <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full lg:translate-x-0 bg-white border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700" aria-label="Sidebar">
        <div class="h-full px-3 py-4 overflow-y-auto bg-white dark:bg-gray-800">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center ps-2.5 mb-5">
                <span class="self-center text-xl font-bold whitespace-nowrap dark:text-white text-primary">SmartMed</span>
            </a>
            <ul class="space-y-2 font-medium">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('admin.dashboard') ? 'bg-primary text-white hover:bg-blue-700' : '' }}">
                        <i class="bi bi-speedometer2 text-xl transition duration-75 group-hover:text-gray-900 dark:group-hover:text-white {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-gray-500 dark:text-gray-400' }}"></i>
                        <span class="ms-3">Dashboard</span>
                    </a>
                </li>
                
                <li class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
                    <span class="px-2 text-xs font-semibold text-gray-500 uppercase dark:text-gray-400">Management</span>
                </li>

                <li>
                    <a href="{{ route('admin.doctors') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('admin.doctors*') ? 'bg-primary text-white hover:bg-blue-700' : '' }}">
                        <i class="bi bi-person-badge text-xl transition duration-75 group-hover:text-gray-900 dark:group-hover:text-white {{ request()->routeIs('admin.doctors*') ? 'text-white' : 'text-gray-500 dark:text-gray-400' }}"></i>
                        <span class="ms-3">Doctors</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.patients') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('admin.patients*') ? 'bg-primary text-white hover:bg-blue-700' : '' }}">
                        <i class="bi bi-people text-xl transition duration-75 group-hover:text-gray-900 dark:group-hover:text-white {{ request()->routeIs('admin.patients*') ? 'text-white' : 'text-gray-500 dark:text-gray-400' }}"></i>
                        <span class="ms-3">Patients</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.appointments') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('admin.appointments*') ? 'bg-primary text-white hover:bg-blue-700' : '' }}">
                        <i class="bi bi-calendar-check text-xl transition duration-75 group-hover:text-gray-900 dark:group-hover:text-white {{ request()->routeIs('admin.appointments*') ? 'text-white' : 'text-gray-500 dark:text-gray-400' }}"></i>
                        <span class="ms-3">Appointments</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.advertisements.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('admin.advertisements*') ? 'bg-primary text-white hover:bg-blue-700' : '' }}">
                        <i class="bi bi-megaphone text-xl transition duration-75 group-hover:text-gray-900 dark:group-hover:text-white {{ request()->routeIs('admin.advertisements*') ? 'text-white' : 'text-gray-500 dark:text-gray-400' }}"></i>
                        <span class="ms-3">Advertisements</span>
                    </a>
                </li>

                <li class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
                    <span class="px-2 text-xs font-semibold text-gray-500 uppercase dark:text-gray-400">Analytics</span>
                </li>

                <li>
                    <a href="{{ route('admin.reports.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('admin.reports*') ? 'bg-primary text-white hover:bg-blue-700' : '' }}">
                        <i class="bi bi-graph-up text-xl transition duration-75 group-hover:text-gray-900 dark:group-hover:text-white {{ request()->routeIs('admin.reports*') ? 'text-white' : 'text-gray-500 dark:text-gray-400' }}"></i>
                        <span class="ms-3">Reports</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.activity_logs') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('admin.activity_logs*') ? 'bg-primary text-white hover:bg-blue-700' : '' }}">
                        <i class="bi bi-activity text-xl transition duration-75 group-hover:text-gray-900 dark:group-hover:text-white {{ request()->routeIs('admin.activity_logs*') ? 'text-white' : 'text-gray-500 dark:text-gray-400' }}"></i>
                        <span class="ms-3">Activity Logs</span>
                    </a>
                </li>
            </ul>
            
            <div class="absolute bottom-0 left-0 justify-center p-4 w-full bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 z-20">
                 <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center p-2 text-red-600 rounded-lg dark:text-red-500 hover:bg-gray-100 dark:hover:bg-gray-700 group">
                    <i class="bi bi-box-arrow-right text-xl transition duration-75 group-hover:text-red-700 dark:group-hover:text-white"></i>
                    <span class="ms-3">Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            </div>
        </div>
    </aside>

    <div class="lg:ml-64">
        <!-- Header -->
        <nav class="bg-white border-b border-gray-200 px-4 py-2.5 dark:bg-gray-800 dark:border-gray-700 sticky top-0 z-30">
            <div class="flex flex-wrap justify-between items-center">
                <div class="flex justify-start items-center">
                    <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg lg:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                           <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                        </svg>
                    </button>
                    <h4 class="text-xl font-bold text-gray-800 dark:text-white ml-3">@yield('title', 'Admin Dashboard')</h4>
                </div>
                <div class="flex items-center">
                    <button id="theme-toggle" type="button" class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5 mr-2">
                        <i id="theme-toggle-dark-icon" class="bi bi-moon hidden text-lg"></i>
                        <i id="theme-toggle-light-icon" class="bi bi-sun hidden text-lg"></i>
                    </button>
                    <div class="flex items-center ms-3">
                        <div>
                            <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" aria-expanded="false" data-dropdown-toggle="dropdown-user">
                                <span class="sr-only">Open user menu</span>
                                <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            </button>
                        </div>
                        <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600" id="dropdown-user">
                            <div class="px-4 py-3" role="none">
                                <p class="text-sm text-gray-900 dark:text-white" role="none">
                                    {{ Auth::user()->name }}
                                </p>
                                <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300" role="none">
                                    {{ Auth::user()->email }}
                                </p>
                            </div>
                            <ul class="py-1" role="none">
                                <li>
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Sign out</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="p-6">
            @if(session('success'))
                <div id="alert-3" class="flex items-center p-4 mb-4 text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                    <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                    </svg>
                    <span class="sr-only">Info</span>
                    <div class="ms-3 text-sm font-medium">
                        {{ session('success') }}
                    </div>
                    <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-green-400 dark:hover:bg-gray-700" data-dismiss-target="#alert-3" aria-label="Close">
                        <span class="sr-only">Close</span>
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                    </button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
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