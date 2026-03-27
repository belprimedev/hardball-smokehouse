<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { ScrollArea } from '@/components/ui/scroll-area';
import { Separator } from '@/components/ui/separator';
import {
    Clock,
    ChefHat,
    Package,
    CheckCircle,
    Truck,
    AlertCircle,
    RefreshCw,
} from 'lucide-vue-next';
import Pusher from 'pusher-js';

interface OrderItem {
    id: number;
    name: string;
    quantity: number;
    special_instructions?: string;
}

interface Order {
    id: number;
    order_number: string;
    status: 'pending' | 'confirmed' | 'preparing' | 'ready' | 'out_for_delivery' | 'completed' | 'cancelled';
    fulfillment_type: 'delivery' | 'pickup';
    customer_name: string;
    estimated_ready_at: string;
    items: OrderItem[];
    special_instructions?: string;
    created_at: string;
}

const orders = ref<Order[]>([]);
const isLoading = ref(true);
const autoRefresh = ref(true);
let refreshInterval: NodeJS.Timeout | null = null;
let pusherChannel: any = null;

const statusConfig: Record<string, { label: string; color: string; icon: any }> = {
    pending: { label: 'Pending', color: 'bg-yellow-500', icon: AlertCircle },
    confirmed: { label: 'Confirmed', color: 'bg-blue-500', icon: Clock },
    preparing: { label: 'Preparing', color: 'bg-orange-500', icon: ChefHat },
    ready: { label: 'Ready', color: 'bg-green-500', icon: Package },
    out_for_delivery: { label: 'Out for Delivery', color: 'bg-purple-500', icon: Truck },
    completed: { label: 'Completed', color: 'bg-gray-500', icon: CheckCircle },
    cancelled: { label: 'Cancelled', color: 'bg-red-500', icon: AlertCircle },
};

const fetchOrders = async () => {
    try {
        const response = await fetch('/api/kitchen/queue');
        const data = await response.json();
        orders.value = data;
    } catch (error) {
        console.error('Failed to fetch orders:', error);
    } finally {
        isLoading.value = false;
    }
};

const updateOrderStatus = async (orderId: number, status: string) => {
    try {
        const response = await fetch(`/api/orders/${orderId}/status`, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ status }),
        });

        if (response.ok) {
            await fetchOrders();
        }
    } catch (error) {
        console.error('Failed to update status:', error);
    }
};

const getNextStatus = (currentStatus: string): string => {
    const flow = ['pending', 'confirmed', 'preparing', 'ready', 'out_for_delivery', 'completed'];
    const currentIndex = flow.indexOf(currentStatus);
    return flow[currentIndex + 1] || currentStatus;
};

const formatTime = (dateStr: string) => {
    return new Date(dateStr).toLocaleTimeString('en-GB', {
        hour: '2-digit',
        minute: '2-digit',
    });
};

const setupPusher = () => {
    const pusherKey = (window as any).PUSHER_APP_KEY;
    if (!pusherKey) return;

    const pusher = new Pusher(pusherKey, {
        cluster: (window as any).PUSHER_CLUSTER || 'eu',
        forceTLS: true,
    });

    pusherChannel = pusher.subscribe('orders');
    pusherChannel.bind('App\\Events\\OrderStatusUpdated', () => {
        fetchOrders();
    });
};

onMounted(() => {
    fetchOrders();
    setupPusher();

    if (autoRefresh.value) {
        refreshInterval = setInterval(fetchOrders, 30000);
    }
});

onUnmounted(() => {
    if (refreshInterval) {
        clearInterval(refreshInterval);
    }
    if (pusherChannel) {
        pusherChannel.unsubscribe();
    }
});

const toggleAutoRefresh = () => {
    autoRefresh.value = !autoRefresh.value;
    if (autoRefresh.value) {
        refreshInterval = setInterval(fetchOrders, 30000);
    } else if (refreshInterval) {
        clearInterval(refreshInterval);
    }
};
</script>

