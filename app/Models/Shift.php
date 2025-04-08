<?php

namespace App\Models;

use App\Casts\TimeCast;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $table = 'shift';
    
    protected $fillable = [
        'name',
        'time_in',
        'time_out',
        // 'is_night_shift',
        'is_fixed',
        'description',
    ];

    protected $casts = [
        'time_in' => TimeCast::class,
        'time_out' => TimeCast::class,
        // 'is_night_shift' => 'boolean',
        'is_fixed' => 'boolean',
    ];

    protected $appends = [
        'is_next_day',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
    
    public function getIsNextDayAttribute()
    {
        return $this->time_in > $this->time_out;
    }
}
