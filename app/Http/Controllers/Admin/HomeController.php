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
use Carbon\CarbonPeriod;
use Illuminate\Support\Str;

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

    public function getTicketsThisWeek(Request $request){
        $dateFilter = Carbon::createFromFormat('Y-m', $request->monthFilter)->week($request->weekFilter);
        // dd($dateFilter);
        $tickets = Ticket::selectRaw('DATE(created_at) as date, COUNT(id) as total')
                         ->whereDate('created_at', '>=', $dateFilter->startOfWeek()->toDateString())
                         ->whereDate('created_at', '<=', $dateFilter->endOfWeek()->toDateString())
                         ->when(!auth()->user()->isAdmin(), function ($query) {
                             $project_id = auth()->user()->projects()->first()->id;
                             return $query->where('project_id', $project_id);
                         })
                         ->groupByRaw('DATE(created_at)')
                         ->orderByRaw('DATE(created_at)', 'asc')
                         ->get();

        $periode = CarbonPeriod::create($dateFilter->startOfWeek()->toDateString(), $dateFilter->endOfWeek()->toDateString());
        $dates = collect($periode->toArray())->map(function ($date) use ($tickets) {
            $ticket = $tickets->where('date', $date->toDateString())->first();
            return [
                'date' => $date->toDateString(),
                'name' => $date->locale('id')->dayName . "\n(" . $date->format('d/m/Y') . ')',
                'value' => $ticket->total ?? 0
            ];
        });
        return collect($dates);
    }

    public function getDataDoughnut(Request $request){
        $dateFilter = Carbon::createFromFormat('Y-m', $request->monthFilter)->day(1);
        $tickets = Ticket::with(Str::singular($request->table))
                         ->whereDate('created_at', '>=', $dateFilter->toDateString())
                         ->whereDate('created_at', '<=', $dateFilter->endOfMonth()->toDateString())
                         ->get();
        $names = DB::table($request->table)->pluck('name');
        $data = [];
        $groupTicket = $tickets->groupBy(Str::singular($request->table).'.name');
        $ticketKeys = $groupTicket->keys();
        foreach ($names as $name) {
            array_push($data, [
                'name' => $name,
                'value' => in_array($name, $ticketKeys->toArray()) ? $groupTicket[$name]->count() : 0
            ]);
        }

        return $data;
    }

    public function getAvgTime($tickets){
        return gmdate(
            'H \j\a\m i \m\e\n\i\t',
            $tickets->map(function ($ticket, $key) {
                return !empty($ticket->work_duration) ? $ticket->work_duration : 0;
            })->avg()
        );
    }

    public function getJumlahTiketHarian(Request $request){
        $dateFilter = Carbon::createFromFormat('Y-m', $request->monthFilter)->day(1);
        $tickets = Ticket::selectRaw('DAY(created_at) as tgl, COUNT(id) as jumlah')
                      ->whereDate('created_at', '>=', $dateFilter->toDateString())
                      ->whereDate('created_at', '<=', $dateFilter->endOfMonth()->toDateString())
                      ->when(!auth()->user()->isAdmin(), function ($query) {
                          $project_id = auth()->user()->projects()->first()->id;
                          return $query->where('project_id', $project_id);
                      })
                      ->groupByRaw('DAY(created_at)')
                      ->orderByRaw('DAY(created_at)', 'asc')
                      ->get();

        $periode = CarbonPeriod::create($dateFilter->startOfMonth()->toDateString(), $dateFilter->endOfMonth()->toDateString());
        $dates = collect($periode->toArray())->map(function ($date) use ($tickets) {
            $ticket = $tickets->where('tgl', $date->day)->first();
            return [
                'tgl' => $date->day,
                'value' => $ticket->jumlah ?? 0
            ];
        });
        return $dates;
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

    public function weeksInMonth(Request $request)
    {
        $date = Carbon::createFromFormat('Y-m', $request->monthFilter);
        $start = $date->startOfMonth()->toDateString();
        $end = $date->endOfMonth()->toDateString();
        $periode = CarbonPeriod::create($start, $end);
        $dates = collect($periode->toArray())->groupBy(function ($value, $key) {
            return $value->week;
        });

        if (filter_var($request->onlyWeek, FILTER_VALIDATE_BOOLEAN)) {
            return $dates->keys();
        }

        return $dates;
    }
}
