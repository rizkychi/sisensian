<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class Menu extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $view,
    ){}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $admin = [
            (object) [
                'name' => 'Dashboard',
                'icon' => 'ri-dashboard-2-line',
                'route' => 'dashboard.index',
                'show' => true,
            ],
            (object) [
                'name' => 'Pengaturan',
                'icon' => 'mdi mdi-cog-outline',
                'route' => 'password.index',
                'show' => false,
            ],
            (object) [
                'name' => 'Profile',
                'icon' => 'mdi mdi-account-circle',
                'route' => 'profile.index',
                'show' => false,
            ],
            // (object) [
            //     'name' => 'Presensi',
            //     'icon' => 'ri-fingerprint-line',
            //     'route' => 'attendance.index',
            // ],
            (object) [
                'name' => 'Pengajuan Cuti',
                'icon' => 'mdi mdi-exit-run',
                'route' => 'leave.index',
                'show' => true,
            ],
            (object) [
                'name' => 'Penjadwalan',
                'icon' => 'mdi mdi-calendar-multiselect',
                'route' => null,
                'show' => true,
                'slug' => 'schedule',
                'submenus' => [
                    (object) [
                        'name' => 'Jadwal Reguler',
                        'icon' => 'ri-calendar-todo-line',
                        'route' => 'regular',
                        'show' => true,
                    ],
                    (object) [
                        'name' => 'Jadwal Shift',
                        'icon' => 'ri-calendar-2-fill ',
                        'route' => 'sift',
                        'show' => true,
                    ],
                ],
            ],
            (object) [
                'name' => 'Laporan',
                'icon' => 'bx bxs-report',
                'route' => null,
                'show' => true,
                'slug' => 'report',
                'submenus' => [
                    (object) [
                        'name' => 'Rekap Presensi',
                        'icon' => 'mdi mdi-file-table',
                        'route' => 'reportsummary',
                        'show' => true,
                    ],
                    (object) [
                        'name' => 'Presensi Karyawan',
                        'icon' => 'mdi mdi-file-account',
                        'route' => 'reportattendance',
                        'show' => true,
                    ],
                    (object) [
                        'name' => 'Cuti Karyawan',
                        'icon' => 'mdi mdi-file-move',
                        'route' => 'reportleav',
                        'show' => true,
                    ],
                ],
            ],
            (object) [
                'name' => 'Master Data',
                'icon' => 'bx bxs-data',
                'route' => null,
                'show' => true,
                'slug' => 'masterdata',
                'submenus' => [
                    (object) [
                        'name' => 'Karyawan',
                        'icon' => 'ri-group-fill',
                        'route' => 'employee.index',
                        'show' => true,
                    ],
                    (object) [
                        'name' => 'Kantor',
                        'icon' => 'bx bxs-buildings ',
                        'route' => 'office.index',
                        'show' => true,
                    ],
                    (object) [
                        'name' => 'Shift',
                        'icon' => 'ri-time-line',
                        'route' => 'shift.index',
                        'show' => true,
                    ],
                ],
            ],
            // (object) [
            //     'name' => 'Pengaturan',
            //     'icon' => 'bx bxs-cog',
            //     'route' => null
            // ],
        ];

        $user = [
            // (object) [
            //     'name' => 'Dashboard',
            //     'icon' => 'ri-dashboard-2-line',
            //     'route' => 'dashboard.index',
            // ],
            (object) [
                'name' => 'Pengaturan',
                'icon' => 'mdi mdi-cog-outline',
                'route' => 'password.index',
                'show' => false,
            ],
            (object) [
                'name' => 'Profile',
                'icon' => 'mdi mdi-account-circle',
                'route' => 'profile.index',
                'show' => false,
            ],
            (object) [
                'name' => 'Presensi',
                'icon' => 'ri-fingerprint-line',
                'route' => 'attendance.index',
                'show' => true,
            ],
            (object) [
                'name' => 'Pengajuan Cuti',
                'icon' => 'mdi mdi-exit-run',
                'route' => 'leave.index',
                'show' => true,
            ],
            // (object) [
            //     'name' => 'Penjadwalan',
            //     'icon' => 'mdi mdi-calendar-multiselect',
            //     'route' => null,
            //     'slug' => 'schedule',
            //     'submenus' => [
            //         (object) [
            //             'name' => 'Jadwal Reguler',
            //             'icon' => 'ri-calendar-todo-line',
            //             'route' => 'regular',
            //         ],
            //         (object) [
            //             'name' => 'Jadwal Shift',
            //             'icon' => 'ri-calendar-2-fill ',
            //             'route' => 'sift',
            //         ],
            //     ],
            // ],
            // (object) [
            //     'name' => 'Laporan',
            //     'icon' => 'bx bxs-report',
            //     'route' => 'report.index',
            // ],
            // (object) [
            //     'name' => 'Master Data',
            //     'icon' => 'bx bxs-data',
            //     'route' => null,
            //     'slug' => 'masterdata',
            //     'submenus' => [
            //         (object) [
            //             'name' => 'Karyawan',
            //             'icon' => 'ri-group-fill',
            //             'route' => 'employee.index',
            //         ],
            //         (object) [
            //             'name' => 'Kantor',
            //             'icon' => 'bx bxs-buildings ',
            //             'route' => 'office.index',
            //         ],
            //         (object) [
            //             'name' => 'Shift',
            //             'icon' => 'ri-time-line',
            //             'route' => 'shift.index',
            //         ],
            //     ],
            // ],
            // (object) [
            //     'name' => 'Pengaturan',
            //     'icon' => 'bx bxs-cog',
            //     'route' => null
            // ],
        ];

        $role = Auth::user()->role;
        if ($role == 'superadmin' || $role == 'admin') {
            $menus = $admin;
        } else {
            $menus = $user;
        }
        
        return view('components.'.$this->view, compact('menus'));
    }
}
