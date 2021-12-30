<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FunctionHelper;
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
        $query = WorkingLog::with('ticket', 'status')
                           ->when($request->has('filter'), function ($query) use ($request) {
                               return FunctionHelper::dxFilterGenerator($query, $request->filter);
                           });

        $logs = $query->limit($request->take)->offset($request->skip)->get();
        return [
            'data' => $logs,
            'totalCount' => $query->count()
        ];
    }

    public function tickets()
    {
        $tickets = Ticket::limit(10)->get();
        return $tickets;
    }

    public function recreateLogs(Request $request)
    {
        $tickets = Ticket::whereIn('id', $request->selectedTickets)->get();
        TicketHelper::recreateLog($tickets);
        return true;
    }
}
