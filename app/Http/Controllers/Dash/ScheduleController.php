<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Office;
use App\Models\Shift;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    private $slug = 'schedule';

    protected $days = [
        'monday' => 'Senin',
        'tuesday' => 'Selasa',
        'wednesday' => 'Rabu',
        'thursday' => 'Kamis',
        'friday' => 'Jumat',
        'saturday' => 'Sabtu',
        'sunday' => 'Minggu'
    ];

    /**
     * Display title
     */
    function __construct()
    {
        view()->share('title', 'Penjadwalan');
        view()->share('days', $this->days);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("dash.$this->slug.index");
    }

    /**
     * Display a listing of the resource.
     */
    public function regular(Request $request)
    {
        $office = Office::where('is_active', true)->get();
        $employee = Employee::where('office_id', $request->office_id)->get();
        return view("dash.$this->slug.regular", compact('office', 'employee'));
    }

    /**
     * Display a listing of the resource.
     */
    public function shift(Request $request)
    {
        $office = Office::where('is_active', true)->get();
        $employee = Employee::where('office_id', $request->office_id)->get();
        return view("dash.$this->slug.regular", compact('office', 'employee'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function regularCreate()
    {
        $route_name = route("regular.store");
        $route_label = 'Tambah';
        $office = Office::where('is_active', true)->get();
        $shift = Shift::where('is_fixed', true)->get();
        return view("dash.$this->slug.regular-form", compact('route_name', 'route_label', 'office', 'shift'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function regularStore(Request $request)
    {
        $office = Office::findOrfail($request->office_id);

        $request->validate([
            'office_id' => 'required|string',
            'employee_id' => 'nullable|string',
        ]);

        try {
            \DB::transaction(function () use ($request) {
                if ($request->employee_id) {
                    // Schedule for one employee
                    foreach ($this->days as $d => $day) {
                        // Check if the schedule already exists
                        $schedule = Schedule::where('employee_id', $request->employee_id)
                            ->where('day_of_week', $d)
                            ->where('is_recurring', true)
                            ->first();
                        
                        if ($schedule && @$request->shift_id[$d] != null) {
                            // Update existing schedule
                            $schedule->update([
                                'shift_id' => $request->shift_id[$d],
                            ]);
                        } else if ($schedule && @$request->shift_id[$d] == null) {
                            // Delete existing schedule
                            $schedule->delete();
                        } else if (@$request->shift_id[$d] != null) {
                            // Create new schedule
                            Schedule::create([
                                'employee_id' => $request->employee_id,
                                'shift_id' => $request->shift_id[$d],
                                'day_of_week' => $d,
                                'is_recurring' => true,
                            ]);
                        }
                    }
                } else {
                    // Schedule for all employees in the office
                    $employees = Employee::where('office_id', $request->office_id)->get();
                    foreach ($employees as $employee) {
                        foreach ($this->days as $d => $day) {
                            // Check if the schedule already exists
                            $schedule = Schedule::where('employee_id', $employee->id)
                                ->where('day_of_week', $d)
                                ->where('is_recurring', true)
                                ->first();
                            
                            if ($schedule && @$request->shift_id[$d] != null) {
                                // Update existing schedule
                                $schedule->update([
                                    'shift_id' => $request->shift_id[$d],
                                ]);
                            } else if ($schedule && @$request->shift_id[$d] == null) {
                                // Delete existing schedule
                                $schedule->delete();
                            } else if (@$request->shift_id[$d] != null) {
                                // Create new schedule
                                Schedule::create([
                                    'employee_id' => $employee->id,
                                    'shift_id' => $request->shift_id[$d],
                                    'day_of_week' => $d,
                                    'is_recurring' => true,
                                ]);
                            }
                        }
                    }
                }
            });
            
            return redirect()->route('regular', ['office_id' => $office->id])->with('success', 'Jadwal berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menyimpan jadwal: ' . $e->getMessage())->withInput();
        }
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
    public function regularEdit(string $id)
    {
        $route_name = route("regular.update", $id);
        $route_label = 'Ubah';
        $office = Office::where('is_active', true)->get();
        $shift = Shift::where('is_fixed', true)->get();
        $data = Employee::findOrFail($id);
        return view("dash.$this->slug.regular-form", compact('route_name', 'route_label', 'office', 'shift', 'data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function regularUpdate(Request $request, string $id)
    {
        $employee = Employee::findOrFail($id);

        try {
            \DB::transaction(function () use ($request, $employee) {
                foreach ($this->days as $d => $day) {
                    // Check if the schedule already exists
                    $schedule = Schedule::where('employee_id', $employee->id)
                        ->where('day_of_week', $d)
                        ->where('is_recurring', true)
                        ->first();
                    
                    if ($schedule && @$request->shift_id[$d] != null) {
                        // Update existing schedule
                        $schedule->update([
                            'shift_id' => $request->shift_id[$d],
                        ]);
                    } else if ($schedule && @$request->shift_id[$d] == null) {
                        // Delete existing schedule
                        $schedule->delete();
                    } else if (@$request->shift_id[$d] != null) {
                        // Create new schedule
                        Schedule::create([
                            'employee_id' => $employee->id,
                            'shift_id' => $request->shift_id[$d],
                            'day_of_week' => $d,
                            'is_recurring' => true,
                        ]);
                    }
                }
            });
            
            return redirect()->route('regular', ['office_id' => $employee->office->id])->with('success', 'Jadwal berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menyimpan jadwal: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
