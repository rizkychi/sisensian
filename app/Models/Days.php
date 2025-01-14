<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Days extends Model
{
    static $days = [
        'monday' => 'Senin',
        'tuesday' => 'Selasa',
        'wednesday' => 'Rabu',
        'thursday' => 'Kamis',
        'friday' => 'Jumat',
        'saturday' => 'Sabtu',
        'sunday' => 'Minggu',
    ];

    /**
     * Get all days.
     */
    public static function getAll()
    {
        return self::$days;
    }

    /**
     * Get a day by slug.
     */
    public static function getName(string $slug)
    {
        return self::$days[$slug];
    }
}