<template>
    <AppLayout>
        <Head title="Kitchen Dashboard" />

        <div class="container mx-auto py-6 px-4">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold">Kitchen Dashboard</h1>
                    <p class="text-muted-foreground">Manage orders in real-time</p>
                </div>
                <div class="flex items-center gap-4">
                    <Button
                        variant="outline"
                        size="sm"
                        :class="{ 'bg-primary/10': autoRefresh }"
                        @click="toggleAutoRefresh"
                    >
                        <RefreshCw class="w-4 h-4 mr-2" :class="{ 'animate-spin': autoRefresh }" />
                        Auto-refresh {{ autoRefresh ? 'ON' : 'OFF' }}
                    </Button>
                    <Button variant="outline" size="sm" @click="fetchOrders">
                        <RefreshCw class="w-4 h-4 mr-2" />
                        Refresh Now
                    </Button>
                </div>
            </div>

            <!-- Status Legend -->
            <div class="flex flex-wrap gap-3 mb-6">
                <div
                    v-for="(config, status) in statusConfig"
                    :key="status"
                    class="flex items-center gap-2 text-sm"
                >
                    <Badge :class="config.color">{{ config.label }}</Badge>
                </div>
            </div>

            <!-- Orders Grid -->
            <div v-if="isLoading" class="text-center py-12">
                <RefreshCw class="w-8 h-8 animate-spin mx-auto mb-4" />
                <p>Loading orders...</p>
            </div>

            <div v-else-if="orders.length === 0" class="text-center py-12 border-2 border-dashed rounded-lg">
                <ChefHat class="w-16 h-16 mx-auto mb-4 text-muted-foreground" />
                <p class="text-lg font-medium">No active orders</p>
                <p class="text-muted-foreground">New orders will appear here</p>
            </div>

            <div v-else class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                <Card
                    v-for="order in orders"
                    :key="order.id"
                    :class="{
                        'border-yellow-500': order.status === 'pending',
                        'border-blue-500': order.status === 'confirmed',
                        'border-orange-500': order.status === 'preparing',
                        'border-green-500 ring-2 ring-green-500/20': order.status === 'ready',
                    }"
                >
                    <CardHeader class="pb-3">
                        <div class="flex items-center justify-between">
                            <CardTitle class="text-lg">{{ order.order_number }}</CardTitle>
                            <Badge :class="statusConfig[order.status].color">
                                <component :is="statusConfig[order.status].icon" class="w-3 h-3 mr-1" />
                                {{ statusConfig[order.status].label }}
                            </Badge>
                        </div>
                        <CardDescription class="flex items-center gap-2">
                            <span>{{ order.customer_name }}</span>
                            <span>•</span>
                            <span class="capitalize">{{ order.fulfillment_type }}</span>
                        </CardDescription>
                    </CardHeader>

                    <CardContent>
                        <div class="flex items-center gap-2 text-sm text-muted-foreground mb-4">
                            <Clock class="w-4 h-4" />
                            <span>Ordered {{ formatTime(order.created_at) }}</span>
                            <span v-if="order.estimated_ready_at">• Ready {{ formatTime(order.estimated_ready_at) }}</span>
                        </div>

                        <ScrollArea class="h-32 mb-4 border rounded-lg p-3">
                            <ul class="space-y-2">
                                <li
                                    v-for="item in order.items"
                                    :key="item.id"
                                    class="text-sm"
                                >
                                    <span class="font-medium">{{ item.quantity }}×</span>
                                    {{ item.name }}
                                    <p v-if="item.special_instructions" class="text-xs text-muted-foreground pl-4">
                                        Note: {{ item.special_instructions }}
                                    </p>
                                </li>
                            </ul>
                        </ScrollArea>

                        <div v-if="order.special_instructions" class="mb-4 p-2 bg-yellow-50 text-yellow-800 text-sm rounded">
                            <AlertCircle class="w-4 h-4 inline mr-1" />
                            {{ order.special_instructions }}
                        </div>

                        <Separator class="my-4" />

                        <div class="flex gap-2">
                            <Button
                                v-if="order.status !== 'completed' && order.status !== 'cancelled'"
                                class="flex-1"
                                size="sm"
                                @click="updateOrderStatus(order.id, getNextStatus(order.status))"
                            >
                                {{ order.status === 'pending' ? 'Confirm' : 'Advance' }}
                            </Button>
                            <Button
                                v-if="order.status === 'pending'"
                                variant="destructive"
                                size="sm"
                                @click="updateOrderStatus(order.id, 'cancelled')"
                            >
                                Cancel
                            </Button>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
