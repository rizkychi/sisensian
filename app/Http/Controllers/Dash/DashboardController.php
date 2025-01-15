<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display title
     */
    function __construct()
    {
        return view()->share('title', 'Dashboard');
    }

    /**
     * Display a listing of the resource.
     */
    public function index() 
    {
        return view('dash.index');
    }
}
