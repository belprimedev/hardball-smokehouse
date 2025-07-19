<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    protected $casts = [
        'reservation_date' => 'date',
        'reservation_time' => 'string',
    ];
    
    public function formattedDate(): Attribute
    {
        return Attribute::get(fn() => $this->reservation_date->format('d M Y'));
    }
}
