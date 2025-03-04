<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceType;
use App\Models\Office;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\LeaveType;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Str;

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
        $route_label = 'Presensi Karyawan';
        $offices = Office::where('is_active', true)->orderBy('name', 'asc')->get();
        $show = false;
        $date_range = [];
        $attendances = [];
        $employees = [];
        $leave_type = LeaveType::getAll();
        $late_type = AttendanceType::getLate();
        $early_type = AttendanceType::getEarly();

        if (request()->query('office_id') && request()->query('date_period')) {
            $show = true;

            $office_id = request()->query('office_id');
            $date_period = request()->query('date_period');
            list($year, $month) = explode('-', $date_period);

            $start_date = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $end_date = Carbon::createFromDate($year, $month, 1)->endOfMonth();
            $date_range = CarbonPeriod::create($start_date, $end_date);

            // Get all employees
            if ($office_id == 'all') {
                $employees = Employee::where('is_active', true)
                    ->orderBy(Office::select('name')->whereColumn('office.id', 'employee.office_id'))
                    ->orderBy('name', 'asc')
                    ->get();
            } else {
                $employees = Employee::where('office_id', $office_id)->where('is_active', true)->orderBy('name', 'asc')->get();
            }

            $attendances = $this->getAttendance($employees, $date_range);
        }

        return view("dash.$this->slug.attendance", compact('route_label', 'offices', 'date_range', 'attendances', 'employees', 'leave_type', 'late_type', 'early_type'))
            ->with('show', $show);
    }

    /**
     * Display summary of the request.
     */
    public function attendanceSummary()
    {
        $route_label = 'Rekap Presensi';
        $offices = Office::where('is_active', true)->orderBy('name', 'asc')->get();
        $show = false;
        $date_range = [];
        $attendances = [];
        $attendance_summary = [];
        $employees = [];
        $leave_type = LeaveType::getAll();
        $late_type = AttendanceType::getLate();
        $early_type = AttendanceType::getEarly();

        if (request()->query('office_id') && request()->query('date_period')) {
            $show = true;

            $office_id = request()->query('office_id');
            $date_period = request()->query('date_period');
            list($year, $month) = explode('-', $date_period);

            $start_date = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $end_date = Carbon::createFromDate($year, $month, 1)->endOfMonth();
            $date_range = CarbonPeriod::create($start_date, $end_date);

            // Get all employees
            if ($office_id == 'all') {
                $employees = Employee::where('is_active', true)
                    ->orderBy(Office::select('name')->whereColumn('office.id', 'employee.office_id'))
                    ->orderBy('name', 'asc')
                    ->get();
            } else {
                $employees = Employee::where('office_id', $office_id)->where('is_active', true)->orderBy('name', 'asc')->get();
            }

            $attendances = $this->getAttendance($employees, $date_range);

            // Counting attendance summary
            $tag = array_merge(['V', 'TK', 'TPM', 'TPP'], array_keys($late_type), array_keys($early_type), array_keys($leave_type));

            foreach ($attendances as $employee_id => $attend) {
                foreach ($tag as $t) {
                    $attendance_summary[$employee_id][$t] = count(array_filter($attend, function ($a) use ($t) {
                        return strpos($a, $t) !== false;
                    }));  
                }
                $attendance_summary[$employee_id]['L'] = count(array_filter($attend, function ($a) {
                    return $a == 'L' || $a == 'LN';
                }));
            }
        }

        return view("dash.$this->slug.attendance_summary", compact('route_label', 'offices', 'date_range', 'attendance_summary', 'employees', 'leave_type', 'late_type', 'early_type'))
            ->with('show', $show);
    }

    /**
     * Display a listing of the request.
     */
    public function detail()
    {
        $route_label = 'Detail Presensi';
        $offices = Office::where('is_active', true)->orderBy('name', 'asc')->get();
        $show = false;
        $employees = null;
        $attendances = null;
        $date_range = [];

        if (request()->query('office_id') && request()->query('date_period') && request()->query('employee_id')) {
            $employee_id = request()->query('employee_id');
            $office_id = request()->query('office_id');
            $date_period = request()->query('date_period');

            $show = true;
            $employees = Employee::where('office_id', $office_id)->where('is_active', true)->orderBy('name', 'asc')->get();

            list($year, $month) = explode('-', $date_period);
            $start_date = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $end_date = Carbon::createFromDate($year, $month, 1)->endOfMonth();
            $date_range = CarbonPeriod::create($start_date, $end_date);
            $attendances = Attendance::where('employee_id', $employee_id)
                ->whereYear('date', $year)
                ->whereMonth('date', $month)
                ->orderBy('date', 'asc')
                ->get();
        }

        return view("dash.$this->slug.detail", compact('route_label', 'offices', 'employees', 'attendances', 'date_range'))
            ->with('show', $show);
    }

    /**
     * Get employee by office
     */
    public function getEmployee(Request $request)
    {
        $office_id = $request->office_id;
        $employees = Employee::where('office_id', $office_id)->where('is_active', true)->orderBy('name', 'asc')->get();
        return response()->json($employees);
    }

    private function getAttendance($employees, $date_range) {
        $attendances = [];
        $month = $date_range->first()->format('m');
        $year = $date_range->first()->format('Y');

        // Get current month holidays
        $holidays = Holiday::whereYear('date', $year)->whereMonth('date', $month)->get();

        // Get all attendances
        foreach ($employees as $employee) {
            foreach ($date_range as $date) {
                // Check Holiday
                if ($holidays->contains('date', $date->format('Y-m-d'))) {
                    $attendances[$employee->id][$date->format('Y-m-d')] = 'LN'; // Save holiday
                    continue;
                }

                // Check Leave
                $leave = $employee->leave->where('start_date', '<=', $date->format('Y-m-d'))
                    ->where('end_date', '>=', $date->format('Y-m-d'))
                    ->where('status', 'approved')
                    ->first();
                if ($leave) {
                    $attendances[$employee->id][$date->format('Y-m-d')] = $leave->leave_type; // Save leave
                    continue;
                }

                // Check Schedule
                if ($employee->category == 'regular') {
                    $schedule = $employee->schedule->where('day_of_week', Str::lower($date->format('l')))->first();
                    if (!$schedule) {
                        $attendances[$employee->id][$date->format('Y-m-d')] = 'L'; // Save weekend
                        continue;
                    }
                } else if ($employee->category == 'shift') {
                    $schedule = $employee->schedule->where('date', $date->format('Y-m-d'))->first();
                    if (!$schedule) {
                        $attendances[$employee->id][$date->format('Y-m-d')] = 'L'; // Save weekend
                        continue;
                    }
                }

                // Check Attendance
                $att = $employee->attendance->where('date', $date->format('Y-m-d'))->first();
                if ($att) {
                    if (!empty($att->status) && in_array('TPM', $att->status) && in_array('TPP', $att->status)) {
                        $attendances[$employee->id][$date->format('Y-m-d')] = 'TK'; // Save absent
                    } else {
                        $attendances[$employee->id][$date->format('Y-m-d')] = !empty($att->status) ? implode(', ', $att->status) : 'V'; // Save attendance status or attendance
                    }
                } else {
                    $attendances[$employee->id][$date->format('Y-m-d')] = 'TK'; // Save absent
                }
            }
        }

        return $attendances;
    }
}
