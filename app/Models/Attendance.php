<?php

namespace App\Models;

use App\Casts\DateCast;
use App\Casts\TimeCast;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendance';

    protected $fillable = [
        'employee_id',
        'schedule_id',
        'office_id',
        'date',
        'time_in',
        'time_out',
        'check_in_time',
        'check_out_time',
        'check_in_lat',
        'check_in_long',
        'check_in_address',
        'check_out_lat',
        'check_out_long',
        'check_out_address',
        'note',
        'status',
        'is_on_leave',
    ];

    protected $appends = [
        'date_formatted',
        'shift_type',
        'check_in_status',
        'check_out_status',
        'check_in_color',
        'check_out_color',
    ];

    protected $casts = [
        'is_on_leave' => 'boolean',
        'date' => DateCast::class,
        'time_in' => TimeCast::class,
        'time_out' => TimeCast::class,
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function office()
    {
        return $this->belongsTo(Office::class, 'office_id');
    }

    public function getDateFormattedAttribute()
    {
        return \Carbon\Carbon::parse($this->date)->translatedFormat('l, d F Y');
    }

    public function getCheckInStatusAttribute()
    {
        if (!$this->check_in_time) {
            return 'Belum Presensi';
        }

        // tolerance 1 minute
        if (\Carbon\Carbon::parse($this->check_in_time) <= \Carbon\Carbon::parse($this->time_in)->addMinutes(1)) {
            return 'Tepat Waktu';
        } elseif (\Carbon\Carbon::parse($this->check_in_time) > \Carbon\Carbon::parse($this->time_in)->addMinutes(1)) {
            return 'Terlambat';
        }
    }

    public function getCheckOutStatusAttribute()
    {
        if (!$this->check_out_time) {
            return 'Belum Presensi';
        }

        // tolerance 1 minute
        if (\Carbon\Carbon::parse($this->check_out_time) >= \Carbon\Carbon::parse($this->time_out)->subMinutes(1)) {
            return 'Tepat Waktu';
        } elseif (\Carbon\Carbon::parse($this->check_out_time) < \Carbon\Carbon::parse($this->time_out)->subMinutes(1)) {
            return 'Pulang Sebelum Waktu';
        }
    }

    public function getShiftTypeAttribute()
    {
        if ($this->schedule_id) {
            $schedule = Schedule::findOrFail($this->schedule_id);
            return $schedule->is_recurring ? 'Reguler' : 'Shift';
        }
        return null;
    }

    public function getCheckInColorAttribute()
    {
        if (!$this->check_in_time) {
            return 'text-muted';
        }

        // tolerance 1 minute
        if (\Carbon\Carbon::parse($this->check_in_time) <= \Carbon\Carbon::parse($this->time_in)->addMinutes(1)) {
            return 'text-success';
        } elseif (\Carbon\Carbon::parse($this->check_in_time) > \Carbon\Carbon::parse($this->time_in)->addMinutes(1)) {
            return 'text-danger';
        }
    }

    public function getCheckOutColorAttribute()
    {
        if (!$this->check_out_time) {
            return 'text-muted';
        }

        // tolerance 1 minute
        if (\Carbon\Carbon::parse($this->check_out_time) >= \Carbon\Carbon::parse($this->time_out)->subMinutes(1)) {
            return 'text-success';
        } elseif (\Carbon\Carbon::parse($this->check_out_time) < \Carbon\Carbon::parse($this->time_out)->subMinutes(1)) {
            return 'text-danger';
        }
    }
}
