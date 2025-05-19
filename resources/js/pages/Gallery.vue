<script setup lang="ts">
import MainLayout from '@/layouts/MainLayout.vue';
</script>

<template>
    <MainLayout>
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-8">Photo Gallery</h1>
                    <p class="text-xl text-gray-600 dark:text-gray-300">Take a look at our restaurant and events</p>
                </div>
                
                <!-- Gallery grid -->
                <div class="mt-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Gallery images will go here -->
                </div>
            </div>
        </div>
    </MainLayout>
</template> 