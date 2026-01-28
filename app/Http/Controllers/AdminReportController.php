<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Appointment;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role !== 'admin') {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        return view('admin.reports.index');
    }

    public function revenue()
    {
        // Monthly Revenue Calculation
        $revenues = Payment::select(
                DB::raw("DATE_FORMAT(payment_date, '%Y-%m') as month"),
                DB::raw("SUM(amount) as total_amount"),
                DB::raw("COUNT(*) as transaction_count")
            )
            ->where('status', 'paid')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get();

        // Calculate Summary Statistics
        $totalRevenue = Payment::where('status', 'paid')->sum('amount');
        $totalTransactions = Payment::where('status', 'paid')->count();
        $averageTransaction = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;
        
        // Calculate Growth (vs last month)
        $currentMonth = Carbon::now()->format('Y-m');
        $lastMonth = Carbon::now()->subMonth()->format('Y-m');
        
        $currentMonthRevenue = Payment::where('status', 'paid')
            ->where(DB::raw("DATE_FORMAT(payment_date, '%Y-%m')"), $currentMonth)
            ->sum('amount');
            
        $lastMonthRevenue = Payment::where('status', 'paid')
            ->where(DB::raw("DATE_FORMAT(payment_date, '%Y-%m')"), $lastMonth)
            ->sum('amount');
            
        $growthPercentage = 0;
        if ($lastMonthRevenue > 0) {
            $growthPercentage = (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100;
        }

        return view('admin.reports.revenue', compact(
            'revenues', 
            'totalRevenue', 
            'totalTransactions', 
            'averageTransaction',
            'growthPercentage',
            'currentMonthRevenue'
        ));
    }

    public function exportRevenue()
    {
        $revenues = Payment::select(
                DB::raw("DATE_FORMAT(payment_date, '%Y-%m') as month"),
                DB::raw("SUM(amount) as total_amount"),
                DB::raw("COUNT(*) as transaction_count")
            )
            ->where('status', 'paid')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=monthly_revenue_report.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($revenues) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Month', 'Transactions', 'Total Revenue (RM)']);

            foreach ($revenues as $revenue) {
                fputcsv($file, [
                    \Carbon\Carbon::createFromFormat('Y-m', $revenue->month)->format('F Y'),
                    $revenue->transaction_count,
                    number_format($revenue->total_amount, 2, '.', '')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function invoices(Request $request)
    {
        $query = Payment::with(['appointment.patient', 'appointment.doctor']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('receipt_number', 'like', "%{$search}%")
                  ->orWhereHas('appointment.patient', function($subQ) use ($search) {
                      $subQ->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->has('date') && $request->date != '') {
            $query->whereDate('payment_date', $request->date);
        }

        $invoices = $query->orderBy('payment_date', 'desc')->paginate(15);

        return view('admin.reports.invoices', compact('invoices'));
    }

    public function appointments()
    {
        // Overall Statistics
        $totalAppointments = Appointment::count();
        $completedAppointments = Appointment::where('status', 'completed')->count();
        $cancelledAppointments = Appointment::whereIn('status', ['cancelled', 'rejected'])->count();
        
        // Appointments by Status
        $statsByStatus = Appointment::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // Appointments by Month (Last 12 months)
        $monthlyStats = Appointment::select(
            DB::raw("DATE_FORMAT(appointment_date, '%Y-%m') as month"),
            DB::raw("count(*) as total")
        )
        ->groupBy('month')
        ->orderBy('month', 'desc')
        ->limit(12)
        ->get();

        return view('admin.reports.appointments', compact('totalAppointments', 'completedAppointments', 'cancelledAppointments', 'statsByStatus', 'monthlyStats'));
    }
}
