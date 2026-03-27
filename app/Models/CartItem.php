<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'menu_item_id',
        'quantity',
        'special_instructions',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class);
    }

    public function getSubtotalAttribute(): float
    {
        return $this->quantity * ($this->menuItem?->price ?? 0);
    }

    public static function getOrCreateSessionId(): string
    {
        $sessionId = session('cart_session_id');
        if (!$sessionId) {
            $sessionId = bin2hex(random_bytes(16));
            session(['cart_session_id' => $sessionId]);
        }
        return $sessionId;
    }
}
