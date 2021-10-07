<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Priority;
use App\Project;
use App\Status;
use Gate;
use Symfony\Component\HttpFoundation\Response;
use App\Ticket;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController
{
    public function index()
    {
        abort_if(Gate::denies('dashboard_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $date = now();
        $tickets = Ticket::with('project', 'status', 'category', 'priority')
                         ->whereMonth('created_at', $date->month)
                         ->when(!auth()->user()->isAdmin(), function ($query) {
                             return $query->whereHas('project', function ($q) {
                                        $q->where('id', auth()->user()->projects->first()->id ?? 0);
                            });
                         })
                         ->get();
        $statuses = Status::all();
        $categories = Category::all();
        $priorities = Priority::all();
        return view('home', compact('tickets', 'statuses', 'categories', 'priorities', 'date'));


        // $monthNum  = date('m');
        // $dateObj   = DateTime::createFromFormat('!m', $monthNum);
        // $monthName = $dateObj->format('F'); // March

        // $totalTickets = Ticket::count();
        // $openTickets = Ticket::whereHas('status', function($query) {
        //     $query->whereName('Open');
        // })->count();
        // $closedTickets = Ticket::whereHas('status', function($query) {
        //     $query->whereName('Closed');
        // })->count();
        // $workingTickets = Ticket::whereHas('status', function($query){
        //     $query->whereName('Working');
        // })->count();
        // $pendingTickets = Ticket::whereHas('status', function($query){
        //     $query->whereName('Pending');
        // })->count();
        // $confirmTickets = Ticket::whereHas('status', function($query){
        //     $query->whereName('Confirm Client');
        // })->count();

        // return view('home', compact('totalTickets', 'openTickets', 'workingTickets', 'pendingTickets', 'confirmTickets', 'closedTickets', 'monthName'));
    }

    public function getJumlahTiketHarian(){
        $date_temp = date('d/m/Y');
        $date = explode('/', $date_temp);
        $alltgl = cal_days_in_month(CAL_GREGORIAN, $date[1], $date[2]);
        // dd($alltgl);
        $user_role = Auth::user()->roles()->first()->id;
        // dd($user_role);
        if ($user_role == 1) {
            $datas = DB::table('tickets')
                ->selectRaw('DAY(created_at) as tgl')
                ->selectRaw('COUNT(*) as jumlah')
                ->whereMonth('created_at', $date[1])
                ->whereYear('created_at', $date[2])
                ->groupByRaw('DAY(created_at)')
                ->orderByRaw('DAY(created_at)', 'asc')
                ->get();
        } else {
            $project = Auth::user()->project->first() ?? null;
            if(!is_null($project)){
                $datas = DB::table('tickets')
                    ->selectRaw('DAY(created_at) as tgl')
                    ->selectRaw('COUNT(*) as jumlah')
                    ->whereMonth('created_at', $date[1])
                    ->whereYear('created_at', $date[2])
                    ->where('project_id', $project)
                    ->groupByRaw('DAY(created_at)')
                    ->orderByRaw('DAY(created_at)', 'asc')
                    ->get();
            }
        }
        foreach($datas as $data)
        {
            if(isset($data)){
                $date_month[] = $data->tgl;
                $jumlah[] = $data->jumlah;
            } else {
                $date_month[] = null;
                $jumlah[] = null;
                break;
            }

        }
        $data = [];
        for($i=1;$i<=$alltgl;$i++)
        {
            if(isset($date_month))
            {
                $temp_index = array_search($i, $date_month);
                if($temp_index === false)
                {
                    array_push($data, array('tgl'=>(int)$i, 'value'=>0));
                } else {
                    array_push($data, array('tgl'=>(int)$i, 'value'=>(int)$jumlah[$temp_index]));
                }
            } else {
                array_push($data, array('tgl'=>(int)$i, 'value'=>0));
            }
        }
        return collect($data);
    }

    public function getRataDurasiSelesai(){

    }

    public function getLastComment(){
        $user_role = Auth::user()->roles()->first()->id;
        if($user_role == 1){
            $data = DB::table('comments')
                ->join('tickets', 'comments.ticket_id', '=', 'tickets.id')
                ->join('projects', 'tickets.project_id', '=', 'projects.id')
                ->select('comments.created_at as tgl', 'projects.name as proyek', 'tickets.title as judul_tiket', 'comments.author_name as author', 'comments.comment_text as deskripsi')
                ->get();
        } else {
            $project = Auth::user()->project->first()->id ?? null;
            if (!is_null($project)) {
                $data = DB::table('comments')
                ->join('tickets', 'comments.ticket_id', '=', 'tickets.id')
                ->join('projects', 'tickets.project_id', '=', 'projects.id')
                ->select('comments.created_at as tgl', 'projects.name as proyek', 'tickets.title as judul_tiket', 'comments.author_name as author', 'comments.comment_text as deskripsi')
                ->where('projects.id', $project)
                ->get();
                // dd($data);
            }
        }
        return collect($data);
    }
}
