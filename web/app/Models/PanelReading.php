<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PanelReading extends Model
{
    use HasFactory;

    protected $tabel = 'panel_readings';

    protected $fillable = [
        'panel_id',
        'data_id',
        'voltage',
        'current',
        'power',
        'dust',
        'recorded_at'
    ];

    public function panel()
    {
        return $this->belongsTo(Panel::class);
    }
}
