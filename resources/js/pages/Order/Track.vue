<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Badge } from '@/components/ui/badge';
import {
    Clock,
    CheckCircle,
    ChefHat,
    Package,
    Truck,
    AlertCircle,
    Search,
} from 'lucide-vue-next';
import Pusher from 'pusher-js';

const props = defineProps<{
    order?: {
        id: number;
        order_number: string;
        status: string;
        estimated_ready_at?: string;
    };
}>();

const orderNumber = ref('');
const order = ref<any>(props.order || null);
const isLoading = ref(false);
const error = ref('');
let pusherChannel: any = null;

const statusSteps = [
    { status: 'pending', label: 'Order Received', icon: AlertCircle },
    { status: 'confirmed', label: 'Confirmed', icon: CheckCircle },
    { status: 'preparing', label: 'Preparing', icon: ChefHat },
    { status: 'ready', label: 'Ready', icon: Package },
    { status: 'completed', label: 'Completed', icon: CheckCircle },
];

const getStatusIndex = (status: string) => {
    return statusSteps.findIndex(s => s.status === status);
};

const fetchOrder = async () => {
    if (!orderNumber.value.trim()) return;

    isLoading.value = true;
    error.value = '';

    try {
        const response = await fetch(`/api/orders/${orderNumber.value}`);
        if (!response.ok) throw new Error('Order not found');

        const data = await response.json();
        order.value = data.order;
        setupRealtime();
    } catch (e) {
        error.value = 'Order not found. Please check your order number.';
    } finally {
        isLoading.value = false;
    }
};

const setupRealtime = () => {
    if (!order.value?.id) return;

    const pusherKey = (window as any).PUSHER_APP_KEY;
    if (!pusherKey) return;

    const pusher = new Pusher(pusherKey, {
        cluster: (window as any).PUSHER_CLUSTER || 'eu',
        forceTLS: true,
    });

    pusherChannel = pusher.subscribe(`order.${order.value.id}`);
    pusherChannel.bind('App\\Events\\OrderStatusUpdated', (data: any) => {
        order.value.status = data.status;
    });
};

onMounted(() => {
    if (order.value) {
        setupRealtime();
    }
});

onUnmounted(() => {
    if (pusherChannel) {
        pusherChannel.unsubscribe();
    }
});
</script>

<template>
    <AppLayout>
        <Head title="Track Order" />

        <div class="container mx-auto py-8 px-4 max-w-2xl">
            <h1 class="text-3xl font-bold mb-2">Track Your Order</h1>
            <p class="text-muted-foreground mb-8">Enter your order number to see the latest status</p>

            <!-- Search Form -->
            <Card class="mb-8">
                <CardContent class="pt-6">
                    <div class="flex gap-4">
                        <div class="flex-1">
                            <Label for="order-number" class="sr-only">Order Number</Label>
                            <Input
                                id="order-number"
                                v-model="orderNumber"
                                placeholder="Enter order number (e.g., HBS-260327-0001)"
                                @keyup.enter="fetchOrder"
                            />
                        </div>
                        <Button @click="fetchOrder" :disabled="isLoading">
                            <Search class="w-4 h-4 mr-2" />
                            {{ isLoading ? 'Searching...' : 'Track' }}
                        </Button>
                    </div>
                    <p v-if="error" class="text-red-500 text-sm mt-2">{{ error }}</p>
                </CardContent>
            </Card>

            <!-- Order Status -->
            <div v-if="order">
                <Card>
                    <CardHeader>
                        <div class="flex items-center justify-between">
                            <div>
                                <CardTitle>Order {{ order.order_number }}</CardTitle>
                                <CardDescription>
                                    {{ order.status === 'cancelled' ? 'Order Cancelled' : 'Live updates enabled' }}
                                </CardDescription>
                            </div>
                            <Badge :variant="order.status === 'completed' ? 'default' : 'secondary'" class="text-sm">
                                {{ order.status_display || order.status }}
                            </Badge>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <!-- Progress Steps -->
                        <div v-if="order.status !== 'cancelled'" class="relative">
                            <div class="flex justify-between mb-2">
                                <div
                                    v-for="(step, index) in statusSteps"
                                    :key="step.status"
                                    class="flex flex-col items-center"
                                    :class="{ 'opacity-50': getStatusIndex(order.status) < index }"
                                >
                                    <div
                                        class="w-10 h-10 rounded-full flex items-center justify-center mb-2"
                                        :class="{
                                            'bg-primary text-primary-foreground': getStatusIndex(order.status) >= index,
                                            'bg-muted': getStatusIndex(order.status) < index,
                                        }"
                                    >
                                        <component :is="step.icon" class="w-5 h-5" />
                                    </div>
                                    <span class="text-xs text-center hidden sm:block">{{ step.label }}</span>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="absolute top-5 left-0 right-0 h-1 bg-muted -z-10 mx-5">
                                <div
                                    class="h-full bg-primary transition-all duration-500"
                                    :style="{ width: `${(getStatusIndex(order.status) / (statusSteps.length - 1)) * 100}%` }"
                                ></div>
                            </div>
                        </div>

                        <!-- Cancelled State -->
                        <div v-else class="text-center py-8">
                            <AlertCircle class="w-16 h-16 text-red-500 mx-auto mb-4" />
                            <p class="text-lg font-medium">This order has been cancelled</p>
                            <p class="text-muted-foreground">Please contact us if you have any questions</p>
                        </div>

                        <!-- Estimated Time -->
                        <div v-if="order.estimated_ready_at && order.status !== 'completed' && order.status !== 'cancelled'" class="mt-6 p-4 bg-primary/10 rounded-lg text-center">
                            <Clock class="w-5 h-5 inline mr-2" />
                            <span class="font-medium">Estimated ready:</span>
                            {{ new Date(order.estimated_ready_at).toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' }) }}
                        </div>

                        <!-- Order Items -->
                        <div v-if="order.items" class="mt-6 border rounded-lg p-4">
                            <h3 class="font-medium mb-4">Order Items</h3>
                            <ul class="space-y-2">
                                <li
                                    v-for="item in order.items"
                                    :key="item.id"
                                    class="flex justify-between"
                                >
                                    <span>{{ item.quantity }}× {{ item.name }}</span>
                                    <span class="text-muted-foreground">£{{ item.subtotal?.toFixed(2) }}</span>
                                </li>
                            </ul>
                            <div class="border-t mt-4 pt-4 flex justify-between font-bold">
                                <span>Total</span>
                                <span>£{{ order.total?.toFixed(2) }}</span>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
