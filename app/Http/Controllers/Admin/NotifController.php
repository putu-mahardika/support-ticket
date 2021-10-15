<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotifController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()
                                       ->where('created_at', '>', now()->subMonth())
                                       ->get()
                                       ->groupBy(function ($item, $key) {
                                           return $item->created_at->format('D, d M Y');
                                       });
        return view('admin.notif.notifpage', compact('notifications'));
    }
}
