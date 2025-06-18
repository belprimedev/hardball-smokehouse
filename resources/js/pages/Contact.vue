<script setup lang="ts">
import MainLayout from '@/layouts/MainLayout.vue';
</script>

<template>
    <MainLayout>
        <div class="min-h-screen bg-gray-900 py-12 gallery-background">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-8">Contact Us</h1>
                    <p class="text-xl text-gray-600 dark:text-gray-300">Get in touch with us</p>
                </div>
                
                <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Contact form -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Send us a message</h2>
                        <form class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                                <input type="text" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                                <input type="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>
                            <div>
                                <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Message</label>
                                <textarea id="message" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                            </div>
                            <button type="submit" class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                Send Message
                            </button>
                        </form>
                    </div>
                    
                    <!-- Contact information -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Contact Information</h2>
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Address</h3>
                                <p class="text-gray-600 dark:text-gray-300">24 Lloyds Ave, Ipswich IP1 3HD</p>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Phone</h3>
                                <p class="text-gray-600 dark:text-gray-300">+44 01473 807117</p>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Email</h3>
                                <p class="text-gray-600 dark:text-gray-300">info@hardballsmokehouse.co.uk</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<style scoped>
.gallery-background {
    background-color: #01101C;
    background-image: radial-gradient(rgba(255, 255, 255, 0.1) 0.5px, transparent 0.5px);
    background-size: 10px 10px;
}
</style> 