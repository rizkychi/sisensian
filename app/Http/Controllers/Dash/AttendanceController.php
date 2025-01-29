<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\Office;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Str;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $today = Carbon::now();
        $employee = auth()->user()->employee;
        $office = Office::where('id', $employee->office_id)->first();

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

        // check attendance status
        $attendance = Attendance::where('employee_id', $employee->id)
            ->where('date', $today->format('Y-m-d'))
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
        
        $schedule = null;
        // check schedule (regular)
        $regular = Schedule::where('employee_id', $employee->id)
            ->where('is_recurring', true)
            ->first();
        if ($regular) {
            $schedule = $regular->where('day_of_week', Str::lower($today->format('l')))->first();
        } else {
            // check schedule (shift)
            $shift = Schedule::where('employee_id', $employee->id)
                ->where('is_recurring', false)
                ->where('date', $today->format('Y-m-d'))
                ->first();
            if ($shift) {
                $schedule = $shift;
            }
        }
        
        $label = (object) [
            'text' => 'Tidak ada jadwal',
            'color' => 'danger',
            'type' => null,
            'is_visible' => false
        ];

        if ($schedule) {
            $now_time = $today;
            $time_in = $schedule->shift->time_in;
            $time_out = $schedule->shift->time_out;

            // testing purpose
            // $now_time = Carbon::now()->setHours(17);
            // $time_in = '08:00';
            // $time_out = '18:00';

            $time_in_time = Carbon::createFromFormat('H:i', $time_in);
            $time_out_time = Carbon::createFromFormat('H:i', $time_out);

            if ($time_in_time->gt($time_out_time)) {
                $time_out_time->addDay();   
            }

            $diff_in = abs($now_time->diffInSeconds($time_in_time));
            $diff_out = abs($now_time->diffInSeconds($time_out_time));

            if ($now_time->between($time_in_time->subHours(2), $time_in_time) || ($now_time->between($time_in_time, $time_out_time) && $diff_in < $diff_out)) {
                $label->text = 'Berangkat';
                $label->color = 'success';
                $label->type = 'in';
                $label->is_visible = $attendance && $attendance->check_in_time ? false : true;
            } elseif ($now_time->between($time_out_time, $time_out_time->addHours(6)) || ($now_time->between($time_in_time, $time_out_time) && $diff_in >= $diff_out)) {
                $label->text = 'Pulang';
                $label->color = 'success';
                $label->type = 'out';
                $label->is_visible = $attendance && $attendance->check_out_time ? false : true;
            } else {
                $label->text = 'Di luar waktu presensi';
                $label->color = 'danger';
            }

            // on leave
            if ($attendance && $attendance->is_on_leave) {
                $label->text = 'Cuti';
                $label->color = 'warning';
                $label->is_visible = false;
            }
        }

        // check holiday
        $holiday = Holiday::where('date', $today->format('Y-m-d'))->first();
        // if today is holiday and day off and schedule is regular
        if ($holiday && $holiday->is_day_off && @$schedule->is_recurring) {
            $label->text = 'Libur';
            $label->color = 'danger';
            $label->is_visible = false;
        }

        return view('dash.attendance.index', compact(
            'today',
            'login',
            'logout',
            'employee',
            'office',
            'schedule',
            'label',
            'attendance'
        ));
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

        $employee = auth()->user()->employee;
        $schedule = Schedule::where('id', $request->schedule_id)->first();

        try {
            $attendance = Attendance::where('employee_id', $employee->id)
                ->where('date', Carbon::now()->format('Y-m-d'))
                ->first();

            if (!$attendance) {
                $attendance = new Attendance();
            }

            $attendance->employee_id = $employee->id;
            $attendance->office_id = $employee->office_id;
            $attendance->schedule_id = $schedule->id;
            $attendance->date = Carbon::now()->format('Y-m-d');
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
        $employee = auth()->user()->employee;
        $attendances = Attendance::where('employee_id', $employee->id)
            ->orderBy('date', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'status' => $attendances->count() > 0 ? 'success' : 'not found',
            'data' => $attendances
        ]);
    }
}
