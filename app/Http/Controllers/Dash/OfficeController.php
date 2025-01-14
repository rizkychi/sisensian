<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use App\Models\Office;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        confirmDelete('Hapus data ini?', 'Data yang dihapus tidak dapat dikembalikan.');   
        return view('dash.office.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dash.office.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'string|max:255',
            'description' => 'string|max:255',
            'lat' => 'required|string|max:255',
            'long' => 'required|string|max:255',
            'radius' => 'required|string|max:255',
        ]);

        $office = new Office();
        $office->name = $request->name;
        $office->address = $request->address;
        $office->description = $request->description;
        $office->lat = $request->lat;
        $office->long = $request->long;
        $office->radius = $request->radius;

        if ($office->save()) {
            return redirect()->route('office.index')->with('success', 'Data berhasil disimpan.');
        } else {
            return back()->with('error', 'Data gagal disimpan.')->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('office.edit', ['office' => $id]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Office::findOrFail($id);
        return view('dash.office.form', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'string|max:255',
            'description' => 'string|max:255',
            'lat' => 'required|string|max:255',
            'long' => 'required|string|max:255',
            'radius' => 'required|string|max:255',
        ]);

        $office = Office::findOrFail($id);
        $office->name = $request->name;
        $office->address = $request->address;
        $office->description = $request->description;
        $office->lat = $request->lat;
        $office->long = $request->long;
        $office->radius = $request->radius;

        if ($office->save()) {
            return redirect()->route('office.index')->with('success', 'Data berhasil diupdate.');
        } else {
            return back()->with('error', 'Data gagal diupdate.')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $office = Office::findOrFail($id);
        if ($office->delete()) {
            return redirect()->route('office.index')->with('success', 'Data berhasil dihapus.');
        } else {
            return redirect()->route('office.index')->with('error', 'Data gagal dihapus.');
        }
    }

    /**
     * Get data for datatable
     */
    public function json(Request $request)
    {
        if ($request->ajax()) {
            $data = Office::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $cols = '<div class="hstack gap-1">';
                    $cols .= '<a href="' . route('office.edit', ['office' => $row->id]) . '" class="btn btn-sm btn-warning btn-icon waves-effect waves-light" title="Edit"><i class="bx bxs-pencil fs-6"></i></a>';
                    $cols .= '<a href="' . route('office.destroy', ['office' => $row->id]) . '" class="btn btn-sm btn-danger btn-icon waves-effect waves-light" title="Hapus" data-confirm-delete="true"><i class="bx bxs-trash fs-6"></i></a>';
                    $cols .= '</div>';
                    return $cols;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
}
