<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_name',
        'business_email',
        'contact_number',
        'address',
        'operation_hours',
        'website',
        'description',
    ];

    /**
     * Get the first (and only) general setting record
     */
    public static function getSettings()
    {
        return self::first() ?? self::create([
            'business_name' => 'Hardball Smokehouse',
            'business_email' => 'info@hardballsmokehouse.com.uk',
            'contact_number' => '07398 951462',
            'address' => '24 Lloyds Ave, Ipswich IP1 3HD, United Kingdom',
            'operation_hours' => 'Monday - Sunday: 1:00 PM - 10:00 PM',
            'website' => 'https://hardballsmokehouse.com.uk',
            'description' => 'Authentic Southern BBQ and craft cocktails in the heart of Ipswich.',
        ]);
    }
}
