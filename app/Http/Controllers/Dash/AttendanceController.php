<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
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
        $login = (object) [
            'text_color' => 'text-muted text-opacity-75',
            'icon' => 'ri-check-line'
        ];
        $logout = (object) [
            'text_color' => 'text-muted text-opacity-75',
            'icon' => 'la-hourglass-half'
        ];
        
        $employee = auth()->user()->employee;
        $office = Office::where('id', $employee->office_id)->first();
        
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
            'is_visible' => false
        ];

        if ($schedule) {
            $now_time = $today;
            $time_in = $schedule->shift->time_in;
            $time_out = $schedule->shift->time_out;

            // testing purpose
            // $now_time = Carbon::now()->setHours(22);
            // $time_in = '08:00';
            // $time_out = '17:00';

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
                $label->is_visible = true;
            } elseif ($now_time->between($time_out_time, $time_out_time->addHours(6)) || ($now_time->between($time_in_time, $time_out_time) && $diff_in >= $diff_out)) {
                $label->text = 'Pulang';
                $label->color = 'success';
                $label->is_visible = true;
            } else {
                $label->text = 'Di luar waktu presensi';
                $label->color = 'danger';
            }
        }

        return view('dash.attendance.index', compact('today', 'login', 'logout', 'employee', 'office', 'schedule', 'label'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
