<script setup lang="ts">
import MainLayout from '@/layouts/MainLayout.vue';
</script>

<template>
    <MainLayout>
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-8">Terms & Conditions</h1>
                    <p class="text-xl text-gray-600 dark:text-gray-300">Please read our terms and conditions carefully</p>
                </div>
                
                <div class="mt-12 space-y-8">
                    <!-- Terms sections -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">1. Reservations</h2>
                        <p class="text-gray-600 dark:text-gray-300">
                            All reservations are subject to availability. We reserve the right to cancel or modify 
                            reservations at our discretion. Please arrive on time for your reservation, as we may 
                            need to release your table if you are more than 15 minutes late.
                        </p>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">2. Cancellation Policy</h2>
                        <p class="text-gray-600 dark:text-gray-300">
                            We require at least 24 hours' notice for cancellations. For large group bookings, 
                            a deposit may be required, which is non-refundable if cancelled less than 48 hours 
                            before the reservation time.
                        </p>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">3. Dietary Requirements</h2>
                        <p class="text-gray-600 dark:text-gray-300">
                            While we make every effort to accommodate dietary requirements, we cannot guarantee 
                            that our food is free from allergens. Please inform our staff of any allergies or 
                            dietary restrictions when making your reservation.
                        </p>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">4. Payment</h2>
                        <p class="text-gray-600 dark:text-gray-300">
                            We accept all major credit and debit cards, as well as cash payments. A service charge 
                            may be added to large group bookings. All prices are inclusive of VAT where applicable.
                        </p>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">5. Privacy Policy</h2>
                        <p class="text-gray-600 dark:text-gray-300">
                            We are committed to protecting your privacy. Any personal information collected during 
                            the reservation process will be used only for the purpose of managing your booking and 
                            will not be shared with third parties without your consent.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template> 