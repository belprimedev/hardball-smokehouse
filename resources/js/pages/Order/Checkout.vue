<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import { Textarea } from '@/components/ui/textarea';
import { Card, CardContent, CardHeader, CardTitle, CardDescription, CardFooter } from '@/components/ui/card';
import { Separator } from '@/components/ui/separator';
import { Loader2, MapPin, Truck, Package, CheckCircle } from 'lucide-vue-next';
import { useCartStore } from '@/stores/cart';
import { useToast } from '@/components/ui/toast/use-toast';

interface CartItem {
    id: number;
    menu_item_id: number;
    name: string;
    price: number;
    quantity: number;
    subtotal: number;
    image_path?: string;
    special_instructions?: string;
}

const cartStore = useCartStore();
const { toast } = useToast();

const cart = computed(() => cartStore.items);
const cartTotal = computed(() => cartStore.total);

const fulfillmentType = ref<'delivery' | 'pickup'>('pickup');
const isProcessing = ref(false);
const isLoading = ref(true);
const step = ref<'details' | 'payment' | 'success'>('details');
const orderNumber = ref('');

const form = ref({
    customer_name: '',
    customer_email: '',
    customer_phone: '',
    delivery_address: '',
    delivery_postcode: '',
    special_instructions: '',
});

onMounted(() => {
    cartStore.fetchCart();
    isLoading.value = false;
});

const goToPayment = () => {
    if (!form.value.customer_name || !form.value.customer_email) {
        toast({
            title: 'Missing Information',
            description: 'Please fill in all required fields',
            variant: 'destructive',
        });
        return;
    }
    step.value = 'payment';
};

const handleSubmit = async () => {
    isProcessing.value = true;

    try {
        const response = await fetch('/api/orders', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                ...form.value,
                fulfillment_type: fulfillmentType.value,
            }),
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Failed to create order');
        }

        orderNumber.value = data.order.order_number;
        cartStore.clearCart();
        step.value = 'success';

        toast({
            title: 'Order Placed!',
            description: `Order ${orderNumber.value} confirmed`,
        });
    } catch (error: any) {
        toast({
            title: 'Order Failed',
            description: error.message || 'Please try again',
            variant: 'destructive',
        });
    } finally {
        isProcessing.value = false;
    }
};

const goHome = () => {
    router.visit('/');
};
</script>

