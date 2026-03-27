import { ref, computed } from 'vue';

interface CartItem {
    id: number;
    menu_item_id: number;
    name: string;
    price: number;
    quantity: number;
    subtotal: number;
    image_path?: string;
    special_instructions?: string;
    available?: boolean;
}

const items = ref<CartItem[]>([]);
const total = computed(() => items.value.reduce((sum, item) => sum + item.subtotal, 0));
const count = computed(() => items.value.reduce((sum, item) => sum + item.quantity, 0));

export function useCartStore() {
    const fetchCart = async () => {
        try {
            const response = await fetch('/api/cart');
            const data = await response.json();
            items.value = data.items || [];
        } catch (error) {
            console.error('Failed to fetch cart:', error);
        }
    };

    const addItem = async (payload: { menu_item_id: number; quantity: number; special_instructions?: string }) => {
        const response = await fetch('/api/cart', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload),
        });

        if (!response.ok) {
            const error = await response.json();
            throw new Error(error.message);
        }

        await fetchCart();
    };

    const updateQuantity = async (itemId: number, quantity: number) => {
        const response = await fetch(`/api/cart/${itemId}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ quantity }),
        });

        if (response.ok) {
            await fetchCart();
        }
    };

    const removeItem = async (itemId: number) => {
        const response = await fetch(`/api/cart/${itemId}`, {
            method: 'DELETE',
        });

        if (response.ok) {
            await fetchCart();
        }
    };

    const clearCart = async () => {
        const response = await fetch('/api/cart', {
            method: 'DELETE',
        });

        if (response.ok) {
            items.value = [];
        }
    };

    return {
        items: computed(() => items.value),
        total,
        count,
        fetchCart,
        addItem,
        updateQuantity,
        removeItem,
        clearCart,
    };
}
