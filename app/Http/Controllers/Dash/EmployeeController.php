<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Office;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    private $slug = 'employee';

    /**
     * Display title
     */
    function __construct()
    {
        return view()->share('title', 'Karyawan');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        confirmDelete('Hapus data ini?', 'Data yang dihapus tidak dapat dikembalikan.');
        return view("dash.$this->slug.index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $route_name = route("$this->slug.store");
        $route_label = 'Tambah';
        $office = Office::where('is_active', true)->get();
        return view("dash.$this->slug.form", compact('route_name', 'route_label', 'office'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users',
            'id_number' => 'required|string|max:255|unique:employee',
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:13',
            'position' => 'nullable|string|max:255',
            'office_id' => 'required',
        ]);

        try {
            \DB::transaction(function () use ($request) {
                $office = Office::findOrFail($request->office_id);

                $user = new User();
                $user->username = $request->username;
                $user->password = Hash::make($request->password);
                $user->email = $request->email;
                $user->save();

                $data = new Employee();
                $data->user_id = $user->id;
                $data->id_number = $request->id_number;
                $data->name = $request->name;
                $data->address = $request->address;
                $data->phone = $request->phone;
                $data->position = $request->position;
                $data->office_id = $request->office_id;
                $data->is_active = $request->is_active == 'on' ? true : false;
                $data->save();
            });

            return redirect()->route("$this->slug.index")->with('success', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Data gagal disimpan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route("$this->slug.edit", ["$this->slug" => $id]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $route_name = route("$this->slug.update", ["$this->slug" => $id]);
        $route_label = 'Ubah';
        $data = Employee::findOrFail($id);
        $office = Office::where('is_active', true)->get();
        return view("dash.$this->slug.form", compact('data', 'route_name', 'route_label', 'office'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $valid = Employee::findOrFail($id);

        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $valid->user_id,
            'password' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users,email,' . $valid->user_id,
            'id_number' => 'required|string|max:255|unique:employee,id_number,' . $valid->id,
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:13',
            'position' => 'nullable|string|max:255',
            'office_id' => 'required',
        ]);

        try {
            \DB::transaction(function () use ($request, $id) {
                $office = Office::findOrFail($request->office_id);

                $data = Employee::findOrFail($id);

                $data->id_number = $request->id_number;
                $data->name = $request->name;
                $data->address = $request->address;
                $data->phone = $request->phone;
                $data->position = $request->position;
                $data->office_id = $request->office_id;
                $data->is_active = $request->is_active == 'on' ? true : false;
                $data->save();

                $user = User::findOrFail($data->user_id);

                $user->username = $request->username;
                $user->password = Hash::make($request->password);
                $user->email = $request->email;
                $user->save();
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
        try {
            \DB::transaction(function () use ($id) {
                $data = Employee::findOrFail($id);
                $user = User::findOrFail($data->user_id);

                $data->delete();
                $user->delete();
            });

            return redirect()->route("$this->slug.index")->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route("$this->slug.index")->with('error', 'Data gagal dihapus: ' . $e->getMessage());
        }
    }

    /**
     * Get data for datatable
     */
    public function json(Request $request)
    {
        if ($request->ajax()) {
            $data = Employee::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $cols = '<div class="hstack gap-1">';
                    $cols .= '<a href="' . route("$this->slug.edit", ["$this->slug" => $row->id]) . '" class="btn btn-sm btn-warning btn-icon waves-effect waves-light" title="Edit"><i class="bx bxs-pencil fs-6"></i></a>';
                    $cols .= '<a href="' . route("$this->slug.destroy", ["$this->slug" => $row->id]) . '" class="btn btn-sm btn-danger btn-icon waves-effect waves-light" title="Hapus" data-confirm-delete="true"><i class="bx bxs-trash fs-6"></i></a>';
                    $cols .= '</div>';
                    return $cols;
                })
                ->addColumn('office_name', function($row){
                    return $row->office->name;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
}
