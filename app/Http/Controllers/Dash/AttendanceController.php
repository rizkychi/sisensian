<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\Office;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AttendanceController extends Controller
{
    protected $tolerance_in = 2; // hours
    protected $tolerance_out = 6; // hours

    /**
     * PATCH 2026-11-10 — dari kode fixing user + review 14:01 WIB.
     * Yang diperbaiki di file ini:
     *  - No. 9  : pemilihan jadwal kemarin (shift 07:30-07:00) — sudah ada di kode user, dipertahankan.
     *  - No. 1  : tanggal efektif — regular pakai hari ini, shift pakai $schedule->date.
     *             Dipakai di index() DAN store() untuk cari attendance & hitung window.
     *  - No. 2  : store() cek kepemilikan (where employee_id) + guard null schedule/shift/office.
     *  - No. 3  : store() cek toleransi server-side dengan midpoint eksplisit.
     *  - No. 4  : duplikat ditolak + DB::transaction + lockForUpdate().
     *  - No. 5  : store() blokir cuti (is_on_leave) dan libur (regular saja).
     *  - No. 6  : radius Haversine dipertahankan + validasi lat/long numeric.
     *  - No. 7  : guard null shift di index()/store(), hapus @$attendance.
     *  - No. 13 : use Illuminate\Support\Str (sudah benar di kode user).
     *  - No. 15 : catch tidak lagi membocorkan $e->getMessage(), diganti pesan generik + Log.
     *  - Pulang cepat: kalau sudah check-in dan belum check-out, boleh check-out
     *    kapan saja sampai time_out + 6 jam, tanpa peduli midpoint.
     * Yang BELUM disentuh di patch ini: history() & schedule() (No. 10-12),
     * exists:schedule,id (No. 14 — pastikan nama tabel memang `schedule`).
     */

    public function index()
    {
        $today = Carbon::now();
        $employee = Auth::user()->employee;
        $office = $employee ? Office::find($employee->office_id) : null;
        $date_in = $date_out = null;
        $attendance = null;
        $effectiveDate = $today->format('Y-m-d');

        $login = (object) [
            'text' => 'Belum melakukan presensi',
            'text_color' => 'text-muted text-opacity-75',
            'icon' => 'la-hourglass-half'
        ];
        $logout = (object) [
            'text' => 'Belum melakukan presensi',
            'text_color' => 'text-muted text-opacity-75',
            'icon' => 'la-hourglass-half'
        ];

        $label = (object) [
            'text' => 'Tidak ada jadwal',
            'color' => 'danger',
            'type' => null,
            'is_visible' => false
        ];

        // check schedule
        $schedule = null;
        if ($employee && $employee->category == 'regular') {
            // schedule (regular)
            $schedule = Schedule::where('employee_id', $employee->id)
                ->where('is_recurring', true)
                ->where('day_of_week', Str::lower($today->format('l')))
                ->first();

            $date_in = $date_out = $today->translatedFormat('l, d F Y');
            $effectiveDate = $today->format('Y-m-d');
        } else {
            // schedule (shift) — fix No. 9, dipertahankan dari kode user
            $yesterday = $today->copy()->subDay();
            $yesterday_schedule = Schedule::where('employee_id', $employee->id)
                ->where('is_recurring', false)
                ->where('date', $yesterday->format('Y-m-d'))
                ->first();

            $use_yesterday = false;

            if ($yesterday_schedule && $yesterday_schedule->shift && $yesterday_schedule->shift->is_next_day) {
                $yTimeIn = Carbon::createFromFormat('Y-m-d H:i', $yesterday->format('Y-m-d') . ' ' . $yesterday_schedule->shift->time_in);
                $yTimeOut = Carbon::createFromFormat('Y-m-d H:i', $yesterday->format('Y-m-d') . ' ' . $yesterday_schedule->shift->time_out);
                if ($yTimeOut->lte($yTimeIn)) {
                    $yTimeOut->addDay();
                }

                $yCheckoutDeadline = $yTimeOut->copy()->addHours($this->tolerance_out);
                $yCheckinStart = $yTimeIn->copy()->subHours($this->tolerance_in);

                $yAttendance = Attendance::where('employee_id', $employee->id)
                    ->where('date', $yesterday_schedule->date)
                    ->first();

                $yAlreadyCheckedOut = $yAttendance && $yAttendance->check_out_time;
                $yMissedCheckout = $today->gt($yCheckoutDeadline);

                if (! $yAlreadyCheckedOut && ! $yMissedCheckout && $today->between($yCheckinStart, $yCheckoutDeadline)) {
                    $use_yesterday = true;
                }
            }

            if ($use_yesterday) {
                $schedule = $yesterday_schedule;
                $date_in = $yesterday->translatedFormat('l, d F Y');
                $date_out = $today->translatedFormat('l, d F Y');
                $effectiveDate = $yesterday_schedule->date;
            } else {
                $schedule = Schedule::where('employee_id', $employee->id)
                    ->where('is_recurring', false)
                    ->where('date', $today->format('Y-m-d'))
                    ->first();

                if ($schedule) {
                    $date_in = $date_out = $today->translatedFormat('l, d F Y');
                    $effectiveDate = $schedule->date;
                }

                if ($schedule && $schedule->shift && $schedule->shift->is_next_day) {
                    $date_out = $today->copy()->addDay()->translatedFormat('l, d F Y');
                }
            }
        }

        if ($schedule && $schedule->shift) {
            // No. 1: pakai tanggal efektif, bukan $schedule->date mentah
            $attendance = Attendance::where('employee_id', $employee->id)
                ->where('date', $effectiveDate)
                ->first();

            if ($attendance) {
                if ($attendance->check_in_time) {
                    $login->text = 'Berhasil presensi pada <b>' . $attendance->check_in_time . '</b>';
                    $login->text_color = 'text-success';
                    $login->icon = 'ri-check-line';
                }
                if ($attendance->check_out_time) {
                    $logout->text = 'Berhasil presensi pada <b>' . $attendance->check_out_time . '</b>';
                    $logout->text_color = 'text-success';
                    $logout->icon = 'ri-check-line';
                }
            }

            // on leave
            if ($attendance && $attendance->is_on_leave) {
                $label->text = 'Cuti';
                $label->color = 'warning';
                $label->is_visible = false;
            } else {
                $now_time = $today->copy();
                $time_in_time = Carbon::createFromFormat('Y-m-d H:i', $effectiveDate . ' ' . $schedule->shift->time_in);
                $time_out_time = Carbon::createFromFormat('Y-m-d H:i', $effectiveDate . ' ' . $schedule->shift->time_out);

                if ($time_out_time->lte($time_in_time)) {
                    $time_out_time->addDay();
                }

                // Midpoint eksplisit: total durasi / 2
                $durationSeconds = $time_in_time->diffInSeconds($time_out_time);
                $midpoint = $time_in_time->copy()->addSeconds(intdiv($durationSeconds, 2));
                $checkInStart = $time_in_time->copy()->subHours($this->tolerance_in);
                $checkOutDeadline = $time_out_time->copy()->addHours($this->tolerance_out);

                $hasIn = $attendance && $attendance->check_in_time;
                $hasOut = $attendance && $attendance->check_out_time;

                if ($hasIn && ! $hasOut) {
                    // Sudah check-in: boleh pulang cepat kapan saja sampai deadline.
                    if ($now_time->lte($checkOutDeadline)) {
                        $label->text = 'Pulang';
                        $label->color = 'success';
                        $label->type = 'out';
                        $label->is_visible = true;
                    } else {
                        $label->text = 'Di luar waktu presensi';
                        $label->color = 'danger';
                    }
                } elseif (! $hasIn && ! $hasOut) {
                    if ($now_time->gte($checkInStart) && $now_time->lt($midpoint)) {
                        $label->text = 'Berangkat';
                        $label->color = 'success';
                        $label->type = 'in';
                        $label->is_visible = true;
                    } elseif ($now_time->gte($midpoint) && $now_time->lte($checkOutDeadline)) {
                        // Missed check-in: hanya bisa check-out
                        $label->text = 'Pulang';
                        $label->color = 'success';
                        $label->type = 'out';
                        $label->is_visible = true;
                    } else {
                        $label->text = 'Di luar waktu presensi';
                        $label->color = 'danger';
                    }
                } else {
                    // Sudah check-in dan check-out: tidak ada tombol
                    $label->text = 'Di luar waktu presensi';
                    $label->color = 'danger';
                    $label->is_visible = false;
                }
            }
        }

        // check holiday
        $holiday = Holiday::where('date', $today->format('Y-m-d'))->first();
        if ($holiday && $holiday->is_day_off && $employee && $employee->category == 'regular' && $schedule) {
            $label->text = 'Libur';
            $label->color = 'danger';
            $label->is_visible = false;
        }

        return view('dash.attendance.index', compact(
            'today',
            'date_in',
            'date_out',
            'login',
            'logout',
            'employee',
            'office',
            'schedule',
            'label',
        ))->with('attendance', $attendance);
    }

    public function store(Request $request)
    {
        $request->validate([
            'att_type' => 'required|in:in,out',
            'att_lat' => 'required|numeric|between:-90,90',
            'att_long' => 'required|numeric|between:-180,180',
            'att_address' => 'required|string',
            'schedule_id' => 'required|string|exists:schedule,id',
        ]);

        $employee = Auth::user()->employee;
        if (! $employee) {
            return redirect()->route('attendance.index')->with('error', 'Data karyawan tidak ditemukan');
        }

        // No. 2 + guard null: jadwal harus milik karyawan ini dan shift-nya ada
        $schedule = Schedule::where('id', $request->schedule_id)
            ->where('employee_id', $employee->id)
            ->first();

        if (! $schedule || ! $schedule->shift) {
            return redirect()->route('attendance.index')->with('error', 'Jadwal tidak ditemukan');
        }

        $office = Office::find($employee->office_id);
        if (! $office || $office->lat === null || $office->long === null || $office->radius === null) {
            return redirect()->route('attendance.index')->with('error', 'Data kantor tidak lengkap');
        }

        $now_time = Carbon::now();

        // No. 1: tanggal efektif — regular = hari ini, shift = tanggal schedule
        $effectiveDate = $schedule->is_recurring
            ? $now_time->format('Y-m-d')
            : $schedule->date;

        $time_in_time = Carbon::createFromFormat('Y-m-d H:i', $effectiveDate . ' ' . $schedule->shift->time_in);
        $time_out_time = Carbon::createFromFormat('Y-m-d H:i', $effectiveDate . ' ' . $schedule->shift->time_out);
        if ($time_out_time->lte($time_in_time)) {
            $time_out_time->addDay();
        }

        $durationSeconds = $time_in_time->diffInSeconds($time_out_time);
        $midpoint = $time_in_time->copy()->addSeconds(intdiv($durationSeconds, 2));
        $checkInStart = $time_in_time->copy()->subHours($this->tolerance_in);
        $checkOutDeadline = $time_out_time->copy()->addHours($this->tolerance_out);

        $attendance = Attendance::where('employee_id', $employee->id)
            ->where('date', $effectiveDate)
            ->first();

        // No. 5: blokir cuti
        if ($attendance && $attendance->is_on_leave) {
            return redirect()->route('attendance.index')->with('error', 'Anda sedang cuti');
        }

        // No. 5: blokir libur untuk regular saja, shift tetap boleh
        $holiday = Holiday::where('date', $effectiveDate)->first();
        if ($holiday && $holiday->is_day_off && $employee->category == 'regular') {
            return redirect()->route('attendance.index')->with('error', 'Hari ini libur');
        }

        // No. 4: duplikat ditolak (dicek lagi di dalam transaksi dengan lock)
        if ($request->att_type == 'in' && $attendance && $attendance->check_in_time) {
            return redirect()->route('attendance.index')->with('error', 'Anda sudah melakukan presensi masuk');
        }
        if ($request->att_type == 'out' && $attendance && $attendance->check_out_time) {
            return redirect()->route('attendance.index')->with('error', 'Anda sudah melakukan presensi keluar');
        }

        // No. 3: cek toleransi server-side
        if ($request->att_type == 'in') {
            if (! ($now_time->gte($checkInStart) && $now_time->lt($midpoint))) {
                return redirect()->route('attendance.index')->with('error', 'Waktu presensi masuk tidak sesuai jadwal');
            }
        }

        if ($request->att_type == 'out') {
            $hasIn = $attendance && $attendance->check_in_time;
            if ($hasIn) {
                // Sudah check-in: boleh pulang cepat kapan saja sampai deadline
                $outAllowed = $now_time->gte($checkInStart) && $now_time->lte($checkOutDeadline);
            } else {
                // Missed check-in: hanya boleh check-out setelah midpoint
                $outAllowed = $now_time->gte($midpoint) && $now_time->lte($checkOutDeadline);
            }
            if (! $outAllowed) {
                return redirect()->route('attendance.index')->with('error', 'Waktu presensi keluar tidak sesuai jadwal');
            }
        }

        // No. 6: cek radius
        $distance = $this->calculateDistance(
            (float) $request->att_lat,
            (float) $request->att_long,
            (float) $office->lat,
            (float) $office->long
        );
        if ($distance > $office->radius) {
            return redirect()->route('attendance.index')->with('error', 'Anda berada di luar radius presensi (' . $distance . ' m). Radius presensi adalah ' . $office->radius . ' m');
        }

        try {
            DB::transaction(function () use ($request, $employee, $schedule, $effectiveDate, $now_time) {
                // Lock baris yang sama supaya double-click / request bersamaan tidak menimpa
                $attendance = Attendance::where('employee_id', $employee->id)
                    ->where('date', $effectiveDate)
                    ->lockForUpdate()
                    ->first();

                // Cek duplikat sekali lagi di dalam lock
                if ($request->att_type == 'in' && $attendance && $attendance->check_in_time) {
                    throw new \RuntimeException('Anda sudah melakukan presensi masuk');
                }
                if ($request->att_type == 'out' && $attendance && $attendance->check_out_time) {
                    throw new \RuntimeException('Anda sudah melakukan presensi keluar');
                }

                if (! $attendance) {
                    $attendance = new Attendance();
                }

                $attendance->employee_id = $employee->id;
                $attendance->office_id = $employee->office_id;
                $attendance->schedule_id = $schedule->id;
                $attendance->date = $effectiveDate;
                $attendance->time_in = $schedule->shift->time_in;
                $attendance->time_out = $schedule->shift->time_out;

                if ($request->att_type == 'in') {
                    $attendance->check_in_time = $now_time->format('H:i:s');
                    $attendance->check_in_lat = $request->att_lat;
                    $attendance->check_in_long = $request->att_long;
                    $attendance->check_in_address = $request->att_address;
                } else {
                    $attendance->check_out_time = $now_time->format('H:i:s');
                    $attendance->check_out_lat = $request->att_lat;
                    $attendance->check_out_long = $request->att_long;
                    $attendance->check_out_address = $request->att_address;
                }

                $attendance->save();
            });

            return redirect()->route('attendance.index')->with('success', 'Berhasil presensi');
        } catch (\RuntimeException $e) {
            // Duplikat dari dalam transaksi: tampilkan pesannya, bukan error generik
            return redirect()->route('attendance.index')->with('error', $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Attendance store failed', [
                'employee_id' => $employee->id,
                'schedule_id' => $request->schedule_id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('attendance.index')->with('error', 'Terjadi kesalahan saat presensi, silakan coba lagi');
        }
    }

    public function history()
    {
        $employee = Auth::user()->employee;
        $attendances = Attendance::with('schedule.shift')
            ->where('employee_id', $employee->id)
            ->where('is_on_leave', false)
            ->orderBy('date', 'desc')
            ->limit(7)
            ->get();

        return response()->json([
            'status' => $attendances->count() > 0 ? 'success' : 'not found',
            'data' => $attendances
        ]);
    }

    public function schedule()
    {
        $employee = Auth::user()->employee;

        $schedules = [];
        if ($employee->category == 'regular') {
            foreach (Carbon::now()->addDays(1)->daysUntil(Carbon::now()->addDays(7)) as $date) {
                $schedules[$date->format('Y-m-d')] = Schedule::with('shift')->where('employee_id', $employee->id)
                    ->where('is_recurring', true)
                    ->where('day_of_week', Str::lower($date->format('l')))
                    ->first();
            }
        }
        if ($employee->category == 'shift') {
            foreach (Carbon::now()->addDays(1)->daysUntil(Carbon::now()->addDays(7)) as $date) {
                $schedules[$date->format('Y-m-d')] = Schedule::with('shift')->where('employee_id', $employee->id)
                    ->where('is_recurring', false)
                    ->where('date', $date->format('Y-m-d'))
                    ->first();
            }
        }

        // check holiday
        $holidays = Holiday::where('date', '>=', Carbon::now()->addDays(1)->format('Y-m-d'))
            ->where('date', '<=', Carbon::now()->addDays(7)->format('Y-m-d'))
            ->where('is_day_off', true)
            ->get();

        foreach ($holidays as $holiday) {
            if ($holiday->is_day_off && $employee->category == 'regular') {
                $schedules[$holiday->date] = $holiday;
            }
        }

        // check leave
        $leaves = Attendance::where('employee_id', $employee->id)
            ->where('date', '>=', Carbon::now()->addDays(1)->format('Y-m-d'))
            ->where('date', '<=', Carbon::now()->addDays(7)->format('Y-m-d'))
            ->where('is_on_leave', true)
            ->get();
        foreach ($leaves as $leave) {
            $schedules[$leave->date] = $leave;
        }

        // refomat data
        $output = [];
        foreach ($schedules as $key => $schedule) {
            if ($schedule == null || $schedule->is_day_off) {
                $output[] = (object) [
                    'date' => $key,
                    'date_formatted' => Carbon::parse($key)->translatedFormat('l, d F Y'),
                    'date_next_formatted' => Carbon::parse($key)->addDay()->translatedFormat('d-m-Y'),
                    'is_next_day' => $schedule->shift->is_next_day ?? false,
                    'title' => 'Tidak ada jadwal (Libur)',
                    'time_in' => '--:--',
                    'time_out' => '--:--',
                    'shift_type' => 'Libur',
                    'color' => 'primary-subtle text-primary'
                ];
            } else if ($schedule && $schedule->is_on_leave) {
                $output[] = (object) [
                    'date' => $key,
                    'date_formatted' => Carbon::parse($key)->translatedFormat('l, d F Y'),
                    'date_next_formatted' => Carbon::parse($key)->addDay()->translatedFormat('d-m-Y'),
                    'is_next_day' => $schedule->shift->is_next_day ?? false,
                    'title' => 'Cuti',
                    'time_in' => '--:--',
                    'time_out' => '--:--',
                    'shift_type' => 'Cuti',
                    'color' => 'warning'
                ];
            } else if ($schedule) {
                $output[] = (object) [
                    'date' => $key,
                    'date_formatted' => Carbon::parse($key)->translatedFormat('l, d F Y'),
                    'date_next_formatted' => Carbon::parse($key)->addDay()->translatedFormat('d-m-Y'),
                    'is_next_day' => $schedule->shift->is_next_day ?? false,
                    'title' => $schedule->shift->name,
                    'time_in' => $schedule->shift->time_in,
                    'time_out' => $schedule->shift->time_out,
                    'shift_type' => $schedule->is_recurring ? 'Reguler' : 'Shift',
                    'color' => 'primary'
                ];
            }
        }

        return response()->json([
            'status' => count($output) > 0 ? 'success' : 'not found',
            'data' => $output
        ]);
    }

    protected function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // in meters

        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        $dLat = $lat2 - $lat1;
        $dLon = $lon2 - $lon1;

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos($lat1) * cos($lat2) *
            sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c); // distance in meters
    }
}
