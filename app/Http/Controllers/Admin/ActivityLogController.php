<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index()
    {
        $logs = ActivityLog::with('user')->latest()->paginate(20);
        return view('admin.activity-logs.index', compact('logs'));
    }

    public function show(ActivityLog $log)
    {
        return view('admin.activity-logs.show', compact('log'));
    }
}
