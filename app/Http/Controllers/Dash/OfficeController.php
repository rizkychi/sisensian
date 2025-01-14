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
        return view('dash.office.index');
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
                    $cols = '<div class="d-flex">';
                    $cols .= '<a href="' . route('office.edit', ['office' => $row->id]) . '" class="btn-sm btn-primary mx-1" title="Edit"><i class="fas fa-pen"></i></a>';
                    // $cols .= '<a href="" data-url="' . route('office.delete', ['office' => $row->id]) . '" data-text="' . $this->title . '" class="btn-sm btn-danger mx-1" onclick="deleteConfirm(event, this)" title="Hapus"><i class="fas fa-trash"></i></a>';
                    $cols .= '</div>';
                    return $cols;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
}
