<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Panel extends Model
{
    use HasFactory;

    protected $fillable = [
        'panel_id',
        'unit_id',
        'current',
        'voltage',
        'power',
        'rain_status',
        'wiper_status',
        'last_cleaning',
        'installed_at',
        'updated_at',
    ];
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'unit_id');
    }
}
