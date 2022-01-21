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
                           ->when($request->has('filter'), function ($q) use ($request) {
                               return FunctionHelper::dxFilterGenerator($q, $request->filter);
                           });

        $logs = $query->limit($request->take)->offset($request->skip)->get();
        return [
            'data' => $logs,
            'totalCount' => $query->count()
        ];
    }

    public function tickets(Request $request)
    {
        $query = Ticket::with('status')->when($request->has('filter'), function ($q) use ($request) {
            return FunctionHelper::dxFilterGenerator($q, $request->filter);
        });

        $tickets = $query->limit($request->take)->offset($request->skip)->get();
        return [
            'data' => $tickets,
            'totalCount' => $query->count()
        ];
    }

    public function recreateLogs(Request $request)
    {
        $tickets = Ticket::whereIn('id', $request->selectedTickets)->get();
        TicketHelper::recreateLog($tickets);
        return true;
    }
}
