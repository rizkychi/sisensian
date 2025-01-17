<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use Illuminate\Http\Request;
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
        $pending = Leave::where('status', 'pending')->count();
        return view('dash.leave.index', compact('pending'));
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

        if ($request->status == 'approved') {
            $data->approved_at = now();
        }

        $data->approved_by = auth()->user()->id;

        $data->save();

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
