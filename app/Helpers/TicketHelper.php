<?php
namespace App\Helpers;

use App\Project;
use App\Ticket;
use App\Workclock;
use App\WorkingLog;
use Carbon\Carbon;

class TicketHelper {

    public static function create($request)
    {
        $user = auth()->user();
        $project = $user->projects->first();

        if (empty($project) && !$user->isAdmin()) {
            return [
                'success' => false,
                'message' => 'Tiket anda gagal ditambahkan. Silahkan hubungi Admin kami untuk info lebih lanjut',
            ];
        }

        if ($user->isAdmin()) {
            $project = Project::find($request->project_id);
        }

        $ticket = Ticket::create([
            'title' => $request->title,
            'code' => FunctionHelper::generateTicketCode($project->id),
            'content' => $request->content,
            'author_name' => $user->name,
            'author_email' => $user->email,
            'status_id' => $request->status_id ?? 1,
            'priority_id' => $request->priority_id ?? 1,
            'category_id' => $request->category_id ?? 1,
            'assigned_to_user_id' => $request->assigned_to_user_id,
            'project_id' => $project->id
        ]);
        $ticket->project()->associate($project);
        $ticket->save();

        foreach ($request->input('attachments', []) as $file) {
            $ticket->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('attachments');
        }

        return [
            'success' => false,
            'message' => 'Tiket anda telah berhasil ditambahkan. Silahkan tunggu hingga mendapatkan balasan melalui email dari kami',
            'data' => $ticket
        ];
    }

    public static function generateWorkingLog($ticket_id)
    {
        $ticket = Ticket::find($ticket_id);
        $lastLog = WorkingLog::where('ticket_id', $ticket->id)->latest()->first();
        $workClock = Workclock::where('day', now()->dayName)->first();

        if (empty($lastLog)) {
            if ($ticket->status_id == 3) {
                WorkingLog::create([
                    'ticket_id' => $ticket->id ?? $ticket_id,
                    'status_id' => $ticket->status_id,
                    'started_at' => now(),
                ]);
            }
        }
        elseif (!empty($lastLog)) {
            if ($ticket->status_id >= 3) { // Hardcode of "Working"
                if ($lastLog->status_id >= 3 && $lastLog->status_id < 5) { // Hardcode of "Closed"
                    if (!empty($lastLog->started_at) && empty($lastLog->finished_at)) {
                        if (now() >= $workClock->time_start->addHours($workClock->duration)) {
                            // Tutup hari. OTOMASTIS
                            $lastLog->finished_at = $workClock->time_start->addHours($workClock->duration);
                        }
                        else {
                            // MANUAL
                            $lastLog->status_id = 5;
                            $lastLog->finished_at = now();
                        }
                        $lastLog->save();
                        $lastLog->refresh();
                    }
                    elseif (!empty($lastLog->started_at) && !empty($lastLog->finished_at)) {
                        WorkingLog::create([
                            'ticket_id' => $ticket->id ?? $ticket_id,
                            'status_id' => 3, // Hardcode of "Working"
                            'started_at' => $workClock->time_start,
                        ]);
                    }
                }
            }
        }
    }

    public static function calculateWorkDuration($tickets, $ignorePerfectLog = false)
    {
        if (app()->runningInConsole()) {
            $progressIndex = 0;
            $progressMax = $tickets->count();
        }

        foreach ($tickets as $ticket) {
            $ticketLogs = WorkingLog::where('ticket_id', $ticket->id)->get();
            if (static::isPerfectLog($ticket->id) || $ignorePerfectLog) {
                $ticket->work_duration = $ticketLogs->map(function($log, $key) {
                    return $log->finished_at->diffInSeconds($log->started_at);
                })
                ->sum();
                $ticket->save();
                $ticket->refresh();
            }

            if (app()->runningInConsole()) {
                $progressIndex++;
                FunctionHelper::progressBar($progressIndex, $progressMax);
            }
        }
    }

    public static function isPerfectLog($ticket_id)
    {
        $logs = WorkingLog::where('ticket_id', $ticket_id)->get();
        return !$logs->pluck('started_at')->contains(null) &&
               !$logs->pluck('finished_at')->contains(null) &&
               $logs->pluck('status_id')->contains(5);
    }

    public static function recreateLog($tickets)
    {
        WorkingLog::whereIn('ticket_id', $tickets->pluck('id')->toArray())->delete();

        $progressIndex = 0;
        $progressMax = $tickets->count();
        foreach ($tickets as $ticket) {

            $workStart = Carbon::create($ticket->work_start);
            $workEnd = Carbon::create($ticket->work_end);
            $diffInDays = $workEnd->hour(0)->minute(0)->second(0)
                                  ->diffInDays(
                                      $workStart->hour(0)->minute(0)->second(0)
                                  );
            // If is same date
            if ($diffInDays == 0) {
                WorkingLog::create([
                    'ticket_id' => $ticket->id,
                    'status_id' => 5, // Hard code of "Closed"
                    'started_at' => $ticket->work_start,
                    'finished_at' => $ticket->work_end,
                ]);
            }
            elseif ($diffInDays > 0) {
                for ($i = 0; $i <= $diffInDays; $i++) {
                    $start = Carbon::create($ticket->work_start)->addDays($i);
                    $workClock = Workclock::where('day', $start->dayName)->first();

                    if ($workClock->duration > 0) {
                        $startTime = explode(":", $workClock->time_start->toTimeString());
                        $endTime = explode(":", $workClock->time_start->addHours($workClock->duration)->toTimeString());

                        if ($i == 0) { // first loop
                            $started_at = $start->toDateTimeString();
                        }
                        else {
                            $started_at = $start->hour($startTime[0])
                                                ->minute($startTime[1])
                                                ->second($startTime[2])
                                                ->toDateTimeString();
                        }

                        if ($i == $diffInDays) { // last loop
                            $finished_at = Carbon::create($ticket->work_end);
                            if ($finished_at < $start) {
                                $finished_at = $start;
                            }
                            $status_id = 5; // Hard code of "Closed"
                        }
                        else {
                            $finished_at = $start->hour($endTime[0])
                                                 ->minute($endTime[1])
                                                 ->second($endTime[2])
                                                 ->toDateTimeString();
                            $status_id = 3; // Hard code of "Working"
                        }

                        WorkingLog::create([
                            'ticket_id' => $ticket->id,
                            'status_id' => $status_id,
                            'started_at' => $started_at,
                            'finished_at' => $finished_at,
                        ]);
                    }
                }
            }

            $progressIndex++;
            FunctionHelper::progressBar($progressIndex, $progressMax);
        }
        static::calculateWorkDuration($tickets);
    }
}
