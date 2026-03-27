<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\CartItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class CheckoutController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret_key'));
    }

    public function createPaymentIntent(Request $request): JsonResponse
    {
        $user = $request->user();
        $sessionId = $user ? null : CartItem::getOrCreateSessionId();

        $cartItems = CartItem::with('menuItem')
            ->when($user, fn($q) => $q->where('user_id', $user->id))
            ->when(!$user, fn($q) => $q->where('session_id', $sessionId))
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 422);
        }

        $amount = (int) round($cartItems->sum(fn($item) => $item->quantity * $item->menuItem->price) * 100);

        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $amount,
                'currency' => 'gbp',
                'automatic_payment_methods' => ['enabled' => true],
                'metadata' => [
                    'user_id' => $user?->id ?? 'guest',
                    'session_id' => $sessionId ?? '',
                ],
            ]);

            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
                'paymentIntentId' => $paymentIntent->id,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create payment intent'], 500);
        }
    }

    public function webhook(Request $request): JsonResponse
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid webhook signature'], 400);
        }

        switch ($event->type) {
            case 'payment_intent.succeeded':
                $this->handlePaymentSuccess($event->data->object);
                break;

            case 'payment_intent.payment_failed':
                $this->handlePaymentFailure($event->data->object);
                break;
        }

        return response()->json(['status' => 'success']);
    }

    private function handlePaymentSuccess($paymentIntent): void
    {
        $order = Order::where('stripe_payment_intent_id', $paymentIntent->id)->first();
        if ($order) {
            $order->update([
                'payment_status' => 'paid',
                'stripe_charge_id' => $paymentIntent->charges->data[0]->id ?? null,
                'status' => 'confirmed',
            ]);

            event(new \App\Events\OrderStatusUpdated($order));
        }
    }

    private function handlePaymentFailure($paymentIntent): void
    {
        $order = Order::where('stripe_payment_intent_id', $paymentIntent->id)->first();
        if ($order) {
            $order->update([
                'payment_status' => 'failed',
            ]);
        }
    }

    public function config(): JsonResponse
    {
        return response()->json([
            'stripePublicKey' => config('services.stripe.public_key'),
            'currency' => 'gbp',
            'currencySymbol' => '£',
        ]);
    }
}
