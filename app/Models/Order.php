<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'delivery_zone_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'fulfillment_type',
        'delivery_address',
        'delivery_postcode',
        'subtotal',
        'delivery_fee',
        'total',
        'payment_status',
        'stripe_payment_intent_id',
        'stripe_charge_id',
        'status',
        'special_instructions',
        'estimated_ready_at',
        'completed_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'total' => 'decimal:2',
        'estimated_ready_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public static function generateOrderNumber(): string
    {
        $date = now()->format('ymd');
        $lastOrder = self::whereDate('created_at', today())->latest()->first();
        $sequence = $lastOrder ? (int)substr($lastOrder->order_number, -4) + 1 : 1;
        return "HBS-{$date}-" . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($order) {
            if (!$order->order_number) {
                $order->order_number = self::generateOrderNumber();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function deliveryZone(): BelongsTo
    {
        return $this->belongsTo(DeliveryZone::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    public function getStatusDisplayAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'preparing' => 'Preparing',
            'ready' => 'Ready for ' . ($this->fulfillment_type === 'delivery' ? 'Pickup' : 'Collection'),
            'out_for_delivery' => 'Out for Delivery',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            default => ucfirst($this->status),
        };
    }

    public function calculateTotals(): void
    {
        $this->subtotal = $this->items->sum('subtotal');
        $this->delivery_fee = $this->fulfillment_type === 'delivery' && $this->deliveryZone
            ? $this->deliveryZone->delivery_fee
            : 0.00;
        $this->total = $this->subtotal + $this->delivery_fee;
    }
}
