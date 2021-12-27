<?php

namespace App\Observers;

use App\User;
use App\Ticket;
use App\Helpers\MqttHelper;
use App\Helpers\TicketHelper;
use App\Notifications\NewTicketNotification;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AssignedTicketNotification;
use App\Notifications\DataChangeEmailNotification;
use App\Notifications\UpdateTicketNotification;

class TicketActionObserver
{
    // public $afterCommit = true;

    public function created(Ticket $ticket)
    {
        /**
         * If create ticket from console, will be ignored
         */
        if (app()->runningInConsole()) return;


        /**
         * Send websocket notification
         */
        $this->sendDatabaseNotification($ticket);


        /**
         * Send mail notification
         */

        // Preparing Data
        $data  = [
            'action' => 'New ticket has been created!',
            'model_name' => 'Ticket',
            'ticket' => $ticket
        ];

        // Get all users (ex. client & admin)
        $users = $ticket->project->users()
                        ->whereDoesntHave('roles', function ($q) {
                           return $q->where('title', 'client');
                        })->get();

        // Get all admin
        $users_admin = \App\User::whereHas('roles', function ($q) {
            return $q->where('title', 'Admin');
        })->get();

        // Send mail
        try {
            Notification::send($users, new DataChangeEmailNotification($data));
            Notification::send($users_admin, new DataChangeEmailNotification($data));
        } catch (\Exception $e) {}
    }

    public function updated(Ticket $ticket)
    {
        /**
         * If create ticket from console, will be ignored
         */
        if (app()->runningInConsole()) return;


        /**
         * Check and fill word_start column
         */
        if ($ticket->status_id == 3 && empty($ticket->work_start)) {
            if ($ticket->getOriginal('status_id', 1) < 3) {
                Ticket::withoutEvents(function () use ($ticket) {
                    Ticket::find($ticket->id)->update([
                        'work_start' => now()
                    ]);
                });
            }
        }


        /**
         * Check and fill word_end column
         */
        if ($ticket->status_id == 5 && !empty($ticket->work_start) && empty($ticket->work_end)) {
            if ($ticket->getOriginal('status_id', 1) < 5) {
                Ticket::withoutEvents(function () use ($ticket) {
                    Ticket::find($ticket->id)->update([
                        'work_end' => now()
                    ]);
                });
            }
        }


        /**
         * Generate working log
         */
        if ($ticket->wasChanged('work_start') || $ticket->wasChanged('work_end')) {
            TicketHelper::generateWorkingLog($ticket->id);
            TicketHelper::calculateWorkDuration(collect([$ticket]));
        }


        /**
         * Send mail notification
         */
        $this->sendDatabaseNotification($ticket, 'edit');
    }

    private function sendEmailNotification()
    {
        # code...
    }

    private function sendDatabaseNotification($ticket, $mode = 'new')
    {
        $admins = User::whereHas('roles', function ($query) {
            $query->where('id', 1);
        })->get();
        $users = $ticket->project->users->merge($admins);
        $users = $users->map(function ($user) {
                            return $user->id != auth()->id() ? $user : null;
                       })->filter();

        if ($mode == 'new') {
            Notification::send($users, new NewTicketNotification($ticket));
        }
        else {
            Notification::send($users, new UpdateTicketNotification($ticket));
        }

        foreach ($users as $user) {
            $user->refresh();
            $topic = '/mchelpdesk/' . md5($user->email) . '/tickets';
            $message = $user->notifications->first()->data;
            MqttHelper::publish($topic, json_encode($message));
        }
    }
}
