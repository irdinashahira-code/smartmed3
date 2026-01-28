<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ctivity
{
    public static function addToLog($action, $module, $description = null)
    {
        $log = [];
        $log['user_id'] = Auth::check() ? Auth::id() : null;
        $log['action'] = $action;
        $log['module'] = $module;
        $log['description'] = $description;
        $log['ip_address'] = Request::ip();
        ActivityLog::create($log);
    }

    public static function logLists()
    {
        return ActivityLog::with('user')->latest()->paginate(20);
    }
}