<template>
    <AppLayout>
        <Head title="Checkout" />

        <div class="container mx-auto py-8 px-4">
            <h1 class="text-3xl font-bold mb-8">Checkout</h1>

            <!-- Step 1: Customer Details -->
            <div v-if="step === 'details'" class="grid lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6">
                    <!-- Fulfillment Type -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Collection Options</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <RadioGroup v-model="fulfillmentType" class="grid grid-cols-2 gap-4">
                                <div class="border rounded-lg p-4 cursor-pointer hover:bg-accent"
                                    :class="{ 'border-primary bg-primary/5': fulfillmentType === 'pickup' }"
                                    @click="fulfillmentType = 'pickup'">
                                    <RadioGroupItem value="pickup" id="pickup" class="sr-only" />
                                    <Label for="pickup" class="flex flex-col items-center gap-2 cursor-pointer">
                                        <Package class="w-6 h-6" />
                                        <span class="font-medium">Pickup</span>
                                        <span class="text-sm text-muted-foreground">Collect from restaurant</span>
                                    </Label>
                                </div>
                                <div class="border rounded-lg p-4 cursor-pointer hover:bg-accent opacity-50">
                                    <RadioGroupItem value="delivery" id="delivery" class="sr-only" disabled />
                                    <Label for="delivery" class="flex flex-col items-center gap-2 cursor-not-allowed">
                                        <Truck class="w-6 h-6" />
                                        <span class="font-medium">Delivery</span>
                                        <span class="text-sm text-muted-foreground">Coming soon</span>
                                    </Label>
                                </div>
                            </RadioGroup>
                        </CardContent>
                    </Card>

                    <!-- Customer Details -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Your Details</CardTitle>
                            <CardDescription>We'll send order updates to this email</CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="grid sm:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <Label for="name">Full Name *</Label>
                                    <Input id="name" v-model="form.customer_name" placeholder="John Doe" />
                                </div>
                                <div class="space-y-2">
                                    <Label for="phone">Phone Number</Label>
                                    <Input id="phone" v-model="form.customer_phone" placeholder="+44 7123 456789" />
                                </div>
                            </div>
                            <div class="space-y-2">
                                <Label for="email">Email Address *</Label>
                                <Input id="email" type="email" v-model="form.customer_email" placeholder="john@example.com" />
                            </div>

                            <Separator />
                            <div class="space-y-2">
                                <Label for="instructions">Special Instructions</Label>
                                <Textarea id="instructions" v-model="form.special_instructions"
                                    placeholder="Any allergies or special requests?" rows="2" />
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Order Summary -->
                <div>
                    <Card class="sticky top-4">
                        <CardHeader>
                            <CardTitle>Order Summary</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div v-if="cart.length === 0" class="text-center py-8 text-muted-foreground">
                                Your cart is empty
                            </div>
                            <div v-else class="space-y-4">
                                <div v-for="item in cart" :key="item.id" class="flex gap-4">
                                    <img v-if="item.image_path" :src="item.image_path" :alt="item.name"
                                        class="w-16 h-16 object-cover rounded" />
                                    <div class="flex-1">
                                        <p class="font-medium">{{ item.name }}</p>
                                        <p class="text-sm text-muted-foreground">Qty: {{ item.quantity }}</p>
                                    </div>
                                    <p class="font-medium">£{{ item.subtotal.toFixed(2) }}</p>
                                </div>

                                <Separator />

                                <div class="flex justify-between text-sm">
                                    <span>Subtotal</span>
                                    <span>£{{ cartTotal.toFixed(2) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span>Collection</span>
                                    <span>Free</span>
                                </div>
                                <Separator />
                                <div class="flex justify-between font-bold text-lg">
                                    <span>Total</span>
                                    <span>£{{ cartTotal.toFixed(2) }}</span>
                                </div>
                            </div>
                        </CardContent>
                        <CardFooter>
                            <Button class="w-full" size="lg" @click="goToPayment" :disabled="cart.length === 0">
                                Place Order
                            </Button>
                        </CardFooter>
                    </Card>
                </div>
            </div>

            <!-- Step 2: Confirmation -->
            <div v-if="step === 'payment'" class="max-w-xl mx-auto">
                <Card>
                    <CardHeader>
                        <CardTitle>Confirm Your Order</CardTitle>
                        <CardDescription>Review your order details before confirming</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="p-4 border rounded-lg bg-muted">
                            <p class="font-medium mb-2">Order Total</p>
                            <p class="text-2xl font-bold">£{{ cartTotal.toFixed(2) }}</p>
                        </div>

                        <div class="space-y-2 text-sm">
                            <p><strong>Name:</strong> {{ form.customer_name }}</p>
                            <p><strong>Email:</strong> {{ form.customer_email }}</p>
                            <p v-if="form.customer_phone"><strong>Phone:</strong> {{ form.customer_phone }}</p>
                            <p><strong>Collection:</strong> Pickup from restaurant</p>
                        </div>
                    </CardContent>
                    <CardFooter class="flex gap-4">
                        <Button variant="outline" @click="step = 'details'">Back</Button>
                        <Button class="flex-1" size="lg" @click="handleSubmit" :disabled="isProcessing">
                            <Loader2 v-if="isProcessing" class="w-4 h-4 mr-2 animate-spin" />
                            {{ isProcessing ? 'Processing...' : 'Confirm Order' }}
                        </Button>
                    </CardFooter>
                </Card>
            </div>

            <!-- Step 3: Success -->
            <div v-if="step === 'success'" class="max-w-md mx-auto text-center py-12">
                <CheckCircle class="w-20 h-20 text-green-500 mx-auto mb-6" />
                <h2 class="text-2xl font-bold mb-2">Order Confirmed!</h2>
                <p class="text-muted-foreground mb-6">Thank you for your order. We'll start preparing it right away.</p>
                <p class="font-medium mb-2">Order Number</p>
                <p class="text-xl font-bold text-primary mb-8">{{ orderNumber }}</p>
                <Button @click="goHome">Continue Shopping</Button>
            </div>
        </div>
    </AppLayout>
</template>
