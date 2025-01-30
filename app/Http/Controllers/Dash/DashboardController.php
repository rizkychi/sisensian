<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Office;
use App\Models\Shift;
use App\Models\Leave;
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
        $c_employee = Employee::count();
        $c_office = Office::count();
        $c_shift = Shift::count();
        $c_leave = Leave::count();
        $c_attendance = Attendance::whereYear('date', date('Y'))
                      ->whereMonth('date', date('m'))
                      ->count();

        return view('dash.index', compact(
            'c_employee',
            'c_office',
            'c_shift',
            'c_leave',
            'c_attendance'
        ));
    }
}
