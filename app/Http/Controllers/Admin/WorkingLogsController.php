<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\TicketHelper;
use App\Http\Controllers\Controller;
use App\Ticket;
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

    public function tickets()
    {
        $tickets = Ticket::all();
        return $tickets;
    }

    public function recreateLogs(Request $request)
    {
        $tickets = Ticket::whereIn('id', $request->selectedTickets)->get();
        TicketHelper::recreateLog($tickets);
        return true;
    }
}
