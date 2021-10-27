<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\WorkingLog;
use Illuminate\Http\Request;

class WorkingLogsController extends Controller
{

    public function index()
    {
        return view('admin.workinglogs.index');
    }

    public function data(Request $request)
    {
        $logs = WorkingLog::with('ticket', 'status')->get();
        return $logs;
    }
}
