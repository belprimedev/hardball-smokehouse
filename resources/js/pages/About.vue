<script setup lang="ts">
import MainLayout from '@/layouts/MainLayout.vue';
</script>

<template>
    <MainLayout>
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-8">About Us</h1>
                    <p class="text-xl text-gray-600 dark:text-gray-300">Our story and passion for Caribbean cuisine</p>
                </div>
                
                <div class="mt-12 space-y-8">
                    <!-- About content sections -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Our Story</h2>
                        <p class="text-gray-600 dark:text-gray-300">
                            Hardball Caribbean Smokehouse brings the authentic flavors of the Caribbean to Ipswich. 
                            Our passion for traditional cooking methods and high-quality ingredients ensures an 
                            unforgettable dining experience.
                        </p>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Our Mission</h2>
                        <p class="text-gray-600 dark:text-gray-300">
                            We are committed to providing exceptional Caribbean cuisine while creating a warm and 
                            welcoming atmosphere for our guests. Our goal is to share the rich culinary traditions 
                            of the Caribbean with our community.
                        </p>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Our Team</h2>
                        <p class="text-gray-600 dark:text-gray-300">
                            Our team of experienced chefs and staff are dedicated to delivering the best possible 
                            dining experience. We take pride in our work and are passionate about sharing our 
                            love for Caribbean cuisine with you.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template> 