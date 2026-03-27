<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\MenuItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $items = $this->getCartItems($request);

        return response()->json([
            'items' => $items->map(fn($item) => [
                'id' => $item->id,
                'menu_item_id' => $item->menu_item_id,
                'name' => $item->menuItem->name,
                'price' => $item->menuItem->price,
                'quantity' => $item->quantity,
                'subtotal' => $item->subtotal,
                'image_path' => $item->menuItem->image_path,
                'special_instructions' => $item->special_instructions,
                'available' => $item->menuItem->is_available && $item->menuItem->is_visible,
            ]),
            'total' => $items->sum('subtotal'),
            'count' => $items->sum('quantity'),
        ]);
    }

    public function add(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'menu_item_id' => 'required|exists:menu_items,id',
            'quantity' => 'required|integer|min:1|max:20',
            'special_instructions' => 'nullable|string|max:500',
        ]);

        $menuItem = MenuItem::findOrFail($validated['menu_item_id']);

        if (!$menuItem->is_available || !$menuItem->is_visible) {
            return response()->json(['message' => 'This item is not available'], 422);
        }

        $user = $request->user();
        $sessionId = $user ? null : CartItem::getOrCreateSessionId();

        $existingItem = CartItem::where('menu_item_id', $menuItem->id)
            ->when($user, fn($q) => $q->where('user_id', $user->id))
            ->when(!$user, fn($q) => $q->where('session_id', $sessionId))
            ->first();

        if ($existingItem) {
            $existingItem->quantity = min($existingItem->quantity + $validated['quantity'], 20);
            $existingItem->save();
        } else {
            CartItem::create([
                'user_id' => $user?->id,
                'session_id' => $sessionId,
                'menu_item_id' => $menuItem->id,
                'quantity' => $validated['quantity'],
                'special_instructions' => $validated['special_instructions'] ?? null,
            ]);
        }

        return response()->json(['message' => 'Item added to cart']);
    }

    public function update(Request $request, CartItem $item): JsonResponse
    {
        if (!$this->ownsCartItem($request, $item)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'quantity' => 'required|integer|min:0|max:20',
        ]);

        if ($validated['quantity'] === 0) {
            $item->delete();
        } else {
            $item->quantity = $validated['quantity'];
            $item->save();
        }

        return response()->json(['message' => 'Cart updated']);
    }

    public function remove(Request $request, CartItem $item): JsonResponse
    {
        if (!$this->ownsCartItem($request, $item)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $item->delete();
        return response()->json(['message' => 'Item removed']);
    }

    public function clear(Request $request): JsonResponse
    {
        $this->getCartQuery($request)->delete();
        return response()->json(['message' => 'Cart cleared']);
    }

    public function transferSessionToUser(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $sessionId = session('cart_session_id');
        if ($sessionId) {
            CartItem::where('session_id', $sessionId)
                ->whereNull('user_id')
                ->update(['user_id' => $user->id, 'session_id' => null]);
            session()->forget('cart_session_id');
        }

        return response()->json(['message' => 'Cart transferred']);
    }

    private function getCartItems(Request $request)
    {
        return $this->getCartQuery($request)
            ->with('menuItem.category')
            ->get();
    }

    private function getCartQuery(Request $request)
    {
        $user = $request->user();
        $sessionId = $user ? null : CartItem::getOrCreateSessionId();

        return CartItem::query()
            ->when($user, fn($q) => $q->where('user_id', $user->id))
            ->when(!$user, fn($q) => $q->where('session_id', $sessionId));
    }

    private function ownsCartItem(Request $request, CartItem $item): bool
    {
        $user = $request->user();
        $sessionId = session('cart_session_id');

        return ($user && $item->user_id === $user->id) ||
            (!$user && $item->session_id === $sessionId);
    }
}
