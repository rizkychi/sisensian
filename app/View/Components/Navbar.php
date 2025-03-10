<?php

namespace App\View\Components;

use App\Models\Notification;
use Closure;
use GuzzleHttp\Psr7\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class Navbar extends Component
{
    public $app_company;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        // Company Name
        $this->app_company = session('app_company') == null ? env('APP_COMPANY') : session('app_company');
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
        
        // Notification
        $notification = Notification::getNotification();
        $c_notif = Notification::countNotification();

        return view('components.navbar')
            ->with('username', $username)
            ->with('surname', $surname)
            ->with('role', $role)
            ->with('app_company', $this->app_company)
            ->with('profilepic', $profilepic)
            ->with('notification', $notification)
            ->with('c_notif', $c_notif);
    }
}
