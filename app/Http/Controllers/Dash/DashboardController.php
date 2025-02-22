<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Office;
use App\Models\Shift;
use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    /**
     * Display title
     */
    function __construct()
    {
        return view()->share('title', 'Dashboard');
    }

    /**
     * Display a listing of the resource.
     */
    public function index() 
    {
        $user = Auth::user()->role;
        if ($user == 'user') {
            return redirect()->route('attendance.index');
        }
        $c_employee = Employee::count();
        $c_office = Office::count();
        $c_shift = Shift::count();
        $c_leave = Leave::count();
        $c_attendance = Attendance::whereYear('date', date('Y'))
                      ->whereMonth('date', date('m'))
                      ->count();

        return view('dash.index', compact(
            'c_employee',
            'c_office',
            'c_shift',
            'c_leave',
            'c_attendance'
        ));
    }

    /**
     * Display the specified resource.
     */
    public function profile()
    {
        echo 'ok';
    }

    /**
     * Update the specified resource in storage.
     */
    public function profileUpdate(Request $request)
    {
        echo 'ok';
    }

    /**
     * Display the specified resource.
     */
    public function password()
    {
        return view('dash.password');
    }

    /**
     * Update the specified resource in storage.
     */
    public function passwordUpdate(Request $request)
    {
        // Validate the request
        $request->validate([
            'password' => 'required|string|min:8|confirmed:confirm_password',
            'confirm_password' => 'required|string|min:8',
        ]);

        $user = Auth::user();

        if (Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password baru tidak boleh sama dengan password lama.']);
        }

        try {
            $user->password = Hash::make($request->password);
            $user->save();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat mengubah password.' . $e->getMessage()]);
        }

        return redirect()->route('password.index')->with('success', 'Password berhasil diubah.');
    }
}
