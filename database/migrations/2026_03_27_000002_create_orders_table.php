<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('delivery_zone_id')->nullable()->constrained()->nullOnDelete();

            // Customer info (for guest checkout)
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone', 20)->nullable();

            // Delivery / pickup
            $table->enum('fulfillment_type', ['delivery', 'pickup'])->default('pickup');
            $table->text('delivery_address')->nullable();
            $table->string('delivery_postcode', 10)->nullable();

            // Pricing
            $table->decimal('subtotal', 10, 2);
            $table->decimal('delivery_fee', 8, 2)->default(0.00);
            $table->decimal('total', 10, 2);

            // Payment
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->string('stripe_payment_intent_id')->nullable();
            $table->string('stripe_charge_id')->nullable();

            // Order lifecycle
            $table->enum('status', ['pending', 'confirmed', 'preparing', 'ready', 'out_for_delivery', 'completed', 'cancelled'])->default('pending');
            $table->text('special_instructions')->nullable();
            $table->timestamp('estimated_ready_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index('customer_email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
