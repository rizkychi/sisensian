<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use App\Models\Office;

class ReportController extends Controller
{
    private $slug = 'report';
    private $months = [
        '01' => 'Januari',
        '02' => 'Februari',
        '03' => 'Maret',
        '04' => 'April',
        '05' => 'Mei',
        '06' => 'Juni',
        '07' => 'Juli',
        '08' => 'Agustus',
        '09' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember',
    ];
    
    /**
     * Display title
     */
    function __construct()
    {
        view()->share('title', 'Laporan');
        view()->share('months', $this->months);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dash.report.index');
    }

    /**
     * Display a listing of the request.
     */
    public function summary()
    {
        return view('dash.report.summary');
    }

    /**
     * Display a listing of the request.
     */
    public function attendance()
    {
        return view("dash.$this->slug.attendance");
    }

    /**
     * Display a listing of the request.
     */
    public function leave()
    {
        $office = Office::where('is_active', true)->get();
        $show = false;
        if (request()->query('office') && request()->query('month')) {
            $show = true;

            // $date_start = request()->query('month') . '-01';
        }
        return view("dash.$this->slug.leave", compact('office'))
            ->with('show', $show);
    }
}
