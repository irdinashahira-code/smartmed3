<?php

use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('welcome');
});

// Temporary Database Setup Route (For Render Deployment)
Route::get('/deploy-setup', function () {
    try {
        // Increase memory limit for migration
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 300);

        \Illuminate\Support\Facades\Artisan::call('migrate:fresh', [
            '--force' => true,
            '--seed' => true
        ]);
        return '<h1>Database Setup Successful! ✅</h1><p>Tables created and seeded.</p><pre>' . \Illuminate\Support\Facades\Artisan::output() . '</pre>';
    } catch (\Exception $e) {
        return '<h1>Setup Failed ❌</h1><p>' . $e->getMessage() . '</p><pre>' . $e->getTraceAsString() . '</pre>';
    }
});

Route::get('/register', function () {
    return view('auth.register_selection');
})->name('register');

use App\Http\Controllers\Auth\LoginController;

Auth::routes(['register' => false]); // Disable default register route to avoid conflict

// Google Auth Routes
Route::get('auth/google', [LoginController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [LoginController::class, 'handleGoogleCallback']);

// Profile Completion (For Google Auth users)
Route::middleware(['auth'])->group(function () {
    Route::get('/complete-profile', [App\Http\Controllers\Auth\CompleteProfileController::class, 'showForm'])->name('complete.profile');
    Route::post('/complete-profile', [App\Http\Controllers\Auth\CompleteProfileController::class, 'store'])->name('complete.profile.submit');
});

Route::get('/register/doctor', [RegisterController::class, 'showDoctorRegistrationForm'])->name('register.doctor');
Route::post('/register/doctor', [RegisterController::class, 'registerDoctor'])->name('register.doctor.submit');

Route::get('/register/patient', [RegisterController::class, 'showPatientRegistrationForm'])->name('register.patient');
Route::post('/register/patient', [RegisterController::class, 'registerPatient'])->name('register.patient.submit');

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/patient/dashboard', [App\Http\Controllers\PatientController::class, 'dashboard'])->name('patient.dashboard');
    Route::get('/patient/design-mockup', function () { return view('patient.design_reference'); })->name('patient.design_mockup');
    Route::post('/patient/ads/interaction', [App\Http\Controllers\PatientController::class, 'trackAdInteraction'])->name('patient.ads.interaction');

    // Appointment Routes
    Route::get('/patient/appointments', [App\Http\Controllers\AppointmentController::class, 'index'])->name('patient.appointments.index');
    Route::get('/patient/appointments/history', [App\Http\Controllers\AppointmentController::class, 'history'])->name('patient.appointments.history');
    Route::get('/patient/book-appointment', [App\Http\Controllers\AppointmentController::class, 'create'])->name('patient.appointments.create');
    // Fix for MethodNotAllowedHttpException: Redirect GET requests to preview back to create
    Route::get('/patient/book-appointment/preview', function() {
        return redirect()->route('patient.appointments.create');
    });
    Route::post('/patient/book-appointment/preview', [App\Http\Controllers\AppointmentController::class, 'preview'])->name('patient.appointments.preview');
    Route::post('/patient/book-appointment/confirm', [App\Http\Controllers\AppointmentController::class, 'store'])->name('patient.appointments.store');
    
    // Medical History Routes
    Route::get('/patient/medical-history', [App\Http\Controllers\MedicalHistoryController::class, 'index'])->name('patient.medical_history.index');
    Route::post('/patient/medical-history', [App\Http\Controllers\MedicalHistoryController::class, 'store'])->name('patient.medical_history.store');
    Route::delete('/patient/medical-history/{medicalHistory}', [App\Http\Controllers\MedicalHistoryController::class, 'destroy'])->name('patient.medical_history.destroy');

    // Feedback Route
    Route::post('/patient/feedback', [App\Http\Controllers\FeedbackController::class, 'store'])->name('patient.feedback.store');

    // Patient Profile Routes
    Route::get('/patient/profile', [App\Http\Controllers\PatientProfileController::class, 'show'])->name('patient.profile.show');
    Route::get('/patient/profile/edit', [App\Http\Controllers\PatientProfileController::class, 'edit'])->name('patient.profile.edit');
    Route::put('/patient/profile', [App\Http\Controllers\PatientProfileController::class, 'update'])->name('patient.profile.update');

    Route::get('/api/slots', [App\Http\Controllers\AppointmentController::class, 'getSlots'])->name('api.slots');
    Route::get('/api/slots/stream', [App\Http\Controllers\SlotStreamController::class, 'stream'])->name('api.slots.stream');
    Route::post('/patient/appointments/{appointment}/cancel', [App\Http\Controllers\AppointmentController::class, 'cancel'])->name('patient.appointments.cancel');
    Route::get('/patient/appointments/{appointment}/reschedule', [App\Http\Controllers\AppointmentController::class, 'showRescheduleForm'])->name('patient.appointments.reschedule');
    Route::post('/patient/appointments/{appointment}/reschedule', [App\Http\Controllers\AppointmentController::class, 'reschedule'])->name('patient.appointments.reschedule.submit');

    // Payment Routes (View)
    Route::get('/patient/appointments/{appointment}/payment', [App\Http\Controllers\AppointmentController::class, 'showPayment'])->name('patient.appointments.payment');
    // Old simulation route - keeping for reference or fallback if needed, but we will use the new one
    // Route::post('/patient/appointments/{appointment}/payment', [App\Http\Controllers\AppointmentController::class, 'processPayment'])->name('patient.appointments.payment.process');
    
    // iPay88 Payment Initiation
    Route::post('/patient/appointments/{appointment}/pay', [App\Http\Controllers\PaymentController::class, 'initiate'])->name('payment.initiate');

    Route::get('/patient/appointments/{appointment}/receipt', [App\Http\Controllers\AppointmentController::class, 'showReceipt'])->name('patient.appointments.receipt');
    
    // Doctor Routes
    Route::get('/doctor/dashboard', [App\Http\Controllers\DoctorController::class, 'dashboard'])->name('doctor.dashboard');
    
    // Doctor Profile Routes
    Route::get('/doctor/profile', [App\Http\Controllers\DoctorProfileController::class, 'show'])->name('doctor.profile.show');
    Route::get('/doctor/profile/edit', [App\Http\Controllers\DoctorProfileController::class, 'edit'])->name('doctor.profile.edit');
    Route::put('/doctor/profile', [App\Http\Controllers\DoctorProfileController::class, 'update'])->name('doctor.profile.update');

    Route::get('/doctor/appointments/today', [App\Http\Controllers\DoctorController::class, 'todayAppointments'])->name('doctor.appointments.today');
    Route::post('/doctor/appointments/{appointment}/approve-cancel', [App\Http\Controllers\DoctorController::class, 'approveCancel'])->name('doctor.appointments.approve-cancel');
    Route::post('/doctor/appointments/{appointment}/approve-reschedule', [App\Http\Controllers\DoctorController::class, 'approveReschedule'])->name('doctor.appointments.approve-reschedule');
    Route::get('/doctor/appointments/{appointment}/details', [App\Http\Controllers\DoctorController::class, 'getAppointmentDetails'])->name('doctor.appointments.details');
    Route::get('/doctor/appointments/stream', [App\Http\Controllers\DoctorStreamController::class, 'stream'])->name('doctor.appointments.stream');
    Route::post('/doctor/appointments/{appointment}/notes', [App\Http\Controllers\DoctorController::class, 'saveConsultationNotes'])->name('doctor.appointments.notes');
    Route::post('/doctor/medical-images/upload', [App\Http\Controllers\MedicalImageController::class, 'upload'])->name('doctor.medical-images.upload');

    // Doctor Schedule Management
    Route::get('/doctor/schedule', [App\Http\Controllers\DoctorScheduleController::class, 'index'])->name('doctor.schedule.index');
    Route::post('/doctor/schedule', [App\Http\Controllers\DoctorScheduleController::class, 'updateSchedule'])->name('doctor.schedule.update');
    Route::post('/doctor/schedule/leave', [App\Http\Controllers\DoctorScheduleController::class, 'addLeave'])->name('doctor.schedule.leave.add');

    Route::get('/doctor/feedback', [App\Http\Controllers\DoctorController::class, 'feedback'])->name('doctor.feedback');
    Route::delete('/doctor/schedule/leave/{leave}', [App\Http\Controllers\DoctorScheduleController::class, 'deleteLeave'])->name('doctor.schedule.leave.delete');

    // Queue Management Routes
    Route::post('/patient/appointments/{appointment}/check-in', [App\Http\Controllers\QueueController::class, 'checkIn'])->name('patient.queue.checkin');
    Route::get('/patient/queue-status', [App\Http\Controllers\QueueController::class, 'getPatientQueueStatus'])->name('patient.queue.status');
    
    Route::get('/doctor/queue/state', [App\Http\Controllers\QueueController::class, 'getDoctorQueueState'])->name('doctor.queue.state');
    Route::get('/doctor/queue/stream', [App\Http\Controllers\QueueController::class, 'streamQueue'])->name('doctor.queue.stream');
    Route::post('/doctor/queue/call-next', [App\Http\Controllers\QueueController::class, 'callNext'])->name('doctor.queue.call-next');
    Route::post('/doctor/queue/delay', [App\Http\Controllers\QueueController::class, 'notifyDelay'])->name('doctor.queue.delay');
    Route::post('/doctor/queue/{appointment}/update', [App\Http\Controllers\QueueController::class, 'updateQueueStatus'])->name('doctor.queue.update');

    // Admin / Notification Dashboard
    Route::get('/admin/notifications', [App\Http\Controllers\AdminController::class, 'notifications'])->name('admin.notifications');

    // Admin Routes
    Route::prefix('admin')->middleware(['auth'])->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
        
        // Doctor Management
        Route::get('/doctors', [App\Http\Controllers\AdminController::class, 'doctors'])->name('admin.doctors');
        Route::get('/doctors/create', [App\Http\Controllers\AdminController::class, 'createDoctor'])->name('admin.doctors.create');
        Route::post('/doctors', [App\Http\Controllers\AdminController::class, 'storeDoctor'])->name('admin.doctors.store');
        Route::get('/doctors/{id}/edit', [App\Http\Controllers\AdminController::class, 'editDoctor'])->name('admin.doctors.edit');
        Route::put('/doctors/{id}', [App\Http\Controllers\AdminController::class, 'updateDoctor'])->name('admin.doctors.update');
        Route::post('/doctors/{id}/approve', [App\Http\Controllers\AdminController::class, 'approveDoctor'])->name('admin.doctors.approve');
        Route::post('/doctors/{id}/reject', [App\Http\Controllers\AdminController::class, 'rejectDoctor'])->name('admin.doctors.reject');

        // Admin Doctor Schedule Management
        Route::get('/doctors/{id}/schedule', [App\Http\Controllers\AdminDoctorScheduleController::class, 'index'])->name('admin.doctors.schedule');
        Route::post('/doctors/{id}/schedule', [App\Http\Controllers\AdminDoctorScheduleController::class, 'updateSchedule'])->name('admin.doctors.schedule.update');
        Route::post('/doctors/leaves/{id}/approve', [App\Http\Controllers\AdminDoctorScheduleController::class, 'approveLeave'])->name('admin.doctors.leaves.approve');
        Route::post('/doctors/leaves/{id}/reject', [App\Http\Controllers\AdminDoctorScheduleController::class, 'rejectLeave'])->name('admin.doctors.leaves.reject');
        Route::delete('/doctors/schedule/leave/{leave}', [App\Http\Controllers\AdminDoctorScheduleController::class, 'deleteLeave'])->name('admin.doctors.schedule.leave.delete');

        // Patient Management
        Route::get('/patients', [App\Http\Controllers\AdminController::class, 'patients'])->name('admin.patients');
        Route::get('/patients/{id}', [App\Http\Controllers\AdminController::class, 'showPatient'])->name('admin.patients.show');
        Route::post('/patients/{id}/toggle-status', [App\Http\Controllers\AdminController::class, 'togglePatientStatus'])->name('admin.patients.toggle-status');
        Route::delete('/patients/{id}', [App\Http\Controllers\AdminController::class, 'deletePatient'])->name('admin.patients.delete');
        Route::get('/patients/{id}/medical-history', [App\Http\Controllers\AdminController::class, 'patientMedicalHistory'])->name('admin.patients.medical-history');

        // Appointment Management
        Route::get('/appointments', [App\Http\Controllers\AdminController::class, 'appointments'])->name('admin.appointments');

        // Advertisement Management
        Route::prefix('advertisements')->group(function () {
            Route::get('/', [App\Http\Controllers\AdminAdvertisementController::class, 'index'])->name('admin.advertisements.index');
            Route::get('/create', [App\Http\Controllers\AdminAdvertisementController::class, 'create'])->name('admin.advertisements.create');
            Route::post('/', [App\Http\Controllers\AdminAdvertisementController::class, 'store'])->name('admin.advertisements.store');
            Route::get('/analytics', [App\Http\Controllers\AdminAdvertisementController::class, 'analytics'])->name('admin.advertisements.analytics');
            Route::get('/{advertisement}/edit', [App\Http\Controllers\AdminAdvertisementController::class, 'edit'])->name('admin.advertisements.edit');
            Route::put('/{advertisement}', [App\Http\Controllers\AdminAdvertisementController::class, 'update'])->name('admin.advertisements.update');
            Route::delete('/{advertisement}', [App\Http\Controllers\AdminAdvertisementController::class, 'destroy'])->name('admin.advertisements.destroy');
        });

        // Report Management
        Route::get('/reports', [App\Http\Controllers\AdminReportController::class, 'index'])->name('admin.reports.index');
        Route::get('/reports/revenue', [App\Http\Controllers\AdminReportController::class, 'revenue'])->name('admin.reports.revenue');
        Route::get('/reports/revenue/export', [App\Http\Controllers\AdminReportController::class, 'exportRevenue'])->name('admin.reports.revenue.export');
        Route::get('/reports/invoices', [App\Http\Controllers\AdminReportController::class, 'invoices'])->name('admin.reports.invoices');
        Route::get('/reports/appointments', [App\Http\Controllers\AdminReportController::class, 'appointments'])->name('admin.reports.appointments');

        // Activity Logs
        Route::get('/activity-logs', [App\Http\Controllers\AdminActivityLogController::class, 'index'])->name('admin.activity_logs');
    });
});

// Payment Callbacks (Must be outside Auth middleware for Backend URL)
// Stripe redirects with GET, but some gateways use POST. Stripe checkout success_url is a GET.
Route::get('/payment/response', [App\Http\Controllers\PaymentController::class, 'response'])->name('payment.response');
Route::post('/payment/backend', [App\Http\Controllers\PaymentController::class, 'backend'])->name('payment.backend');
