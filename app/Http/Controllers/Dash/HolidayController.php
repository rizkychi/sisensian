<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class HolidayController extends Controller
{
    private $slug = 'holiday';

    /**
     * Display title
     */
    function __construct()
    {
        $start_year = 2024;
        $end_year = date('Y') + 2;
        $years = range($start_year, $end_year);
        view()->share('years', $years);
        return view()->share('title', 'Hari Libur');
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
        return view("dash.$this->slug.form", compact('route_name', 'route_label'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validate request
        $request->validate([
            'date' => 'required|date',
            'name' => 'required|string',
        ]);

        try {
            \DB::transaction(function () use ($request) {
                $data = new Holiday();
                $data->date = $request->date;
                $data->name = $request->name;
                $data->is_day_off = $request->is_day_off == 'on' ? 1 : 0;
                $data->save();
            });

            return redirect()->route("$this->slug.index")->with('success', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Data gagal disimpan: ' . $e->getMessage());
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
        $data = Holiday::findOrFail($id);
        return view("dash.$this->slug.form", compact('data', 'route_name', 'route_label'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // validate request
        $request->validate([
            'date' => 'required|date',
            'name' => 'required|string',
        ]);

        try {
            \DB::transaction(function () use ($request, $id) {
                $data = Holiday::findOrFail($id);
                $data->date = $request->date;
                $data->name = $request->name;
                $data->is_day_off = $request->is_day_off == 'on' ? 1 : 0;
                $data->save();
            });

            return redirect()->route("$this->slug.index")->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Data gagal diperbarui: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            \DB::transaction(function () use ($id) {
                $data = Holiday::findOrFail($id);

                $data->delete();
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
            if ($request->year != null) {
                $data = Holiday::whereYear('date', $request->year)->get();
            } else {
                $data = Holiday::all();
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $cols = '<div class="hstack gap-1">';
                    $cols .= '<a href="' . route("$this->slug.edit", ["$this->slug" => $row->id]) . '" class="btn btn-sm btn-warning btn-icon waves-effect waves-light" title="Edit"><i class="bx bxs-pencil fs-6"></i></a>';
                    $cols .= '<a href="' . route("$this->slug.destroy", ["$this->slug" => $row->id]) . '" class="btn btn-sm btn-danger btn-icon waves-effect waves-light" title="Hapus" data-confirm-delete="true"><i class="bx bxs-trash fs-6"></i></a>';
                    $cols .= '</div>';
                    return $cols;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    /**
     * Sync holiday data from API
     */
    public function sync(Request $request)
    {
        // validate request
        $request->validate([
            'year' => 'required|integer',
        ]);

        $client = new \GuzzleHttp\Client([
            'verify' => false, // Disable SSL verification
        ]);

        try {
            // Call API to get holiday data
            $response = $client->get(env('API_HOLIDAY_URL') . "?year=$request->year");
            $data = json_decode($response->getBody(), true);

            foreach ($data as $holiday) {
                Holiday::updateOrCreate([
                    'date' => $holiday['tanggal'],
                    'name' => $holiday['keterangan'],
                    'is_day_off' => $holiday['is_cuti'],
                ]);
            }

            return response()->json(['status' => 'success', 'message' => 'Data berhasil disinkronisasi.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Data gagal disinkronisasi: ' . $e->getMessage()]);
        }
    }
}
