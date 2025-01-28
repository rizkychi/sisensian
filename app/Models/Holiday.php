<?php

namespace App\Models;

use App\Casts\DateCast;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $table = 'holiday';
    protected $fillable = ['date', 'name', 'is_day_off'];
    protected $hidden = ['created_at', 'updated_at'];

    protected $casts = [
        'date' => DateCast::class,
        'is_day_off' => 'boolean',
    ];
}
