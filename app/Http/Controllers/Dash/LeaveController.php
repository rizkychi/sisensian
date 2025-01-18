<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

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
            'start_date' => 'required|string',
            'end_date' => 'required|string',
            'reason' => 'nullable|string',
        ]);

        $data = new Leave();
        $data->employee_id = Auth::user()->employee->id;
        $data->leave_type = $request->leave_type;
        $data->start_date = $request->start_date;
        $data->end_date = $request->end_date;
        $data->reason = $request->reason;
        $data->status = 'pending';
        
        if ($data->save()) {
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
        // Json Request
        $data = Leave::findOrFail($id);
        return view('dash.leave.details', compact('data'));
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
        $data = Leave::findOrFail($id);
        $data->status = $request->status;
        $data->note = $request->note;
        $data->confirmed_at = now();
        $data->confirmed_by = Auth::id();
        
        if ($data->save()) {
            return redirect()->route("$this->slug.index")->with('success', 'Data berhasil diupdate.');
        } else {
            return back()->with('error', 'Data gagal diupdate')->withInput();
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
