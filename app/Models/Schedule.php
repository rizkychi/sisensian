<?php

namespace App\Models;

use App\Casts\DateCast;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = 'schedule';
    
    protected $fillable = [
        'employee_id',
        'shift_id',
        'date',
        'is_recurring',
        'day_of_week',
    ];

    protected $casts = [
        'is_recurring' => 'boolean',
        'date' => DateCast::class,
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}
