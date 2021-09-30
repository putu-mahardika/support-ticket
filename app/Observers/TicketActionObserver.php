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
        // dd($model);
        $data  = ['action' => 'New ticket has been created!', 'model_name' => 'Ticket', 'ticket' => $model];
        $users_admin = \App\User::whereHas('roles', function ($q) {
            return $q->where('title', 'Admin');
        })->get();

        $users_agent = \App\User::whereHas('roles', function ($q) {
            return $q->Where('title', 'Agent');
        })->where('id',$model->assigned_to_user_id)->get();
        // dd($users);
        
        Notification::send($users_admin, new DataChangeEmailNotification($data));
        Notification::send($users_agent, new DataChangeEmailNotification($data));
    }

    public function updated(Ticket $model)
    {
        if($model->isDirty('assigned_to_user_id'))
        {
            $user = $model->assigned_to_user;
            if($user)
            {
                Notification::send($user, new AssignedTicketNotification($model));
            }
        }
    }
}
