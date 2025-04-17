<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\Office;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Str;

class AttendanceController extends Controller
{
    protected $tolerance_in = 2; // hours
    protected $tolerance_out = 6; // hours

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $today = Carbon::now();
        $employee = Auth::user()->employee;
        $office = Office::where('id', $employee->office_id)->first();
        $date_in = $date_out = null;

        // testing purpose
        // $today = Carbon::now()->setHours(14)->setMinutes(1);

        $login = (object) [
            'text' => 'Belum melakukan presensi',
            'text_color' => 'text-muted text-opacity-75',
            'icon' => 'la-hourglass-half'
        ];
        $logout = (object) [
            'text' => 'Belum melakukan presensi',
            'text_color' => 'text-muted text-opacity-75',
            'icon' => 'la-hourglass-half'
        ];

        $label = (object) [
            'text' => 'Tidak ada jadwal',
            'color' => 'danger',
            'type' => null,
            'is_visible' => false
        ];
        
        // check schedule
        $schedule = null;
        if ($employee->category == 'regular') {
            // schedule (regular)
            $schedule = Schedule::where('employee_id', $employee->id)
                ->where('is_recurring', true)
                ->where('day_of_week', Str::lower($today->format('l')))
                ->first();
        
            $date_in = $date_out = $today->translatedFormat('l, d F Y');
        } else {
            // schedule (shift)
            // check yesterday first
            $yesterday = $today->copy()->subDay();
            $yesterday_schedule = Schedule::where('employee_id', $employee->id)
                ->where('is_recurring', false)
                ->where('date', $yesterday->format('Y-m-d'))
                ->first();

            // check if yesterday schedule is not null and shift is next day and time out is less than now 
            // then use yesterday schedule
            if ($yesterday_schedule
            && $yesterday_schedule->shift->is_next_day
            && abs($today->diffInHours(Carbon::createFromFormat('Y-m-d H:i', $yesterday->copy()->addDay()->format('Y-m-d') . ' ' . $yesterday_schedule->shift->time_out)->format('Y-m-d H:i'))) <= $this->tolerance_out) {
                $schedule = $yesterday_schedule;
                $date_in = $yesterday->translatedFormat('l, d F Y');
                $date_out = $today->translatedFormat('l, d F Y');
            } else {
                // if not, use today schedule
                $schedule = Schedule::where('employee_id', $employee->id)
                    ->where('is_recurring', false)
                    ->where('date', $today->format('Y-m-d'))
                    ->first();
                
                if ($schedule) {
                    $date_in = $date_out = $today->translatedFormat('l, d F Y');
                }

                if (@$schedule->shift->is_next_day) {
                    $date_out = $today->copy()->addDay()->translatedFormat('l, d F Y');
                }
            }
        }

        if ($schedule) {
            // check attendance status
            $attendance = Attendance::where('employee_id', $employee->id)
                ->where('date', $schedule->date)
                ->first();

            if ($attendance) {
                if ($attendance->check_in_time) {
                    $login->text = 'Berhasil presensi pada <b>'.$attendance->check_in_time.'</b>';
                    $login->text_color = 'text-success';
                    $login->icon = 'ri-check-line';
                }
                if ($attendance->check_out_time) {
                    $logout->text = 'Berhasil presensi pada <b>'.$attendance->check_out_time.'</b>';
                    $logout->text_color = 'text-success';
                    $logout->icon = 'ri-check-line';
                }
            }

            // on leave
            if ($attendance && $attendance->is_on_leave) {
                $label->text = 'Cuti';
                $label->color = 'warning';
                $label->is_visible = false;
            } else {
                // check time
                $now_time = $today->copy();
                $time_in = $schedule->shift->time_in;
                $time_out = $schedule->shift->time_out;

                $time_in_time = Carbon::createFromFormat('Y-m-d H:i', $schedule->date . ' ' . $time_in);
                $time_out_time = Carbon::createFromFormat('Y-m-d H:i', $schedule->date . ' ' . $time_out);

                if ($time_in_time->gt($time_out_time)) {
                    $time_out_time->addDay();
                }

                $diff_in = abs($now_time->diffInSeconds($time_in_time));
                $diff_out = abs($now_time->diffInSeconds($time_out_time));

                if ($now_time->between($time_in_time->copy()->subHours($this->tolerance_in), $time_in_time) || ($now_time->between($time_in_time, $time_out_time) && $diff_in < $diff_out)) {
                    $label->text = 'Berangkat';
                    $label->color = 'success';
                    $label->type = 'in';
                    $label->is_visible = $attendance && $attendance->check_in_time ? false : true;
                } elseif ($now_time->between($time_out_time, $time_out_time->copy()->addHours($this->tolerance_out)) || ($now_time->between($time_in_time, $time_out_time) && $diff_in >= $diff_out)) {
                    $label->text = 'Pulang';
                    $label->color = 'success';
                    $label->type = 'out';
                    $label->is_visible = $attendance && $attendance->check_out_time ? false : true;
                } else {
                    $label->text = 'Di luar waktu presensi';
                    $label->color = 'danger';
                }
            }
        }

        // check holiday
        $holiday = Holiday::where('date', $today->format('Y-m-d'))->first();
        // if today is holiday and day off
        if ($holiday && $holiday->is_day_off && $employee->category == 'regular' && $schedule) {
            $label->text = 'Libur';
            $label->color = 'danger';
            $label->is_visible = false;
        }

        return view('dash.attendance.index', compact(
            'today',
            'date_in',
            'date_out',
            'login',
            'logout',
            'employee',
            'office',
            'schedule',
            'label',
        ))->with('attendance', @$attendance);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validate
        $request->validate([
            'att_type' => 'required|in:in,out',
            'att_lat' => 'required|string',
            'att_long' => 'required|string',
            'att_address' => 'required|string',
            'schedule_id' => 'required|string|exists:schedule,id',
        ]);

