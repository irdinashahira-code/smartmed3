@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse shadow-sm" style="min-height: calc(100vh - 56px);">
            <div class="position-sticky pt-3">
                <div class="list-group list-group-flush">
                    <a href="{{ route('patient.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('patient.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2 me-2"></i> {{ __('Dashboard') }}
                    </a>
                    <a href="{{ route('patient.appointments.create') }}" class="list-group-item list-group-item-action {{ request()->routeIs('patient.appointments.create') ? 'active' : '' }}">
                        <i class="bi bi-calendar-plus me-2"></i> {{ __('Book Appointment') }}
                    </a>
                    <a href="{{ route('patient.appointments.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('patient.appointments.index') ? 'active' : '' }}">
                        <i class="bi bi-calendar-check me-2"></i> {{ __('My Appointments') }}
                    </a>
                    <a href="{{ route('patient.medical_history.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('patient.medical_history.index') ? 'active' : '' }}">
                        <i class="bi bi-file-medical me-2"></i> {{ __('Medical History') }}
                    </a>
                    <a href="{{ route('patient.profile.show') }}" class="list-group-item list-group-item-action {{ request()->routeIs('patient.profile.show') ? 'active' : '' }}">
                        <i class="bi bi-person me-2"></i> {{ __('My Profile') }}
                    </a>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="pt-3 pb-2 mb-3">
                @yield('patient_content')
            </div>
        </main>
    </div>
</div>
@endsection
