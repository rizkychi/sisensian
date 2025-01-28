<?php

namespace App\Models;

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

    protected $casts = [
        'is_on_leave' => 'boolean',
        'date' => 'date',
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
}
