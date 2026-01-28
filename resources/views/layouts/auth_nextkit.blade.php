<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    
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
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-slate-600 dark:text-slate-300 font-sans">
    
    <div class="min-h-screen flex flex-col items-center justify-center py-6 px-4 sm:px-6 lg:px-8">
        <div class="mb-8 text-center">
            <a href="{{ url('/') }}" class="flex items-center justify-center gap-2">
                <span class="text-3xl font-bold text-primary dark:text-white">SmartMed</span>
            </a>
        </div>
        
        <div class="w-full @yield('auth_width', 'max-w-md')">
            @yield('content')
        </div>
        
        <div class="mt-8 text-center text-sm text-gray-500 dark:text-gray-400">
            &copy; {{ date('Y') }} SmartMed. All rights reserved.
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
    @stack('scripts')
</body>
</html>
