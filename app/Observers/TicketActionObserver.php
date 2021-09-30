<?php

namespace App\Observers;

use App\Notifications\DataChangeEmailNotification;
use App\Notifications\AssignedTicketNotification;
use App\Ticket;
use Illuminate\Support\Facades\Notification;

class TicketActionObserver
{
    public function created(Ticket $model)
    {
        $data  = ['action' => 'New ticket has been created!', 'model_name' => 'Ticket', 'ticket' => $model];
        $users = $model->project->user()
                       ->whereDoesntHave('roles', function ($q) {
                           return $q->where('title', 'client');
                        })->get();
        try {
            Notification::send($users, new DataChangeEmailNotification($data));
        } catch (\Exception $e) {

        }
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
}
