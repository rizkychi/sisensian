<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\Notification;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Str;

class LeaveController extends Controller
{
    private $slug = 'leave';

    /**
     * Display title
     */
    function __construct()
    {
        return view()->share('title', 'Pengajuan Cuti');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->role != 'superadmin') {
            return redirect()->route("$this->slug.request");
        }
        $pending = Leave::where('status', 'pending')->count();
        return view('dash.leave.index', compact('pending'));
    }

    /**
     * Display a listing of the request.
     */
    public function request()
    {
        if (Auth::user()->role == 'superadmin') {
            return redirect()->route("$this->slug.index");
        }
        $data = Leave::where('employee_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return view('dash.leave.request', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $route_name = route("$this->slug.store");
        $route_label = 'Tambah';
        $data = Auth::user()->employee;
        $leave_types = LeaveType::getAll();
        return view("dash.$this->slug.form", compact('route_name', 'route_label', 'data', 'leave_types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Auth::user()->role == 'superadmin') {
            return redirect()->route("$this->slug.index");
        }
        $request->validate([
            'leave_type' => 'required|string',
            // 'start_date' => 'required|string',
            // 'end_date' => 'required|string',
            'leave_date' => 'required|string',
            'reason' => 'nullable|string',
        ]);

        if (strpos($request->leave_date, ' to ') !== false) {
            list($start_date, $end_date) = explode(' to ', $request->leave_date);
        } else {
            $start_date = $end_date = $request->leave_date;
        }

        $data = new Leave();
        $data->employee_id = Auth::user()->employee->id;
        $data->leave_type = $request->leave_type;
        $data->start_date = $start_date;
        $data->end_date = $end_date;
        $data->reason = $request->reason;
        $data->status = 'pending';
        
        if ($data->save()) {
            Notification::insertNotification(
                'admin',
                'Pengajuan Cuti',
                'Mengajukan '.LeaveType::getName($data->leave_type).' pada '.$start_date.' s/d '.$end_date,
                route("$this->slug.show", $data->id)
            );

            return redirect()->route("$this->slug.request")->with('success', 'Data berhasil disimpan.');
        } else {
            return back()->with('error', 'Data gagal disimpan')->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $route_label = 'Detail';
        $data = Leave::findOrFail($id);
        return view('dash.leave.details', compact('data', 'route_label'));
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
        if (Auth::user()->role != 'superadmin') {
            return redirect()->route("$this->slug.index");
        }
        
        $data = Leave::findOrFail($id);
        
        if ($request->status == null) {
            return back()->with('error', 'Status harus diisi.')->withInput();
        }

        try {
            \DB::transaction(function () use ($request, $data) {
                $data->status = $request->status;
                $data->note = $request->note;
                $data->confirmed_at = now();
                $data->confirmed_by = Auth::id();
                $data->save();
                
                if ($request->status == 'approved') {
                    // save to attendance
                    $start_date = \Carbon\Carbon::parse($data->start_date);
                    $end_date = \Carbon\Carbon::parse($data->end_date);
                    $period = \Carbon\CarbonPeriod::create($start_date, $end_date);

                    $schedule = Schedule::where('employee_id', $data->employee_id)->get();
                    foreach ($period as $date) {
                        if ($data->employee->category == 'shift') {
                            $schedule = $schedule->where('is_recurring', false)->where('date', $date->format('Y-m-d'))->first();
                        } else {
                            $schedule = $schedule->where('is_recurring', true)->where('day_of_week', Str::lower($date->format('l')))->first();
                        }
                        
                        $attendace = Attendance::updateOrCreate([
                            'employee_id' => $data->employee_id,
                            'date' => $date->format('Y-m-d'),
                        ], [
                            'schedule_id' => @$schedule->id ?? null,
                            'office_id' => @$data->employee->office_id,
                            'is_on_leave' => true,
                            'time_in' => (@$schedule->shift->time_in ?? '00:00'),
                            'time_out' => (@$schedule->shift->time_out ?? '00:00'),
                        ]);
                    }

                    $stat = 'Disetujui';
                    $stat_type = 'success';
                } else {
                    $stat = 'Ditolak';
                    $stat_type = 'danger';
                }


                Notification::insertNotification(
                    $data->employee->user->id,
                    'Pengajuan Cuti',
                    'Pengajuan '.LeaveType::getName($data->leave_type).' pada '.$data->start_date.' s/d '.$data->end_date.' telah '.$stat,
                    route("$this->slug.show", $data->id),
                    $stat_type
                );
            });
            return redirect()->route("$this->slug.index")->with('success', 'Data berhasil diupdate.');
        } catch (\Exception $e) {
            return back()->with('error', 'Data gagal diupdate: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Get data for datatable
     */
    public function json(Request $request)
    {
        if ($request->ajax()) {
            $query = Leave::query();
            if (Auth::user()->role != 'superadmin') {
                $query->where('employee_id', Auth::user()->employee->id);
            }
            if (isset($request->status) && $request->status != '') {
                $query->where('status', $request->status);
            }
            $data = $query->orderBy('created_at', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function($row){
                    return $row->employee->name;
                })
                ->make(true);
        }
    }
}
