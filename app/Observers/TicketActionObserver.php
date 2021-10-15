<?php

namespace App\Observers;

use App\User;
use App\Ticket;
use App\Helpers\MqttHelper;
use App\Notifications\TicketNotification;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AssignedTicketNotification;
use App\Notifications\DataChangeEmailNotification;

class TicketActionObserver
{
    public function created(Ticket $ticket)
    {
        $this->sendDatabaseNotification($ticket);

        // dd($model);
        // $data  = ['action' => 'New ticket has been created!', 'model_name' => 'Ticket', 'ticket' => $model];
        // $users = $model->project->users()
        //                ->whereDoesntHave('roles', function ($q) {
        //                    return $q->where('title', 'client');
        //                 })->get();

        // $users_admin = \App\User::whereHas('roles', function ($q) {
        //     return $q->where('title', 'Admin');
        // })->get();
        // try {
        //     Notification::send($users, new DataChangeEmailNotification($data));
        //     Notification::send($users_admin, new DataChangeEmailNotification($data));
        // } catch (\Exception $e) {

        // }
    }

    public function updated(Ticket $model)
    {
        // if($model->isDirty('assigned_to_user_id'))
        // {
        //     $user = $model->assigned_to_user;
        //     if($user)
        //     {
        //         Notification::send($user, new AssignedTicketNotification($model));
        //     }
        // }
    }

    private function sendEmailNotification()
    {
        # code...
    }

    private function sendDatabaseNotification($ticket)
    {
        $admins = User::whereHas('roles', function ($query) {
            $query->where('id', 1);
        })->get();
        $users = $ticket->project->users->merge($admins);
        $users = $users->map(function ($user) {
                            return $user->id != auth()->id() ? $user : null;
                       })->filter();

        Notification::send($users, new TicketNotification($ticket));
        foreach ($users as $user) {
            $user->refresh();
            $topic = '/mchelpdesk/' . md5($user->email) . '/tickets';
            $message = $user->notifications->first()->data;
            MqttHelper::publish($topic, json_encode($message));
        }
    }
}
