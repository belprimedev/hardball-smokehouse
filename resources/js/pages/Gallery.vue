<script setup lang="ts">
import MainLayout from '@/layouts/MainLayout.vue';
import { ref } from 'vue';

// Sample gallery items - replace with your actual data
const galleryItems = ref([
    {
        id: 1,
        image: '/img/gallery/store8.jpg',
        title: 'Restaurant Interior',
        description: 'Our cozy dining area'
    },
    {
        id: 2,
        image: '/img/gallery/food1.jpg',
        title: 'Signature Dish',
        description: 'Chef\'s special creation'
    },
    {
        id: 3,
        image: '/img/gallery/event1.jpg',
        title: 'Private Events',
        description: 'Perfect for celebrations'
    },
    // Add more items as needed
]);

const isLoading = ref(true);

// Simulate loading state
setTimeout(() => {
    isLoading.value = false;
}, 1000);
</script>

<template>
    <MainLayout>
        <div class="min-h-screen bg-gray-900 py-12 gallery-background">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-4xl font-bold text-white mb-8">Photo Gallery</h1>
                    <p class="text-xl text-gray-300">Take a look at our restaurant and events</p>
                </div>
                
                <!-- Gallery grid -->
                <div class="mt-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Loading skeleton -->
                    <template v-if="isLoading">
                        <div v-for="n in 6" :key="n" class="animate-pulse">
                            <div class="bg-gray-200 dark:bg-gray-700 rounded-lg aspect-square"></div>
                            <div class="mt-2 h-4 bg-gray-200 dark:bg-gray-700 rounded w-3/4"></div>
                            <div class="mt-1 h-3 bg-gray-200 dark:bg-gray-700 rounded w-1/2"></div>
                        </div>
                    </template>

                    <!-- Gallery items -->
                    <template v-else>
                        <div v-for="item in galleryItems" :key="item.id" 
                             class="group relative overflow-hidden rounded-lg shadow-lg transition-transform duration-300 hover:scale-105">
                            <div class="aspect-square relative">
                                <img :src="item.image" 
                                     :alt="item.title"
                                     class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
                                     loading="lazy">
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all duration-300"></div>
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <h3 class="text-white text-lg font-semibold">{{ item.title }}</h3>
                                <p class="text-gray-200 text-sm">{{ item.description }}</p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<style scoped>
.aspect-square {
    aspect-ratio: 1 / 1;
}

.gallery-background {
    background-color: #01101C;
    background-image: radial-gradient(rgba(255, 255, 255, 0.1) 0.5px, transparent 0.5px);
    background-size: 10px 10px;
}
</style> 