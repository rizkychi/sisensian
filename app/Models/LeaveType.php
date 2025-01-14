<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    static $types = [
        'CS' => 'Cuti Sakit',
        'CT' => 'Cuti Tahunan',
        'CN' => 'Cuti Menikah',
        'CM' => 'Cuti Melahirkan',
        'OTHER' => 'Lainnya',
    ];

    /**
     * Get all leave types.
     */
    public static function getAll()
    {
        return self::$types;
    }

    /**
     * Get a leave type by slug.
     */
    public static function getName(string $slug)
    {
        return self::$types[$slug];
    }
}
