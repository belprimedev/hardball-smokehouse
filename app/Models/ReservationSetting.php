<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReservationSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'day_of_week',
        'opening_time',
        'closing_time',
        'max_capacity_per_hour',
        'is_open'
    ];

    protected $casts = [
        'opening_time' => 'string',
        'closing_time' => 'string',
        'is_open' => 'boolean',
    ];

    public static function getSettingsForDay($dayOfWeek)
    {
        return static::where('day_of_week', strtolower($dayOfWeek))->first();
    }

    public function getAvailableHours()
    {
        if (!$this->is_open) {
            return [];
        }

        $hours = [];
        $current = strtotime($this->opening_time);
        $closing = strtotime($this->closing_time);

        while ($current < $closing) {
            $hours[] = date('H:i', $current);
            $current = strtotime('+1 hour', $current);
        }

        return $hours;
    }
}
