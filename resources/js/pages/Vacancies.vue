<script setup lang="ts">
import MainLayout from '@/layouts/MainLayout.vue';
</script>

<template>
    <MainLayout>
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-8">Join Our Team</h1>
                    <p class="text-xl text-gray-600 dark:text-gray-300">Current job opportunities at Hardball Caribbean Smokehouse</p>
                </div>
                
                <div class="mt-12 space-y-8">
                    <!-- Job listings -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Available Positions</h2>
                        
                        <div class="space-y-6">
                            <!-- Job position 1 -->
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Chef de Partie</h3>
                                <p class="text-gray-600 dark:text-gray-300 mt-2">
                                    We are looking for an experienced Chef de Partie to join our kitchen team. 
                                    The ideal candidate will have experience in Caribbean cuisine and a passion 
                                    for creating authentic dishes.
                                </p>
                                <div class="mt-4">
                                    <a href="#" class="text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300">
                                        Apply Now
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Job position 2 -->
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Front of House Staff</h3>
                                <p class="text-gray-600 dark:text-gray-300 mt-2">
                                    Join our front of house team as a server or host. We're looking for friendly, 
                                    enthusiastic individuals who are passionate about providing excellent customer service.
                                </p>
                                <div class="mt-4">
                                    <a href="#" class="text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300">
                                        Apply Now
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Job position 3 -->
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Kitchen Porter</h3>
                                <p class="text-gray-600 dark:text-gray-300 mt-2">
                                    We're seeking a reliable and hardworking kitchen porter to support our kitchen team. 
                                    This is an excellent opportunity to start your career in the culinary industry.
                                </p>
                                <div class="mt-4">
                                    <a href="#" class="text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300">
                                        Apply Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Application information -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">How to Apply</h2>
                        <p class="text-gray-600 dark:text-gray-300">
                            To apply for any of our positions, please send your CV and a cover letter to 
                            <a href="mailto:careers@hardballsmokehouse.co.uk" class="text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300">
                                careers@hardballsmokehouse.co.uk
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template> 