<?php
namespace App\Helpers;

use App\Project;
use App\Ticket;
use App\Workclock;
use App\WorkingLog;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    public static function calculateWorkDuration($tickets)
    {
        /**
         * Every ticket must be valid
         */
        $isValidTickets = $tickets->every(function ($ticket, $key) {
            return $ticket->status_id == 5 && !empty($ticket->work_start) && !empty($ticket->work_end);
        });

        /**
         * If the ticket collection is not valid, it will be skipped
         */
        if (!$isValidTickets) return response('Tiket tidak valid!', 400);

        $workClocks = Workclock::all();
        $result = true;
        foreach ($tickets as $ticket) {
            $ticket->refresh();
            $period = CarbonPeriod::create($ticket->work_start, $ticket->work_end);

            if (count($period->toArray()) > 1) {
                $workingHours = collect($period->toArray())->map(function ($date) use ($ticket, $workClocks) {
                    $workClock = $workClocks->where('day', $date->dayName)->first();
                    $timeStart = $workClock->time_start->setDate($date->year, $date->month, $date->day);
                    $timeEnd = $timeStart->copy()->addHours($workClock->duration);

                    // Head
                    if (Carbon::create($ticket->work_start)->toDateString() == $date->toDateString()) {
                        return $timeEnd->diffInSeconds($ticket->work_start);
                    }
                    // Tail
                    elseif (Carbon::create($ticket->work_end)->toDateString() == $date->toDateString()) {
                        return $timeStart->diffInSeconds($ticket->work_end);
                    }
                    // Body
                    else {
                        return $workClock->duration * 3600;
                    }
                });

                try {
                    $result = $ticket->update([
                        'work_duration' => $workingHours->sum()
                    ]);
                } catch (\Throwable $th) {
                    throw $th;
                }
            }
            else {
                $workingHours = Carbon::create($ticket->work_end)->diffInSeconds(
                    Carbon::create($ticket->work_start)
                );

                try {
                    $result = $ticket->update([
                        'work_duration' => $workingHours
                    ]);
                } catch (\Throwable $th) {
                    throw $th;
                }
            }
        }
        return $result;
    }
}
