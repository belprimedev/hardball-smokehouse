<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { ShoppingBag } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import CartDrawer from './CartDrawer.vue';
import { useCartStore } from '@/stores/cart';

const cartStore = useCartStore();
const cartOpen = ref(false);

onMounted(() => {
    cartStore.fetchCart();
});
</script>

<template>
    <div class="fixed bottom-6 right-6 z-50">
        <Button
            size="lg"
            class="rounded-full shadow-lg h-14 w-14 relative"
            @click="cartOpen = true"
        >
            <ShoppingBag class="w-6 h-6" />
            <span
                v-if="cartStore.count > 0"
                class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center"
            >
                {{ cartStore.count > 99 ? '99+' : cartStore.count }}
            </span>
        </Button>
    </div>

    <CartDrawer v-model:open="cartOpen" />
</template>
