<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliveryZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'postcode_prefix',
        'delivery_fee',
        'minimum_order',
        'estimated_minutes',
        'is_active',
    ];

    protected $casts = [
        'delivery_fee' => 'decimal:2',
        'minimum_order' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public static function findByPostcode(string $postcode): ?self
    {
        $prefix = strtoupper(substr(str_replace(' ', '', $postcode), 0, 2));
        return self::where('postcode_prefix', $prefix)
            ->where('is_active', true)
            ->first();
    }
}
