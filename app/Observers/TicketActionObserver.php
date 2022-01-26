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
         * Check and fill word_start column
         */
        if ($ticket->status_id == 3 && empty($ticket->work_start)) {
            Ticket::withoutEvents(function () use ($ticket) {
                $ticket->work_start = now();
                $ticket->save();
            });
        }


        /**
         * Check and fill work_end column
         */
        if ($ticket->status_id == 5) {
            if (empty($ticket->work_start) && empty($ticket->work_end)) {
                Ticket::withoutEvents(function () use ($ticket) {
                    $ticket->update([
                        'work_start' => $ticket->created_at,
                        'work_end' => now(),
                    ]);
                    TicketHelper::calculateWorkDuration(collect([$ticket]));
                });
            }
        }



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
                    $ticket->work_start = now();
                    $ticket->save();
                });
            }
        }


        /**
         * Check and fill work_end column
         */
        if ($ticket->status_id == 5) {
            if ($ticket->getOriginal('status_id', 1) < 5) {

                /**
                 * Normal Condition
                 * From Working to Close
                 */
                if (!empty($ticket->work_start) && empty($ticket->work_end)) {
                    Ticket::withoutEvents(function () use ($ticket) {
                        $ticket->update([
                            'work_end' => now(),
                        ]);
                        TicketHelper::calculateWorkDuration(collect([$ticket]));
                    });
                }

                /**
                 * Abnormal Condition 1
                 * From Open to Close, etc..
                 */
                elseif (empty($ticket->work_start) && empty($ticket->work_end)) {
                    Ticket::withoutEvents(function () use ($ticket) {
                        $ticket->update([
                            'work_start' => $ticket->created_at,
                            'work_end' => now(),
                        ]);
                        TicketHelper::calculateWorkDuration(collect([$ticket]));
                    });
                }
            }
        }

        /**
         * When work start or work end has been updated
         */
        if ($ticket->work_start != $ticket->getOriginal('work_start') || $ticket->work_end != $ticket->getOriginal('work_end')) {
            Ticket::withoutEvents(function () use ($ticket) {
                TicketHelper::calculateWorkDuration(collect([$ticket]));
            });
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
