<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AttendanceType extends Model
{
    static $late = [
        'TL1' => 'Terlambat 1 menit sampai 30 menit',
        'TL2' => 'Terlambat 31 menit sampai 60 menit',
        'TL3' => 'Terlambat 61 menit sampai 90 menit',
        'TL4' => 'Terlambat 91 menit sampai 120 menit',
        'TL5' => 'Terlambat lebih dari 120 menit',
    ];

    static $early = [
        'PSW1' => 'Pulang sebelum waktu 1 menit sampai 30 menit',
        'PSW2' => 'Pulang sebelum waktu 31 menit sampai 60 menit',
        'PSW3' => 'Pulang sebelum waktu 61 menit sampai 90 menit',
        'PSW4' => 'Pulang sebelum waktu 91 menit sampai 120 menit',
        'PSW5' => 'Pulang sebelum waktu lebih dari 120 menit',
    ];

    static $absent = [
        'AB' => 'Tidak Hadir',
        'TPM' => 'Tidak Presensi Masuk',
        'TPP' => 'Tidak Presensi Pulang',
    ];

    /**
     * Get all attendance types.
     */
    public static function getAll()
    {
        $types = array_merge(self::$late, self::$early, self::$absent);
        return $types;
    }

    /**
     * Get late attendance.
     */
    public static function getLate()
    {
        return self::$late;
    }

    /**
     * Get early attendance.
     */
    public static function getEarly()
    {
        return self::$early;
    }

    /**
     * Get absent attendance.
     */
    public static function getAbsent()
    {
        return self::$absent;
    }

    /**
     * Get a attendance type by slug.
     */
    public static function getName(string $slug)
    {
        $types = array_merge(self::$late, self::$early, self::$absent);
        return $types[$slug] ?? null;
    }

    /**
     * Determine type by time.
     */
    public static function getTypeByTime($time, $time_check)
    {
        $time = Carbon::parse($time);
        $time_check = Carbon::parse($time_check);

        $diffInMinutes = $time->diffInMinutes($time_check, false);

        if ($diffInMinutes > 0) {
            foreach (self::$late as $key => $description) {
                if ($diffInMinutes <= 30 && $key == 'TL1') return $key;
                if ($diffInMinutes <= 60 && $key == 'TL2') return $key;
                if ($diffInMinutes <= 90 && $key == 'TL3') return $key;
                if ($diffInMinutes <= 120 && $key == 'TL4') return $key;
                if ($diffInMinutes > 120 && $key == 'TL5') return $key;
            }
        } elseif ($diffInMinutes < 0) {
            $diffInMinutes = abs($diffInMinutes);
            foreach (self::$early as $key => $description) {
                if ($diffInMinutes <= 30 && $key == 'PSW1') return $key;
                if ($diffInMinutes <= 60 && $key == 'PSW2') return $key;
                if ($diffInMinutes <= 90 && $key == 'PSW3') return $key;
                if ($diffInMinutes <= 120 && $key == 'PSW4') return $key;
                if ($diffInMinutes > 120 && $key == 'PSW5') return $key;
            }
        }

        return null;
    }
}