        $employee = Auth::user()->employee;
        $schedule = Schedule::where('id', $request->schedule_id)->first();

        try {
            $attendance = Attendance::where('employee_id', $employee->id)
                ->where('date', $schedule->date)
                ->first();

            if (!$attendance) {
                $attendance = new Attendance();
            }

            $attendance->employee_id = $employee->id;
            $attendance->office_id = $employee->office_id;
            $attendance->schedule_id = $schedule->id;
            $attendance->date = $schedule->date;
            $attendance->time_in = $schedule->shift->time_in;
            $attendance->time_out = $schedule->shift->time_out;
            
            if ($request->att_type == 'in') {
                $attendance->check_in_time = Carbon::now()->format('H:i:s');
                $attendance->check_in_lat = $request->att_lat;
                $attendance->check_in_long = $request->att_long;
                $attendance->check_in_address = $request->att_address;
            } else if ($request->att_type == 'out') {
                $attendance->check_out_time = Carbon::now()->format('H:i:s');
                $attendance->check_out_lat = $request->att_lat;
                $attendance->check_out_long = $request->att_long;
                $attendance->check_out_address = $request->att_address;
            }
            
            $attendance->save();

            return redirect()->route('attendance.index')->with('success', 'Berhasil presensi');
        } catch (\Exception $e) {
            return redirect()->route('attendance.index')->with('error', 'Terjadi kesalahan saat presensi: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function history()
    {
        $employee = Auth::user()->employee;
        $attendances = Attendance::with('schedule.shift')
            ->where('employee_id', $employee->id)
            ->where('is_on_leave', false)
            ->orderBy('date', 'desc')
            ->limit(7)
            ->get();

        return response()->json([
            'status' => $attendances->count() > 0 ? 'success' : 'not found',
            'data' => $attendances
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function schedule()
    {
        $employee = Auth::user()->employee;

        $schedules = [];
        if ($employee->category == 'regular') {
            foreach (Carbon::now()->addDays(1)->daysUntil(Carbon::now()->addDays(7)) as $date) {
                $schedules[$date->format('Y-m-d')] = Schedule::with('shift')->where('employee_id', $employee->id)
                    ->where('is_recurring', true)
                    ->where('day_of_week', Str::lower($date->format('l')))
                    ->first();
            }
        }
        if ($employee->category == 'shift') {
            foreach (Carbon::now()->addDays(1)->daysUntil(Carbon::now()->addDays(7)) as $date) {
                $schedules[$date->format('Y-m-d')] = Schedule::with('shift')->where('employee_id', $employee->id)
                    ->where('is_recurring', false)
                    ->where('date', $date->format('Y-m-d'))
                    ->first();
            }
        }

        // check holiday
        $holidays = Holiday::where('date', '>=', Carbon::now()->addDays(1)->format('Y-m-d'))
        ->where('date', '<=', Carbon::now()->addDays(7)->format('Y-m-d'))
        ->where('is_day_off', true)
        ->get();
        
        foreach ($holidays as $holiday) {
            if ($holiday->is_day_off && $employee->category == 'regular') {
                $schedules[$holiday->date] = $holiday;
            }
        }

        // check leave
        $leaves = Attendance::where('employee_id', $employee->id)
            ->where('date', '>=', Carbon::now()->addDays(1)->format('Y-m-d'))
            ->where('date', '<=', Carbon::now()->addDays(7)->format('Y-m-d'))
            ->where('is_on_leave', true)
            ->get();
        foreach ($leaves as $leave) {
            $schedules[$leave->date] = $leave;
        }

        // refomat data
        $output = [];
        foreach ($schedules as $key => $schedule) {
            if ($schedule == null || $schedule->is_day_off) {
                $output[] = (object) [
                    'date' => $key,
                    'date_formatted' => Carbon::parse($key)->translatedFormat('l, d F Y'),
                    'date_next_formatted' => Carbon::parse($key)->addDay()->translatedFormat('d-m-Y'),
                    'is_next_day' => $schedule->shift->is_next_day ?? false,
                    'title' => 'Tidak ada jadwal (Libur)',
                    'time_in' => '--:--',
                    'time_out' => '--:--',
                    'shift_type' => 'Libur',
                    'color' => 'primary-subtle text-primary'
                ];
            } else if ($schedule && $schedule->is_on_leave) {
                $output[] = (object) [
                    'date' => $key,
                    'date_formatted' => Carbon::parse($key)->translatedFormat('l, d F Y'),
                    'date_next_formatted' => Carbon::parse($key)->addDay()->translatedFormat('d-m-Y'),
                    'is_next_day' => $schedule->shift->is_next_day ?? false,
                    'title' => 'Cuti',
                    'time_in' => '--:--',
                    'time_out' => '--:--',
                    'shift_type' => 'Cuti',
                    'color' => 'warning'
                ];
            } else if ($schedule) {
                $output[] = (object) [
                    'date' => $key,
                    'date_formatted' => Carbon::parse($key)->translatedFormat('l, d F Y'),
                    'date_next_formatted' => Carbon::parse($key)->addDay()->translatedFormat('d-m-Y'),
                    'is_next_day' => $schedule->shift->is_next_day ?? false,
                    'title' => $schedule->shift->name,
                    'time_in' => $schedule->shift->time_in,
                    'time_out' => $schedule->shift->time_out,
                    'shift_type' => $schedule->is_recurring ? 'Reguler' : 'Shift',
                    'color' => 'primary'
                ];
            }
        }

        return response()->json([
            'status' => count($output) > 0 ? 'success' : 'not found',
            'data' => $output
        ]);
    }
}
