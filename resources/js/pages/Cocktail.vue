<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import MainLayout from '@/layouts/MainLayout.vue';
import { ref, onMounted } from 'vue';
import axios from 'axios';

interface MenuItem {
    id: number;
    name: string;
    description: string | null;
    price: number;
    image_path: string | null;
    category_id: number;
    is_featured: boolean;
    is_chef_special: boolean;
    is_available: boolean;
    is_visible: boolean;
    category?: {
        id: number;
        name: string;
        description: string | null;
    };
}

const cocktails = ref<MenuItem[]>([]);
const beers = ref<MenuItem[]>([]);
const loading = ref(true);

onMounted(async () => {
    try {
        // Fetch all menu items
        const response = await axios.get<MenuItem[]>('/api/menu-items');
        const items = response.data;

        // Filter items by category and availability
        cocktails.value = items.filter(item => 
            item.category?.name === 'Cocktails & Beverages' && 
            item.is_available
        );
        beers.value = items.filter(item => 
            item.category?.name === 'Beer' && 
            item.is_available
        );
    } catch (error) {
        console.error('Error fetching menu items:', error);
    } finally {
        loading.value = false;
    }
});

const getImageSource = (imagePath: string | null): string => {
    if (!imagePath) return '/img/cocktails/default-cocktail.jpg'; // Default image
    return `/storage/${imagePath}`;
};
</script>

<template>
    <MainLayout>
        <Head title="Cocktails & Beverages">
            <link rel="preconnect" href="https://rsms.me/" />
            <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
        </Head>

        <!-- Hero Section -->
        <div class="relative h-[60vh] overflow-hidden">
            <div class="absolute inset-0">
                <img src="/img/landscape5.jpg" alt="Cocktail Background" class="w-full h-full object-cover" />
                <div class="absolute inset-0 bg-[#131317] bg-opacity-50"></div>
            </div>
            <div class="relative h-full flex items-center justify-center text-center">
                <div class="max-w-4xl px-4">
                    <h1 class="text-5xl md:text-7xl font-black text-white mb-6 knewave-regular">Cocktails & Beverages</h1>
                    <p class="text-xl md:text-2xl text-yellow-400 font-light">Discover our signature Caribbean-inspired drinks</p>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="bg-[#131317] py-20 gallery-background">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Cocktails Section -->
                <div class="mb-20">
                    <div class="text-center mb-12">
                        <h2 class="text-4xl font-bold text-yellow-400 mb-4 great-vibes">Signature Cocktails</h2>
                        <div class="w-24 h-1 bg-yellow-400 mx-auto"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div v-for="item in cocktails" :key="item.id"
                            class="bg-[#2a2a2a] rounded-lg overflow-hidden transform transition duration-500 hover:scale-105 hover:shadow-2xl">
                            <div class="relative h-64">
                                <img :src="getImageSource(item.image_path)" :alt="item.name"
                                    class="w-full h-full object-cover" />
                            </div>
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <h3 class="text-xl font-bold text-white">{{ item.name }}</h3>
                                    <span class="text-yellow-400 font-bold">£{{ item.price }}</span>
                                </div>
                                <p class="text-gray-400">{{ item.description }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Beers Section -->
                <div>
                    <div class="text-center mb-12">
                        <h2 class="text-4xl font-bold text-yellow-400 mb-4 great-vibes">Jamaican Beers</h2>
                        <div class="w-24 h-1 bg-yellow-400 mx-auto"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div v-for="item in beers" :key="item.id"
                            class="bg-[#2a2a2a] rounded-lg overflow-hidden transform transition duration-500 hover:scale-105 hover:shadow-2xl">
                            <div class="relative h-64">
                                <img :src="getImageSource(item.image_path)" :alt="item.name"
                                    class="w-full h-full object-cover" />
                            </div>
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <h3 class="text-xl font-bold text-white">{{ item.name }}</h3>
                                    <span class="text-yellow-400 font-bold">£{{ item.price }}</span>
                                </div>
                                <p class="text-gray-400">{{ item.description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="bg-[#1a1a1a] py-20 border-t border-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl font-bold text-white mb-8">Experience Our Signature Drinks</h2>
                <p class="text-gray-400 mb-8 max-w-2xl mx-auto">
                    Join us for an unforgettable evening of Caribbean-inspired cocktails and warm hospitality.
                </p>
                <a href="/make-reservation"
                    class="inline-block bg-yellow-400 text-black px-8 py-3 rounded-full font-bold hover:bg-yellow-500 transition duration-300">
                    Make a Reservation
                </a>
            </div>
        </div>
    </MainLayout>
</template>

<style scoped>
.great-vibes {
    font-family: 'Great Vibes', cursive;
}

.gallery-background {
    background-color: #01101C;
    background-image: radial-gradient(rgba(255, 255, 255, 0.1) 0.5px, transparent 0.5px);
    background-size: 10px 10px;
}

/* Add smooth scrolling */
html {
    scroll-behavior: smooth;
}

/* Custom animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fadeIn 0.6s ease-out forwards;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #1a1a1a;
}

::-webkit-scrollbar-thumb {
    background: #facc15;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #eab308;
}
</style> 