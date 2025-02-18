<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\Office;
use App\Models\Employee;
use App\Models\LeaveType;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ReportController extends Controller
{
    private $slug = 'report';
    private $months = [
        '01' => 'Januari',
        '02' => 'Februari',
        '03' => 'Maret',
        '04' => 'April',
        '05' => 'Mei',
        '06' => 'Juni',
        '07' => 'Juli',
        '08' => 'Agustus',
        '09' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember',
    ];
    
    /**
     * Display title
     */
    function __construct()
    {
        view()->share('title', 'Laporan');
        view()->share('months', $this->months);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dash.report.index');
    }

    /**
     * Display a listing of the request.
     */
    public function summary()
    {
        return view('dash.report.summary');
    }

    /**
     * Display a listing of the request.
     */
    public function attendance()
    {
        return view("dash.$this->slug.attendance");
    }

    /**
     * Display a listing of the request.
     */
    public function leave()
    {
        $office = Office::where('is_active', true)->get();
        $show = false;
        $leaves = [];
        $employees = [];
        $leave_type = LeaveType::getAll();

        if (request()->query('office_id') && request()->query('date_period')) {
            $show = true;

            $office_id = request()->query('office_id');
            $date_period = request()->query('date_period');
            list($year, $month) = explode('-', $date_period);

            $leaves = Leave::whereHas('employee', function ($query) use ($office_id) {
                $query->where('office_id', $office_id);
            })
            ->whereYear('start_date', $year)
            ->whereMonth('start_date', $month)
            ->orWhere(function ($query) use ($year, $month) {
                $query->whereYear('end_date', $year)
                      ->whereMonth('end_date', $month);
            })
            ->get();
            $leaves = $leaves->where('status', 'approved');

            $employees = Employee::where('office_id', $office_id)->where('is_active', true)->get();

            $start_date = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $end_date = Carbon::createFromDate($year, $month, 1)->endOfMonth();
            $date_range = CarbonPeriod::create($start_date, $end_date);
        }

        return view("dash.$this->slug.leave", compact('office', 'leaves', 'employees', 'leave_type'))
            ->with('show', $show)
            ->with('start_date', $start_date ?? null)
            ->with('end_date', $end_date ?? null)
            ->with('date_range', $date_range ?? null);
    }
}
