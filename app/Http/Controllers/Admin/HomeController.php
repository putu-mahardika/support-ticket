<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Priority;
use App\Project;
use App\Status;
use Gate;
use Symfony\Component\HttpFoundation\Response;
use App\Ticket;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Helpers\FunctionHelper;


class HomeController
{
    public function index()
    {
        abort_if(Gate::denies('dashboard_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // dd(auth()->check());

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

        $avgTime = $this->getAvgTime($tickets);

        $weekNow = Carbon::now()->week;
        
        return view('home', compact('tickets', 'statuses', 'categories', 'priorities', 'date', 'avgTime', 'weekNow'));
    }

    public function getTicketsThisWeek(){
        $tickets = Ticket::
                    select(DB::raw('date(created_at) as date, count(*) as total'))
                    ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    ->groupBy(DB::raw('date(created_at)'))
                    ->get();

        $tickets->map(function($ticket){
            $ticket->hari = Carbon::create($ticket->date)->locale('id')->dayName;
            return $ticket;
        })->toArray();
        foreach($tickets as $ticket)
        {
            if(isset($ticket)){
                $ticketKeys[] = $ticket->hari;
                $ticketCount[$ticket->hari] = $ticket->total;
            } else {
                $ticketKeys[] = null;
                break;
            }
        }
        $names = FunctionHelper::getDayName(Carbon::getDays());
        $data = [];
        foreach ($names as $name) {
            array_push($data, array('name'=>$name, 
                        'value'=>in_array($name, $ticketKeys) ? $ticketCount[$name] : 0
            ));
        }
        // dd($data);
        return collect($data);
    }

    public function getDataDoughnut(Request $request){
        // dd($request->table);
        $table = $request->table;
        $tickets = Ticket::with(['category', 'priority', 'status'])->get();
        $names = DB::table($request->table)->pluck('name');
        $data = [];
        $ticketKeys = $tickets->groupBy('priority.name')->keys();

        foreach ($names as $name) {
            // $data->put(
            //     $name,
            //     in_array($name, $ticketKeys->toArray()) ?
            //     $tickets->groupBy('priority.name')[$name]->count() : 0
            // );
            array_push($data, array('name'=>$name, 
                        'value'=>in_array($name, $ticketKeys->toArray()) ? $tickets->groupBy('priority.name')[$name]->count() : 0
            ));
        }
        // dd($data);

        return $data;
        // dd(
        //     $tickets,
        //     $names,
        //     $dataStatuses
        // );
        // dd('aaaa');
    }

    

    public function getAvgTime($tickets){
        return gmdate(
            'H \j\a\m i \m\e\n\i\t',
            $tickets->map(function ($ticket, $key) {
                return !empty($ticket->work_duration) ? $ticket->work_duration : 0;
            })->avg()
        );
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
            $project_id = Auth::user()->projects->first()->id ?? null;
            if(!is_null($project_id)){
                $datas = DB::table('tickets')
                    ->selectRaw('DAY(created_at) as tgl')
                    ->selectRaw('COUNT(*) as jumlah')
                    ->whereMonth('created_at', $date[1])
                    ->whereYear('created_at', $date[2])
                    ->where('project_id', $project_id)
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
            // $data = DB::table('comments')
            //     ->join('tickets', 'comments.ticket_id', '=', 'tickets.id')
            //     ->join('projects', 'tickets.project_id', '=', 'projects.id')
            //     ->select('comments.created_at as tgl', 'projects.name as proyek', 'tickets.title as judul_tiket', 'comments.author_name as author', 'comments.comment_text as deskripsi')
            //     ->whereIn('comments.id', function($query){
            //         $query->max('comments.id')->groupBy('comments.ticket_id');
            //     })
            //     ->get();
            $data = DB::select(
                        DB::raw('SELECT a.created_at as tgl,
                                        c.name as proyek, 
                                        b.title as judul_tiket, 
                                        a.author_name as author, 
                                        a.comment_text as deskripsi 
                                        from comments a, 
                                        tickets b, 
                                        projects c 
                                        where a.id in (select max(id) from comments group by ticket_id) 
                                        and a.ticket_id = b.id 
                                        and b.project_id = c.id limit 10'
                                ));
        } else {
            $project = Auth::user()->projects->first()->id ?? null;
            if (!is_null($project)) {
                // $data = DB::table('comments')
                // ->join('tickets', 'comments.ticket_id', '=', 'tickets.id')
                // ->join('projects', 'tickets.project_id', '=', 'projects.id')
                // ->select('comments.created_at as tgl', 'projects.name as proyek', 'tickets.title as judul_tiket', 'comments.author_name as author', 'comments.comment_text as deskripsi')
                // ->where('projects.id', $project)
                // ->get();
                $data = DB::select(
                            DB::raw('SELECT a.created_at as tgl,
                                            c.name as proyek, 
                                            b.title as judul_tiket, 
                                            a.author_name as author, 
                                            a.comment_text as deskripsi from comments a, 
                                            tickets b, projects c 
                                            where a.id in (select max(id) from comments group by ticket_id) 
                                            and a.ticket_id = b.id and b.project_id = c.id 
                                            and b.project_id = ' . $project . '
                                            limit 10'
                                            ));
            }
        }
        return collect($data);
    }
}
