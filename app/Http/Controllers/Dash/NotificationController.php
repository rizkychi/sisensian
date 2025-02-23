<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class NotificationController extends Controller
{
    private $slug = 'notification';

    /**
     * Display title
     */
    function __construct()
    {
        return view()->share('title', 'Pemberitahuan');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notifications = Notification::with('from')->where('to_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return view('dash.notification', compact('notifications'));
    }

    /**
     * Read notification
     */
    public function read($id)
    {
        $notification = Notification::find($id);
        if ($notification && $notification->to_id == Auth::id()) {
            $notification->readNotification($id);
            return redirect($notification->url ?? '/index');
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    /**
     * Read all notification
     */
    public function readAll()
    {
        $notification = new Notification();
        $notification->readAllNotification();
        return redirect()->route('notification.index');
    }
}
