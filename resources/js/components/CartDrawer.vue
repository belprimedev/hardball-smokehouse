<script setup lang="ts">
import { computed } from 'vue';
import { Sheet, SheetContent, SheetHeader, SheetTitle, SheetDescription } from '@/components/ui/sheet';
import { Button } from '@/components/ui/button';
import { ScrollArea } from '@/components/ui/scroll-area';
import { Separator } from '@/components/ui/separator';
import { Minus, Plus, ShoppingBag, Trash2, X } from 'lucide-vue-next';
import { useCartStore } from '@/stores/cart';
import { router } from '@inertiajs/vue3';

const cartStore = useCartStore();

const props = defineProps<{
    open: boolean;
}>();

const emit = defineEmits<{
    (e: 'update:open', value: boolean): void;
}>();

const isOpen = computed({
    get: () => props.open,
    set: (value) => emit('update:open', value),
});

const cart = computed(() => cartStore.items);
const total = computed(() => cartStore.total);
const count = computed(() => cartStore.count);

const handleCheckout = () => {
    isOpen.value = false;
    router.visit('/order/checkout');
};
</script>

<template>
    <Sheet v-model:open="isOpen">
        <SheetContent class="w-full sm:max-w-lg flex flex-col">
            <SheetHeader class="pb-4">
                <SheetTitle class="flex items-center gap-2">
                    <ShoppingBag class="w-5 h-5" />
                    Your Cart ({{ count }})
                </SheetTitle>
                <SheetDescription v-if="cart.length === 0">
                    Your cart is empty. Browse our menu to add items.
                </SheetDescription>
            </SheetHeader>

            <div v-if="cart.length === 0" class="flex-1 flex items-center justify-center text-muted-foreground">
                <div class="text-center">
                    <ShoppingBag class="w-16 h-16 mx-auto mb-4 opacity-50" />
                    <p>Nothing in your cart yet</p>
                    <Button variant="outline" class="mt-4" @click="isOpen = false">
                        Continue Shopping
                    </Button>
                </div>
            </div>

            <template v-else>
                <ScrollArea class="flex-1 -mx-6 px-6">
                    <div class="space-y-4">
                        <div
                            v-for="item in cart"
                            :key="item.id"
                            class="flex gap-4 p-3 border rounded-lg"
                        >
                            <img
                                v-if="item.image_path"
                                :src="item.image_path"
                                :alt="item.name"
                                class="w-20 h-20 object-cover rounded"
                            />
                            <div v-else class="w-20 h-20 bg-muted rounded flex items-center justify-center text-xs text-muted-foreground">
                                No Image
                            </div>

                            <div class="flex-1 min-w-0">
                                <h4 class="font-medium truncate">{{ item.name }}</h4>
                                <p class="text-sm text-muted-foreground">£{{ item.price.toFixed(2) }}</p>

                                <div class="flex items-center gap-2 mt-2">
                                    <div class="flex items-center border rounded">
                                        <Button
                                            variant="ghost"
                                            size="icon"
                                            class="h-7 w-7"
                                            @click="cartStore.updateQuantity(item.id, item.quantity - 1)"
                                        >
                                            <Minus class="w-3 h-3" />
                                        </Button>
                                        <span class="w-8 text-center text-sm">{{ item.quantity }}</span>
                                        <Button
                                            variant="ghost"
                                            size="icon"
                                            class="h-7 w-7"
                                            @click="cartStore.updateQuantity(item.id, item.quantity + 1)"
                                        >
                                            <Plus class="w-3 h-3" />
                                        </Button>
                                    </div>

                                    <Button
                                        variant="ghost"
                                        size="icon"
                                        class="h-7 w-7 text-destructive"
                                        @click="cartStore.removeItem(item.id)"
                                    >
                                        <Trash2 class="w-4 h-4" />
                                    </Button>
                                </div>
                            </div>

                            <div class="text-right">
                                <p class="font-medium">£{{ item.subtotal.toFixed(2) }}</p>
                            </div>
                        </div>
                    </div>
                </ScrollArea>

                <div class="pt-4 space-y-4">
                    <Separator />

                    <div class="flex justify-between items-center">
                        <span class="text-muted-foreground">Subtotal</span>
                        <span class="font-medium">£{{ total.toFixed(2) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-muted-foreground">Delivery</span>
                        <span class="text-muted-foreground">Calculated at checkout</span>
                    </div>
                    <Separator />
                    <div class="flex justify-between items-center text-lg font-bold">
                        <span>Total</span>
                        <span>£{{ total.toFixed(2) }}</span>
                    </div>

                    <Button class="w-full" size="lg" @click="handleCheckout">
                        Proceed to Checkout
                    </Button>

                    <Button variant="ghost" class="w-full" @click="cartStore.clearCart()">
                        Clear Cart
                    </Button>
                </div>
            </template>
        </SheetContent>
    </Sheet>
</template>
