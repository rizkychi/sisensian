<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    

    /**
     * Change status to read
     */
    

    /**
     * Insert new notification.
     */
    
}
