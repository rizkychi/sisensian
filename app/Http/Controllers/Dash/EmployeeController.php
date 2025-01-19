<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Office;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Response;

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
        $office = Office::where('is_active', true)->get();
        return view("dash.$this->slug.index", compact('office'));
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
                $data->is_active = $request->is_active == 'on' ? 1 : 0;
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
            'password' => 'nullable|string|max:255',
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
                $data->is_active = $request->is_active == 'on' ? 1 : 0;
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
                ->addColumn('user', function($row){
                    return json_decode($row->user);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    /**
     * Import data from excel
     */
    public function import(Request $request)
    {
        $office = Office::findorFail($request->office_id);

        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048',
        ]);

        try {
            \DB::transaction(function () use ($request) {
                $path = $request->file('file')->getRealPath();
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);
                $data = $spreadsheet->getActiveSheet()->toArray();

                foreach ($data as $key => $value) {
                    if ($key > 0) { // Skip header row
                        if (empty($value[1]) || empty($value[2]) || empty($value[3]) || empty($value[4]) || empty($value[5])) {
                            continue; // Skip row if any required column is empty
                        }

                        $existingUser = User::where('username', $value[1])->orWhere('email', $value[3])->first();
                        $existingEmployee = Employee::where('id_number', $value[4])->first();

                        if ($existingUser || $existingEmployee) {
                            continue; // Skip row if user or employee already exists
                        }

                        $user = new User();
                        $user->username = $value[1];
                        $user->password = Hash::make($value[2]);
                        $user->email = $value[3];
                        $user->save();

                        $employee = new Employee();
                        $employee->user_id = $user->id;
                        $employee->id_number = $value[4];
                        $employee->name = $value[5];
                        $employee->address = $value[6] ?? null;
                        $employee->phone = $value[7] ?? null;
                        $employee->position = $value[8] ?? null;
                        $employee->office_id = $request->office_id;
                        $employee->is_active = true;
                        $employee->save();
                    }
                }
            });

            return redirect()->route("$this->slug.index")->with('success', 'Data berhasil diimport.');
        } catch (\Exception $e) {
            return back()->with('error', 'Data gagal diimport: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Download template excel
     */
    public function template()
    {
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];

        $callback = function() {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set header values
            $sheet->setCellValue('A1', 'No');
            $sheet->setCellValue('B1', 'Username');
            $sheet->setCellValue('C1', 'Password');
            $sheet->setCellValue('D1', 'Email');
            $sheet->setCellValue('E1', 'ID Karyawan');
            $sheet->setCellValue('F1', 'Nama Lengkap');
            $sheet->setCellValue('G1', 'Alamat');
            $sheet->setCellValue('H1', 'No. HP');
            $sheet->setCellValue('I1', 'Jabatan');

            // set number value
            $sheet->setCellValue('A2', 1);
            $sheet->setCellValue('A3', 2);
            $sheet->setCellValue('A4', 3);

            // Style the header
            $headerStyle = [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FFFFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF4CAF50'],
                ],
            ];
            $sheet->getStyle('A1:I1')->applyFromArray($headerStyle);

            // Set phone number column to text format
            $sheet->getStyle('H')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);

            // Set column widths
            $sheet->getColumnDimension('A')->setWidth(5);
            $sheet->getColumnDimension('B')->setWidth(20);
            $sheet->getColumnDimension('C')->setWidth(20);
            $sheet->getColumnDimension('D')->setWidth(25);
            $sheet->getColumnDimension('E')->setWidth(20);
            $sheet->getColumnDimension('F')->setWidth(25);
            $sheet->getColumnDimension('G')->setWidth(30);
            $sheet->getColumnDimension('H')->setWidth(15);
            $sheet->getColumnDimension('I')->setWidth(20);

            // Add border to all cells
            $sheet->getStyle('A1:I100')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            header('Content-Disposition: attachment; filename="karyawan_template.xlsx"');
            $writer->save('php://output');
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Get employees by office
     */
    public function getEmployeesByOffice(string $office_id)
    {
        $data = Employee::where('office_id', $office_id)->get();
        return response()->json($data);
    }
}
