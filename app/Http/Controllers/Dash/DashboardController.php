<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\Office;
use App\Models\Shift;
use App\Models\Leave;
use App\Models\User;
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

        $upcoming = Holiday::where('date', '>=', date('Y-m-d'))->orderBy('date', 'asc')->limit(7)->get();
        $masterholidays = Holiday::all();
        $holidays = [];
        foreach ($masterholidays as $holiday) {
            $temp = new \stdClass();
            $temp->id = $holiday->id;
            $temp->title = $holiday->name;
            $temp->start = $holiday->date;
            $temp->allDay = !0;
            $temp->className = $holiday->is_day_off ? 'bg-danger-subtle' : 'bg-info-subtle';
            $holidays[] = $temp;
            unset($temp);
        }

        return view('dash.index', compact(
            'c_employee',
            'c_office',
            'c_shift',
            'c_leave',
            'c_attendance',
            'holidays',
            'upcoming'
        ));
    }

    /**
     * Display the specified resource.
     */
    public function profile()
    {
        $route_label = 'Profil';
        $user = Auth::user();
        if ($user->employee != null) {
            $data = $user->employee;
        } else {
            $data = (object) [
                'name' => $user->username,
                'user' => (object) [
                    'username' => $user->username,
                    'email' => $user->email,
                ],
                'address' => null,
                'phone' => null,
                'id' => $user->id,
                'is_admin' => $user->role == 'superadmin' ? true : false,
            ];
        }
        $profilepic = $user->avatar ? \Storage::url($user->avatar) : 'assets/images/users/user-dummy-img.jpg';
        return view('dash.profile', compact('data', 'route_label'))
            ->with('profilepic', $profilepic);
    }

    /**
     * Update the specified resource in storage.
     */
    public function profileUpdate(Request $request)
    {
        $user = Auth::user();
        if ($user->employee != null) {
            $without_employee = false;
        } else {
            $without_employee = true;
        }
        $id = $user->id;

        if ($without_employee) {
            $request->validate([
                'username' => 'required|string|alpha_num|max:255|unique:users,username,' . $id,
                'email' => 'required|string|max:255|unique:users,email,' . $id,
                // 'name' => 'required|string|max:255',
                // 'address' => 'nullable|string|max:255',
                // 'phone' => 'nullable|string|max:13',
            ]);
        } else {
            $request->validate([
                'username' => 'required|string|alpha_num|max:255|unique:users,username,' . $id,
                'email' => 'required|string|max:255|unique:users,email,' . $id,
                'name' => 'required|string|max:255',
                'address' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:13',
            ]);
        }

        try {
            \DB::transaction(function () use ($request, $id, $without_employee) {
                $user = User::findOrFail($id);

                $user->username = $request->username;
                $user->email = $request->email;
                $user->save();

                if (!$without_employee) {
                    $data = Employee::findOrFail($user->employee->id);

                    $data->name = $request->name;
                    $data->address = $request->address;
                    $data->phone = $request->phone;
                    $data->save();
                }
            });

            return redirect()->route("profile.index")->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Data gagal diperbarui: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function password()
    {
        $route_label = 'Pengaturan';
        return view('dash.password', compact('route_label'));
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

        $user = User::findOrFail(Auth::user()->id);

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

    /**
     * Upload avatar
     */
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = User::findOrFail(Auth::user()->id);
        try {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');

            // Optionally, delete the old avatar if exists
            if ($user->avatar) {
                \Storage::disk('public')->delete($user->avatar);
            }

            $user->avatar = 'avatars/' . basename($avatarPath);
            $user->save();

            return response()->json(['success' => true, 'avatar_url' => \Storage::url($user->avatar)]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => 'Terjadi kesalahan saat mengunggah foto: ' . $e->getMessage()]);
        }
    }
}
