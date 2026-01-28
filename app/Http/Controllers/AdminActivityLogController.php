<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\ctivity;

class AdminActivityLogController extends Controller
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
        $logs = ctivity::logLists();
        return view('admin.activity_logs.index', compact('logs'));
    }
}
