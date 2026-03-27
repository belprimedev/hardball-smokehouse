<script setup lang="ts">
import { ref } from 'vue';
import { Plus, Minus, Check } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { useCartStore } from '@/stores/cart';
import { useToast } from '@/components/ui/toast/use-toast';

interface MenuItem {
    id: number;
    name: string;
    description?: string;
    price: number;
    image_path?: string;
    short_label?: string;
    side_note?: string;
    is_available: boolean;
    is_featured?: boolean;
    is_chef_special?: boolean;
    category?: {
        name: string;
    };
}

const props = defineProps<{
    item: MenuItem;
}>

const cartStore = useCartStore();
const { toast } = useToast();

const quantity = ref(1);
const isAdding = ref(false);
const added = ref(false);

const handleAddToCart = async () => {
    if (!props.item.is_available) return;

    isAdding.value = true;

    try {
        await cartStore.addItem({
            menu_item_id: props.item.id,
            quantity: quantity.value,
        });

        added.value = true;
        setTimeout(() => {
            added.value = false;
            quantity.value = 1;
        }, 1500);

        toast({
            title: 'Added to cart',
            description: `${quantity.value} × ${props.item.name}`,
        });
    } catch (error) {
        toast({
            title: 'Error',
            description: 'Could not add item to cart',
            variant: 'destructive',
        });
    } finally {
        isAdding.value = false;
    }
};

const incrementQty = () => {
    if (quantity.value < 20) quantity.value++;
};

const decrementQty = () => {
    if (quantity.value > 1) quantity.value--;
};
</script>

<template>
    <Card class="group overflow-hidden transition-shadow hover:shadow-lg">
        <div class="relative aspect-[4/3] overflow-hidden">
            <img
                v-if="item.image_path"
                :src="item.image_path"
                :alt="item.name"
                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
            />
            <div
                v-else
                class="w-full h-full bg-muted flex items-center justify-center text-muted-foreground"
            >
                No Image
            </div>

            <!-- Badges -->
            <div class="absolute top-2 left-2 flex flex-col gap-1">
                <span
                    v-if="item.is_featured"
                    class="px-2 py-1 text-xs font-medium bg-primary text-primary-foreground rounded"
                >
                    Featured
                </span>
                <span
                    v-if="item.is_chef_special"
                    class="px-2 py-1 text-xs font-medium bg-amber-500 text-white rounded"
                >
                    Chef's Special
                </span>
            </div>

            <!-- Unavailable overlay -->
            <div
                v-if="!item.is_available"
                class="absolute inset-0 bg-black/60 flex items-center justify-center"
            >
                <span class="px-3 py-1 bg-red-500 text-white text-sm font-medium rounded">
                    Unavailable
                </span>
            </div>
        </div>

        <CardHeader class="pb-2">
            <div class="flex justify-between items-start gap-2">
                <div>
                    <h3 class="font-semibold text-lg">{{ item.name }}</h3>
                    <p v-if="item.category" class="text-sm text-muted-foreground">
                        {{ item.category.name }}
                    </p>
                </div>
                <span class="font-bold text-lg">£{{ item.price.toFixed(2) }}</span>
            </div>
        </CardHeader>

        <CardContent class="space-y-4">
            <p v-if="item.description" class="text-sm text-muted-foreground line-clamp-2">
                {{ item.description }}
            </p>
            <p v-if="item.side_note" class="text-xs text-amber-600">
                {{ item.side_note }}
            </p>

            <!-- Add to Cart Controls -->
            <div class="flex items-center gap-2">
                <div class="flex items-center border rounded-lg">
                    <Button
                        variant="ghost"
                        size="icon"
                        class="h-9 w-9"
                        @click="decrementQty"
                        :disabled="quantity <= 1 || !item.is_available"
                    >
                        <Minus class="w-4 h-4" />
                    </Button>
                    <span class="w-10 text-center font-medium">{{ quantity }}</span>
                    <Button
                        variant="ghost"
                        size="icon"
                        class="h-9 w-9"
                        @click="incrementQty"
                        :disabled="quantity >= 20 || !item.is_available"
                    >
                        <Plus class="w-4 h-4" />
                    </Button>
                </div>

                <Button
                    class="flex-1"
                    :disabled="!item.is_available || isAdding"
                    @click="handleAddToCart"
                    :variant="added ? 'outline' : 'default'"
                >
                    <Check v-if="added" class="w-4 h-4 mr-2" />
                    <span v-if="added">Added!</span>
                    <span v-else>Add to Cart</span>
                </Button>
            </div>
        </CardContent>
    </Card>
</template>
