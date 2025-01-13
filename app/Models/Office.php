<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    protected $table = 'office';

    protected $fillable = [
        'name',
        'address',
        'description',
        'lat',
        'long',
        'radius',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
