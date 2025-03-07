<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'employee';

    protected $fillable = [
        'user_id',
        'id_number',
        'name',
        'address',
        'phone',
        'position',
        'office_id',
        'is_active',
        'photo',
        'category',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedule()
    {
        return $this->hasMany(Schedule::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    public function leave()
    {
        return $this->hasMany(Leave::class);
    }
}
