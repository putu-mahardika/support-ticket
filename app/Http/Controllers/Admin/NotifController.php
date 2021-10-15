<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotifController extends Controller
{
    public function index(Request $request)
    {
        $notifications = auth()->user()->notifications()
                                       ->where('created_at', '>', now()->subMonth())
                                       ->when($request->ajax(), function ($query) {
                                           return $query->limit(3);
                                       })
                                       ->get()
                                       ->when(!$request->ajax(), function ($query) {
                                            return $query->groupBy(function ($item, $key) {
                                                return $item->created_at->format('D, d M Y');
                                            });
                                       });
        if ($request->ajax()) {
            return [
                'html' => view('partials.notificationsList', compact('notifications'))->render(),
                'hasUnread' => auth()->user()->unreadNotifications->count() > 0,
                'label' => auth()->user()->unreadNotifications->count() > 3 ? '3+' : auth()->user()->unreadNotifications->count()
            ];
        }
        return view('admin.notif.notifpage', compact('notifications'));
    }

    public function markRead(Request $request)
    {
        if ($request->has('selectAll')) {
            auth()->user()->unreadNotifications->markAsRead();
        }
        else {
            auth()->user()->unreadNotifications
                          ->where('id', $request->id)
                          ->first()
                          ->markAsRead();
        }
        return true;
    }
}
