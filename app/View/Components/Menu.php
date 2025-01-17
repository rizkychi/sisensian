<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
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
        $menus = [
            (object) [
                'name' => 'Dashboard',
                'icon' => 'ri-dashboard-2-line',
                'route' => 'dashboard.index',
            ],
            (object) [
                'name' => 'Presensi',
                'icon' => 'ri-fingerprint-line',
                'route' => 'attendance.index',
            ],
            (object) [
                'name' => 'Pengajuan Cuti',
                'icon' => 'mdi mdi-exit-run',
                'route' => 'leave.index',
            ],
            (object) [
                'name' => 'Penjadwalan',
                'icon' => 'mdi mdi-calendar-multiselect',
                'route' => null,
                'slug' => 'schedule',
                'submenus' => [
                    (object) [
                        'name' => 'Jadwal Reguler',
                        'icon' => 'ri-calendar-todo-line',
                        'route' => 'regular',
                    ],
                    (object) [
                        'name' => 'Jadwal Shift',
                        'icon' => 'ri-calendar-2-fill ',
                        'route' => 'sift',
                    ],
                ],
            ],
            (object) [
                'name' => 'Laporan',
                'icon' => 'bx bxs-report',
                'route' => 'report.index',
            ],
            (object) [
                'name' => 'Master Data',
                'icon' => 'bx bxs-data',
                'route' => null,
                'slug' => 'masterdata',
                'submenus' => [
                    (object) [
                        'name' => 'Karyawan',
                        'icon' => 'ri-group-fill',
                        'route' => 'employee.index',
                    ],
                    (object) [
                        'name' => 'Kantor',
                        'icon' => 'bx bxs-buildings ',
                        'route' => 'office.index',
                    ],
                    (object) [
                        'name' => 'Shift',
                        'icon' => 'ri-time-line',
                        'route' => 'shift.index',
                    ],
                ],
            ],
            (object) [
                'name' => 'Pengaturan',
                'icon' => 'bx bxs-cog',
                'route' => null
            ],
        ];
        
        return view('components.'.$this->view, compact('menus'));
    }
}
