<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard - Visual Mockup Reference</title>
    <!-- Fonts: Plus Jakarta Sans (Modern Sans-Serif) -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'heidi-teal': '#00a3b4',
                        'heidi-teal-dark': '#008a99',
                        'heidi-navy': '#0a192f',
                        'heidi-grey-light': '#f5f7fa',
                        'heidi-grey-text': '#5a6a85',
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-white text-gray-800 antialiased">

    <!-- Top Navigation (Mockup) -->
    <nav class="w-full bg-white border-b border-gray-100 py-4">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <!-- Logo Mockup -->
                <div class="w-8 h-8 bg-heidi-teal rounded-lg flex items-center justify-center text-white font-bold">H</div>
                <span class="text-xl font-bold text-heidi-navy tracking-tight">HeidiHealth</span>
            </div>
            <div class="hidden md:flex gap-8 text-sm font-medium text-gray-600">
                <a href="#" class="hover:text-heidi-teal transition">Platform</a>
                <a href="#" class="hover:text-heidi-teal transition">Solutions</a>
                <a href="#" class="hover:text-heidi-teal transition">Pricing</a>
                <a href="#" class="hover:text-heidi-teal transition">Resources</a>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('patient.dashboard') }}" class="text-sm font-semibold text-gray-500 hover:text-heidi-navy">Back to Dashboard</a>
                <button class="bg-heidi-teal hover:bg-heidi-teal-dark text-white text-sm font-bold py-2.5 px-5 rounded-lg transition-colors shadow-sm">
                    Get Started
                </button>
            </div>
        </div>
    </nav>

    <!-- Hero Section / Dashboard Header -->
    <section class="bg-white pt-16 pb-12">
        <div class="max-w-5xl mx-auto px-6 text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold text-heidi-navy mb-6 leading-tight">
                Your Health, <span class="text-heidi-teal">Reimagined.</span>
            </h1>
            <p class="text-lg text-heidi-grey-text mb-8 max-w-2xl mx-auto leading-relaxed">
                Access your medical records, schedule appointments, and manage your well-being from a single, beautifully designed dashboard.
            </p>
            <div class="flex justify-center gap-4">
                <button class="bg-heidi-teal hover:bg-heidi-teal-dark text-white text-base font-bold py-3 px-8 rounded-lg shadow-md transition-transform transform hover:-translate-y-0.5">
                    Book Appointment
                </button>
                <button class="bg-white border border-gray-200 text-heidi-navy text-base font-bold py-3 px-8 rounded-lg hover:bg-gray-50 transition-colors">
                    View Records
                </button>
            </div>
        </div>
    </section>

    <!-- Metrics Section (Alternating Background: Light Grey) -->
    <section class="bg-heidi-grey-light py-16">
        <div class="max-w-6xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div class="p-6">
                    <div class="text-5xl font-bold text-heidi-teal mb-2">3</div>
                    <p class="text-sm font-semibold text-heidi-navy uppercase tracking-wide">Upcoming Appointments</p>
                </div>
                <div class="p-6 border-l border-r border-gray-200">
                    <div class="text-5xl font-bold text-heidi-teal mb-2">12</div>
                    <p class="text-sm font-semibold text-heidi-navy uppercase tracking-wide">Medical Documents</p>
                </div>
                <div class="p-6">
                    <div class="text-5xl font-bold text-heidi-teal mb-2">100%</div>
                    <p class="text-sm font-semibold text-heidi-navy uppercase tracking-wide">Profile Completion</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Feature Sections (Alternating Backgrounds) -->
    
    <!-- Section 1: White -->
    <section class="bg-white py-20">
        <div class="max-w-6xl mx-auto px-6">
            <div class="flex flex-col md:flex-row items-center gap-12">
                <div class="w-full md:w-1/2">
                    <!-- Graphic Placeholder: Clean Icon/Illustration -->
                    <div class="bg-heidi-grey-light rounded-2xl p-10 flex items-center justify-center aspect-video">
                        <i class="bi bi-calendar-check text-heidi-teal text-8xl opacity-80"></i>
                    </div>
                </div>
                <div class="w-full md:w-1/2">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-heidi-teal">
                            <i class="bi bi-clock-history text-xl"></i>
                        </span>
                        <span class="text-sm font-bold text-heidi-teal uppercase tracking-wider">Smart Scheduling</span>
                    </div>
                    <h2 class="text-3xl font-bold text-heidi-navy mb-4">Effortless Appointment Booking</h2>
                    <p class="text-heidi-grey-text text-lg leading-relaxed mb-6">
                        Find the right doctor at the right time. Our smart scheduling system ensures you get the care you need without the wait.
                    </p>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center text-gray-700">
                            <i class="bi bi-check-circle-fill text-heidi-teal mr-3"></i> Real-time availability
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="bi bi-check-circle-fill text-heidi-teal mr-3"></i> Instant confirmation
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="bi bi-check-circle-fill text-heidi-teal mr-3"></i> Automated reminders
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 2: Light Grey -->
    <section class="bg-heidi-grey-light py-20">
        <div class="max-w-6xl mx-auto px-6">
            <div class="flex flex-col md:flex-row-reverse items-center gap-12">
                <div class="w-full md:w-1/2">
                    <!-- Graphic Placeholder -->
                    <div class="bg-white rounded-2xl p-10 flex items-center justify-center aspect-video shadow-sm">
                        <i class="bi bi-file-earmark-medical text-heidi-teal text-8xl opacity-80"></i>
                    </div>
                </div>
                <div class="w-full md:w-1/2">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center text-green-600">
                            <i class="bi bi-shield-lock text-xl"></i>
                        </span>
                        <span class="text-sm font-bold text-green-600 uppercase tracking-wider">Secure Records</span>
                    </div>
                    <h2 class="text-3xl font-bold text-heidi-navy mb-4">Your Medical History, Secure & Accessible</h2>
                    <p class="text-heidi-grey-text text-lg leading-relaxed mb-6">
                        Keep track of your diagnoses, allergies, and treatment history in one secure location. Share with doctors instantly when needed.
                    </p>
                    <button class="text-heidi-teal font-bold hover:text-heidi-teal-dark flex items-center group">
                        View Medical Records <i class="bi bi-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="bg-white py-20 border-t border-gray-100">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold text-heidi-navy mb-12">Trusted by Patients Everywhere</h2>
            
            <div class="bg-white border border-gray-100 rounded-2xl p-8 shadow-lg relative">
                <div class="absolute -top-6 left-1/2 transform -translate-x-1/2">
                    <div class="w-12 h-12 bg-heidi-teal rounded-full flex items-center justify-center text-white text-2xl shadow-md">
                        <i class="bi bi-quote"></i>
                    </div>
                </div>
                <p class="text-xl text-gray-700 italic mb-6 pt-6">
                    "The interface is incredibly intuitive. I can book appointments and check my results without any confusion. It's exactly what a modern healthcare platform should be."
                </p>
                <div class="flex items-center justify-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-gray-200 overflow-hidden">
                        <!-- Placeholder Avatar -->
                        <svg class="w-full h-full text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    </div>
                    <div class="text-left">
                        <div class="font-bold text-heidi-navy">Sarah Jenkins</div>
                        <div class="text-sm text-gray-500">Patient since 2023</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-heidi-teal py-20">
        <div class="max-w-4xl mx-auto px-6 text-center text-white">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">Ready to take control of your health?</h2>
            <p class="text-lg opacity-90 mb-8 max-w-2xl mx-auto">
                Join thousands of patients who are experiencing the future of healthcare management today.
            </p>
            <button class="bg-white text-heidi-teal text-lg font-bold py-3 px-10 rounded-lg shadow-lg hover:bg-gray-50 transition-colors">
                Get Started Now
            </button>
        </div>
    </section>

    <!-- Footer Mockup -->
    <footer class="bg-white py-12 border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="text-gray-400 text-sm">
                &copy; {{ date('Y') }} SmartMed (Design Mockup). All rights reserved.
            </div>
            <div class="flex gap-6">
                <a href="#" class="text-gray-400 hover:text-heidi-teal"><i class="bi bi-twitter text-xl"></i></a>
                <a href="#" class="text-gray-400 hover:text-heidi-teal"><i class="bi bi-linkedin text-xl"></i></a>
                <a href="#" class="text-gray-400 hover:text-heidi-teal"><i class="bi bi-instagram text-xl"></i></a>
            </div>
        </div>
    </footer>

</body>
</html>