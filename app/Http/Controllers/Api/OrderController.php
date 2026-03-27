<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use App\Models\MenuItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $orders = Order::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return response()->json($orders);
    }

    public function show(Request $request, Order $order): JsonResponse
    {
        $user = $request->user();

        if (!$user && session('cart_session_id')) {
            // Guest viewing their order via session
        } elseif ($user && $order->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'order' => $order->load('items.menuItem'),
            'status_display' => $order->status_display,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'fulfillment_type' => 'required|in:delivery,pickup',
            'delivery_address' => 'required_if:fulfillment_type,delivery|string|max:500',
            'delivery_postcode' => 'required_if:fulfillment_type,delivery|string|max:10',
            'special_instructions' => 'nullable|string|max:500',
        ]);

        $user = $request->user();
        $sessionId = $user ? null : CartItem::getOrCreateSessionId();

        $cartItems = CartItem::with('menuItem')
            ->when($user, fn($q) => $q->where('user_id', $user->id))
            ->when(!$user, fn($q) => $q->where('session_id', $sessionId))
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 422);
        }

        // Validate all items are still available
        foreach ($cartItems as $item) {
            if (!$item->menuItem->is_available || !$item->menuItem->is_visible) {
                return response()->json([
                    'message' => "{$item->menuItem->name} is no longer available",
                ], 422);
            }
        }

        try {
            $order = DB::transaction(function () use ($validated, $cartItems, $user) {
                $subtotal = $cartItems->sum(fn($item) => $item->quantity * $item->menuItem->price);
                $deliveryFee = $validated['fulfillment_type'] === 'delivery' ? 0.00 : 0.00;

                $order = Order::create([
                    'user_id' => $user?->id,
                    'customer_name' => $validated['customer_name'],
                    'customer_email' => $validated['customer_email'],
                    'customer_phone' => $validated['customer_phone'] ?? null,
                    'fulfillment_type' => $validated['fulfillment_type'],
                    'delivery_address' => $validated['delivery_address'] ?? null,
                    'delivery_postcode' => $validated['delivery_postcode'] ?? null,
                    'subtotal' => $subtotal,
                    'delivery_fee' => $deliveryFee,
                    'total' => $subtotal + $deliveryFee,
                    'special_instructions' => $validated['special_instructions'] ?? null,
                    'estimated_ready_at' => now()->addMinutes(45),
                ]);

                foreach ($cartItems as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'menu_item_id' => $item->menu_item_id,
                        'name' => $item->menuItem->name,
                        'price' => $item->menuItem->price,
                        'quantity' => $item->quantity,
                        'special_instructions' => $item->special_instructions,
                    ]);
                }

                // Clear cart
                $itemIds = $cartItems->pluck('id');
                CartItem::whereIn('id', $itemIds)->delete();

                return $order;
            });

            return response()->json([
                'message' => 'Order created successfully',
                'order' => $order->load('items'),
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create order'], 500);
        }
    }

    public function status(Order $order): JsonResponse
    {
        return response()->json([
            'order_number' => $order->order_number,
            'status' => $order->status,
            'status_display' => $order->status_display,
            'payment_status' => $order->payment_status,
            'estimated_ready_at' => $order->estimated_ready_at,
            'completed_at' => $order->completed_at,
        ]);
    }

    public function cancel(Request $request, Order $order): JsonResponse
    {
        if (!$order->canBeCancelled()) {
            return response()->json(['message' => 'Order cannot be cancelled'], 422);
        }

        $user = $request->user();
        if (!$user || $order->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $order->status = 'cancelled';
        $order->save();

        return response()->json(['message' => 'Order cancelled', 'order' => $order]);
    }

    public function updateStatus(Request $request, Order $order): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,out_for_delivery,completed,cancelled',
        ]);

        $order->status = $validated['status'];

        if ($validated['status'] === 'completed') {
            $order->completed_at = now();
        }

        $order->save();

        // Broadcast status update via Pusher
        event(new \App\Events\OrderStatusUpdated($order));

        return response()->json([
            'message' => 'Status updated',
            'order' => $order->fresh(),
        ]);
    }

    public function kitchenQueue(Request $request): JsonResponse
    {
        $orders = Order::with('items')
            ->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready'])
            ->whereNot('status', 'cancelled')
            ->where('created_at', '>=', now()->subDays(1))
            ->orderByRaw("FIELD(status, 'ready', 'preparing', 'confirmed', 'pending')")
            ->latest()
            ->get();

        return response()->json($orders);
    }
}
