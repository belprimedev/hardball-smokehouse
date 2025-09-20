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
        <div class="relative h-[50vh] sm:h-[60vh] md:h-[70vh] overflow-hidden">
            <div class="absolute inset-0">
                <img src="/img/landscape5.jpg" alt="Cocktail Background" class="w-full h-full object-cover" />
                <div class="absolute inset-0 bg-gradient-to-r from-black/60 via-black/40 to-transparent"></div>
            </div>
            <div class="relative h-full flex items-center justify-center text-center">
                <div class="max-w-5xl px-4 sm:px-6 lg:px-8">
                    <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xl font-black text-white mb-4 sm:mb-6 tracking-wider">Cocktails & Beverages</h1>
                    <p class="text-lg sm:text-xl md:text-2xl text-yellow-300 font-light mb-6 sm:mb-8 px-4">Discover our signature Caribbean-inspired drinks</p>
                    <div class="flex flex-col sm:flex-row justify-center space-y-2 sm:space-y-0 sm:space-x-4">
                        <div class="bg-yellow-400/20 backdrop-blur-sm rounded-full px-4 sm:px-6 py-2 border border-yellow-400/30">
                            <span class="text-yellow-300 text-sm font-medium">🍹 Craft Cocktails</span>
                        </div>
                        <div class="bg-yellow-400/20 backdrop-blur-sm rounded-full px-4 sm:px-6 py-2 border border-yellow-400/30">
                            <span class="text-yellow-300 text-sm font-medium">🍺 Premium Beers</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="bg-gradient-to-br from-gray-50 via-white to-gray-100 py-12 sm:py-16 lg:py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Featured Cocktails Section -->
                <div class="mb-12 sm:mb-16 lg:mb-20">
                    <div class="text-center mb-12 sm:mb-16">
                        <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900 mb-4">Signature Cocktails</h2>
                        <p class="text-lg sm:text-xl text-gray-600 max-w-3xl mx-auto px-4">Handcrafted with premium spirits and fresh ingredients, our cocktails bring the Caribbean to Ipswich</p>
                        <div class="w-24 sm:w-32 h-1 bg-gradient-to-r from-yellow-400 to-orange-500 mx-auto mt-6 rounded-full"></div>
                    </div>

                    <!-- Featured Cocktails with Images -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 sm:gap-12 mb-12 sm:mb-16">
                        <div v-for="item in cocktails.slice(0, 4)" :key="item.id"
                            class="group bg-white rounded-2xl overflow-hidden shadow-xl border border-gray-200 transform transition-all duration-500 hover:scale-105 hover:shadow-2xl">
                            <div class="relative h-64 sm:h-72 md:h-80">
                                <img :src="getImageSource(item.image_path)" :alt="item.name"
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <div class="absolute top-3 sm:top-4 right-3 sm:right-4 bg-yellow-400 text-black px-2 sm:px-3 py-1 rounded-full text-sm font-bold">
                                    £{{ item.price }}
                                </div>
                            </div>
                            <div class="p-6 sm:p-8">
                                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3">{{ item.name }}</h3>
                                <p class="text-gray-600 leading-relaxed text-sm sm:text-base">{{ item.description }}</p>
                                <div class="mt-4 flex items-center justify-between">
                                    <span class="text-yellow-600 font-semibold text-sm sm:text-base">Featured Cocktail</span>
                                    <div class="flex space-x-1">
                                        <svg v-for="star in 5" :key="star" class="w-4 sm:w-5 h-4 sm:h-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Cocktails List -->
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6 sm:p-8">
                        <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-6 sm:mb-8 text-center">More Cocktails</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                            <div v-for="item in cocktails.slice(4)" :key="item.id"
                                class="group p-4 sm:p-6 rounded-xl border border-gray-200 hover:border-yellow-300 hover:bg-yellow-50/50 transition-all duration-300">
                                <div class="flex items-start justify-between mb-3">
                                    <h4 class="text-base sm:text-lg font-bold text-gray-900 group-hover:text-yellow-700 transition-colors">{{ item.name }}</h4>
                                    <span class="text-yellow-600 font-bold text-base sm:text-lg">£{{ item.price }}</span>
                                </div>
                                <p class="text-gray-600 text-sm leading-relaxed">{{ item.description }}</p>
                                <div class="mt-4 flex items-center justify-between">
                                    <span class="text-xs text-gray-500">🍹 Premium Spirit</span>
                                    <div class="w-2 h-2 bg-yellow-400 rounded-full"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Beers Section -->
                <div class="mb-12 sm:mb-16 lg:mb-20">
                    <div class="text-center mb-12 sm:mb-16">
                        <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900 mb-4">Jamaican Beers</h2>
                        <p class="text-lg sm:text-xl text-gray-600 max-w-3xl mx-auto px-4">Authentic Caribbean beers and premium international selections</p>
                        <div class="w-24 sm:w-32 h-1 bg-gradient-to-r from-yellow-400 to-orange-500 mx-auto mt-6 rounded-full"></div>
                    </div>

                    <!-- Beer Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 sm:gap-8">
                        <div v-for="item in beers" :key="item.id"
                            class="group bg-white rounded-xl overflow-hidden shadow-lg border border-gray-200 hover:shadow-xl transition-all duration-300">
                            <div class="relative h-40 sm:h-48">
                                <img :src="getImageSource(item.image_path)" :alt="item.name"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />
                                <div class="absolute top-2 sm:top-3 right-2 sm:right-3 bg-yellow-400 text-black px-2 py-1 rounded-full text-xs font-bold">
                                    £{{ item.price }}
                                </div>
                            </div>
                            <div class="p-4 sm:p-6">
                                <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-2 group-hover:text-yellow-700 transition-colors">{{ item.name }}</h3>
                                <p class="text-gray-600 text-sm">{{ item.description }}</p>
                                <div class="mt-4 flex items-center justify-between">
                                    <span class="text-xs text-gray-500">🍺 Draft Beer</span>
                                    <div class="flex space-x-1">
                                        <div class="w-1 h-1 bg-yellow-400 rounded-full"></div>
                                        <div class="w-1 h-1 bg-yellow-400 rounded-full"></div>
                                        <div class="w-1 h-1 bg-yellow-400 rounded-full"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Special Offers Section -->
                <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-3xl p-8 sm:p-12 border border-yellow-200">
                    <div class="text-center mb-6 sm:mb-8">
                        <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Happy Hour Specials</h2>
                        <p class="text-lg sm:text-xl text-gray-600">Join us for amazing drink deals every day</p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                        <div class="text-center">
                            <div class="bg-yellow-400 rounded-full w-12 h-12 sm:w-16 sm:h-16 flex items-center justify-center mx-auto mb-4">
                                <span class="text-xl sm:text-2xl">🍹</span>
                            </div>
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2">Happy Hour</h3>
                            <p class="text-gray-600 text-sm sm:text-base">5-7 PM Daily</p>
                            <p class="text-yellow-600 font-bold text-sm sm:text-base">2 for 1 on All Cocktails</p>
                        </div>
                        <div class="text-center">
                            <div class="bg-yellow-400 rounded-full w-12 h-12 sm:w-16 sm:h-16 flex items-center justify-center mx-auto mb-4">
                                <span class="text-xl sm:text-2xl">🍺</span>
                            </div>
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2">Beer Special</h3>
                            <p class="text-gray-600 text-sm sm:text-base">All Day Sunday</p>
                            <p class="text-yellow-600 font-bold text-sm sm:text-base"></p>
                        </div>
                        <div class="text-center sm:col-span-2 lg:col-span-1">
                            <div class="bg-yellow-400 rounded-full w-12 h-12 sm:w-16 sm:h-16 flex items-center justify-center mx-auto mb-4">
                                <span class="text-xl sm:text-2xl">🎉</span>
                            </div>
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2">Weekend Vibes</h3>
                            <p class="text-gray-600 text-sm sm:text-base">Friday & Saturday</p>
                            <p class="text-yellow-600 font-bold text-sm sm:text-base">Live Music & Drinks</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="bg-gradient-to-r from-yellow-400 to-orange-500 py-12 sm:py-16 lg:py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4 sm:mb-6">Experience Our Signature Drinks</h2>
                <p class="text-yellow-100 mb-6 sm:mb-8 max-w-2xl mx-auto text-base sm:text-lg px-4">
                    Join us for an unforgettable evening of Caribbean-inspired cocktails and warm hospitality.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/make-reservation"
                        class="inline-block bg-white text-yellow-600 px-6 sm:px-8 py-3 sm:py-4 rounded-full font-bold hover:bg-gray-100 transition duration-300 shadow-lg text-sm sm:text-base">
                        Make a Reservation
                    </a>
                    <a href="/menu"
                        class="inline-block bg-transparent text-white border-2 border-white px-6 sm:px-8 py-3 sm:py-4 rounded-full font-bold hover:bg-white hover:text-yellow-600 transition duration-300 text-sm sm:text-base">
                        View Full Menu
                    </a>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<style scoped>
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
    background: #f3f4f6;
}

::-webkit-scrollbar-thumb {
    background: #fbbf24;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #f59e0b;
}

/* Smooth transitions */
* {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}

/* Hover effects */
.group:hover .group-hover\:scale-110 {
    transform: scale(1.1);
}

/* Gradient text effect */
.gradient-text {
    background: linear-gradient(135deg, #fbbf24, #f59e0b);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
</style> 