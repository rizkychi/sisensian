<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use App\Models\Office;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $today = \Carbon\Carbon::now();
        $login = (object) [
            'text_color' => 'text-muted text-opacity-75',
            'icon' => 'ri-check-line'
        ];
        $logout = (object) [
            'text_color' => 'text-muted text-opacity-75',
            'icon' => 'la-hourglass-half'
        ];
        $attendace_text = 'Berangkat';
        $employee = auth()->user()->employee;
        $office = Office::where('id', $employee->office_id)->first();
        return view('dash.attendance.index', compact('today', 'login', 'logout', 'employee', 'office'))->with('attendance_text', $attendace_text);
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
}
