<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    public static function defaultLeaveTypes()
    {
        return [
            ['CT' => 'Cuti Tahunan'],
            ['CS' => 'Cuti Sakit'],
            ['OTHER' => 'Lainnya'],
        ];
    }
}
