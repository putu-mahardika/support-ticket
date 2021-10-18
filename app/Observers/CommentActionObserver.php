<?php

namespace App\Observers;

use App\User;
use App\Comment;
use App\Helpers\MqttHelper;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewCommentNotification;

class CommentActionObserver
{
    public function created(Comment $comment)
    {
        $this->sendDatabaseNotification($comment);
    }

    public function updated(Comment $comment)
    {

    }

    private function sendDatabaseNotification($comment, $mode = 'new')
    {
        $admins = User::whereHas('roles', function ($query) {
            $query->where('id', 1);
        })->get();
        $users = $comment->ticket->project->users->merge($admins);
        $users = $users->map(function ($user) {
                            return $user->id != auth()->id() ? $user : null;
                       })->filter();

        if ($mode == 'new') {
            Notification::send($users, new NewCommentNotification($comment));
        }
        else {

        }

        foreach ($users as $user) {
            $user->refresh();
            $topic = '/mchelpdesk/' . md5($user->email) . '/comments';
            $message = $user->notifications->first()->data;
            MqttHelper::publish($topic, json_encode($message));
        }
    }
}
