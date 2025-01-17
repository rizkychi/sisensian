<?php

namespace App\Models;

use App\Casts\DateCast;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $table = 'leave';

    protected $fillable = [
        'employee_id',
        'start_date',
        'end_date',
        'leave_type',
        'reason',
        'status',
        'confirmed_by',
        'confirmed_at',
        'note',
    ];

    protected $casts = [
        'start_date' => DateCast::class,
        'end_date' => DateCast::class,
        'confirmed_at' => DateCast::class,
        'created_at' => DateCast::class,
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function confirmedBy()
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }
}
