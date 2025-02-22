<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class Navbar extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $user = Auth::user();
        if ($user->employee == null) {
            $username = ucfirst($user->username);
            $surname = $username;
            $role = 'Administrator';
        } else {
            $username = ucfirst($user->employee->name);
            $surname = explode(' ', trim($username))[0];
            $role = ucfirst($user->employee->position ?? 'Karyawan');
        }

        // Profile picture
        $profilepic = $user->avatar ? \Storage::url($user->avatar) : asset('assets/images/users/user-dummy-img.jpg');
        return view('components.navbar')
            ->with('username', $username)
            ->with('surname', $surname)
            ->with('role', $role)
            ->with('profilepic', $profilepic);
    }
}
