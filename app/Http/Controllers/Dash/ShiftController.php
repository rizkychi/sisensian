<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ShiftController extends Controller
{
    /**
     * Display title
     */
    function __construct()
    {
        return view()->share('title', 'Shift');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        confirmDelete('Hapus data ini?', 'Data yang dihapus tidak dapat dikembalikan.');
        return view('dash.shift.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $route_name = route('shift.store');
        $route_label = 'Tambah';
        return view('dash.shift.form', compact('route_name', 'route_label'));
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
        return redirect()->route('shift.edit', ['shift' => $id]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $route_name = route('shift.update', ['shift' => $id]);
        $route_label = 'Ubah';
        $data = Shift::findOrFail($id);
        return view('dash.shift.form', compact('data', 'route_name', 'route_label'));
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
        $shift = Shift::findOrFail($id);
        if ($shift->delete()) {
            return redirect()->route('shift.index')->with('success', 'Data berhasil dihapus.');
        } else {
            return redirect()->route('shift.index')->with('error', 'Data gagal dihapus.');
        }
    }

    /**
     * Get data for datatable
     */
    public function json(Request $request)
    {
        if ($request->ajax()) {
            $data = Shift::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $cols = '<div class="hstack gap-1">';
                    $cols .= '<a href="' . route('shift.edit', ['shift' => $row->id]) . '" class="btn btn-sm btn-warning btn-icon waves-effect waves-light" title="Edit"><i class="bx bxs-pencil fs-6"></i></a>';
                    $cols .= '<a href="' . route('shift.destroy', ['shift' => $row->id]) . '" class="btn btn-sm btn-danger btn-icon waves-effect waves-light" title="Hapus" data-confirm-delete="true"><i class="bx bxs-trash fs-6"></i></a>';
                    $cols .= '</div>';
                    return $cols;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
}
