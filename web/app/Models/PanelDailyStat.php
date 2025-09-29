<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PanelDailyStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'panel_id',
        'date',
        'hour',
        'avg_voltage',
        'avg_current',
        'avg_power',
        'avg_dust'
    ];

    public function panel()
    {
        return $this->belongsTo(Panel::class);
    }
}
