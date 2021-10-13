<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotifController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->unreadNotifications;
        return view('admin.notif.notifpage', compact('notifications'));
    }
}
