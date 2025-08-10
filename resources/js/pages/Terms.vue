<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import MainLayout from '@/layouts/MainLayout.vue';
</script>

<style scoped>
.knewave-regular {
    font-family: 'Knewave', cursive;
}

.bg-dark-900 {
    background-color: #0A1B2A;
}

.text-dark-900 {
    color: #0A1B2A;
}

.bg-light-cream {
    background-color: #FAF5D0;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.fade-in-up {
    animation: fadeInUp 0.6s ease-out;
}

.hover-lift {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}
</style>

<template>
    <MainLayout>
        <Head title="Terms & Conditions">
            <link rel="preconnect" href="https://rsms.me/" />
            <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
        </Head>

        <!-- Hero Section -->
        <section class="relative py-20 bg-gradient-to-br from-dark-900 via-green-900 to-dark-900 text-white overflow-hidden">
            <!-- Animated Background Pattern -->
            <div class="absolute inset-0 z-0">
                <div class="absolute inset-0 bg-[url('/img/bg/bg-5.jpg')] bg-cover bg-center bg-no-repeat"></div>
                <div class="absolute inset-0 bg-gradient-to-br from-dark-900 via-green-900 to-gray-900"></div>
                
                <!-- Floating Caribbean Elements -->
                <div class="absolute top-20 left-10 w-24 h-24 bg-yellow-400/20 rounded-full animate-pulse animation-delay-3000"></div>
                <div class="absolute top-40 right-20 w-16 h-16 bg-accent-red/20 rounded-full animate-pulse"></div>
                <div class="absolute bottom-40 left-1/4 w-20 h-20 bg-green-400/20 rounded-full animate-bounce animation-delay-2000"></div>
                <div class="absolute bottom-20 right-1/3 w-12 h-12 bg-yellow-400/20 rounded-full animate-pulse"></div>
            </div>

            <!-- Content Container -->
            <div class="relative z-10 container mx-auto px-4">
                <div class="text-center max-w-7xl mx-auto">
                    <div class="mb-8">
                        <div class="w-20 h-20 bg-yellow-400 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-dark-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h1 class="text-5xl lg:text-6xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 via-white to-accent-red mb-4 knewave-regular">
                            Terms & Conditions
                        </h1>
                        <p class="text-xl lg:text-2xl text-gray-200 leading-relaxed">
                            Please read our terms and conditions carefully before using our services.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Terms & Conditions Content -->
        <section class="py-20 bg-light-cream relative" style="background: url('/img/bg/food_pattern.jpg') no-repeat center center fixed; background-size: cover;">
            <!-- White overlay to make background lighter -->
            <div class="absolute inset-0 bg-white/95"></div>
            
            <div class="container mx-auto px-4 relative z-10">
                <div class="max-w-6xl mx-auto space-y-8">
                    
                    <!-- Introduction -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 hover-lift">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-dark-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h2 class="text-3xl font-bold text-dark-900 knewave-regular">Agreement to Terms</h2>
                        </div>
                        <p class="text-lg text-gray-700 leading-relaxed">
                            By accessing and using Hardball Caribbean Smokehouse's website and services, you agree to be bound by these Terms and Conditions. If you disagree with any part of these terms, please do not use our services.
                        </p>
                    </div>
                    
                    <!-- Reservations -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 hover-lift">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h2 class="text-3xl font-bold text-dark-900 knewave-regular">1. Reservations</h2>
                        </div>
                        
                        <div class="space-y-4">
                            <p class="text-lg text-gray-700 leading-relaxed">
                                All reservations are subject to availability and are not confirmed until you receive a confirmation email from us.
                            </p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-gray-100 p-6 rounded-xl">
                                    <h3 class="text-lg font-bold text-dark-900 mb-3">Arrival Time</h3>
                                    <p class="text-gray-700">Please arrive on time for your reservation. We may need to release your table if you are more than 15 minutes late.</p>
                                </div>
                                
                                <div class="bg-gray-100 p-6 rounded-xl">
                                    <h3 class="text-lg font-bold text-dark-900 mb-3">Table Allocation</h3>
                                    <p class="text-gray-700">We reserve the right to allocate tables based on availability and group size requirements.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cancellation Policy -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 hover-lift">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                            <h2 class="text-3xl font-bold text-dark-900 knewave-regular">2. Cancellation Policy</h2>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="bg-red-50 p-6 rounded-xl">
                                <h3 class="text-lg font-bold text-dark-900 mb-3">Standard Cancellations</h3>
                                <p class="text-gray-700">We require at least 24 hours' notice for cancellations. Late cancellations may result in a cancellation fee.</p>
                            </div>
                            
                            <div class="bg-yellow-50 p-6 rounded-xl">
                                <h3 class="text-lg font-bold text-dark-900 mb-3">Large Group Bookings</h3>
                                <p class="text-gray-700">For groups of 8 or more, a deposit may be required. Deposits are non-refundable if cancelled less than 48 hours before the reservation time.</p>
                            </div>
                            
                            <div class="bg-green-50 p-6 rounded-xl">
                                <h3 class="text-lg font-bold text-dark-900 mb-3">No-Show Policy</h3>
                                <p class="text-gray-700">Repeated no-shows may result in restrictions on future reservations.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Dietary Requirements -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 hover-lift">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h2 class="text-3xl font-bold text-dark-900 knewave-regular">3. Dietary Requirements</h2>
                        </div>
                        
                        <div class="space-y-4">
                            <p class="text-lg text-gray-700 leading-relaxed">
                                While we make every effort to accommodate dietary requirements, we cannot guarantee that our food is free from allergens.
                            </p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-gray-100 p-6 rounded-xl">
                                    <h3 class="text-lg font-bold text-dark-900 mb-3">Allergies</h3>
                                    <p class="text-gray-700">Please inform our staff of any allergies or dietary restrictions when making your reservation.</p>
                                </div>
                                
                                <div class="bg-gray-100 p-6 rounded-xl">
                                    <h3 class="text-lg font-bold text-dark-900 mb-3">Special Requests</h3>
                                    <p class="text-gray-700">We will do our best to accommodate special dietary requests with advance notice.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Terms -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 hover-lift">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                            </div>
                            <h2 class="text-3xl font-bold text-dark-900 knewave-regular">4. Payment Terms</h2>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gray-100 p-6 rounded-xl">
                                <h3 class="text-lg font-bold text-dark-900 mb-3">Accepted Methods</h3>
                                <p class="text-gray-700">We accept all major credit and debit cards, as well as cash payments.</p>
                            </div>
                            
                            <div class="bg-gray-100 p-6 rounded-xl">
                                <h3 class="text-lg font-bold text-dark-900 mb-3">Service Charges</h3>
                                <p class="text-gray-700">A service charge may be added to large group bookings and special events.</p>
                            </div>
                            
                            <div class="bg-gray-100 p-6 rounded-xl">
                                <h3 class="text-lg font-bold text-dark-900 mb-3">Pricing</h3>
                                <p class="text-gray-700">All prices are inclusive of VAT where applicable. Prices are subject to change without notice.</p>
                            </div>
                            
                            <div class="bg-gray-100 p-6 rounded-xl">
                                <h3 class="text-lg font-bold text-dark-900 mb-3">Deposits</h3>
                                <p class="text-gray-700">Deposits for large groups are non-refundable and must be paid in advance.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Conduct & Behavior -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 hover-lift">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <h2 class="text-3xl font-bold text-dark-900 knewave-regular">5. Conduct & Behavior</h2>
                        </div>
                        
                        <div class="space-y-4">
                            <p class="text-lg text-gray-700 leading-relaxed">
                                We expect all guests to behave respectfully towards our staff and other customers.
                            </p>
                            
                            <div class="space-y-3">
                                <div class="flex items-start gap-3">
                                    <div class="w-6 h-6 bg-yellow-400 rounded-full flex items-center justify-center mt-1">
                                        <div class="w-2 h-2 bg-dark-900 rounded-full"></div>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-dark-900">Respectful Behavior</h3>
                                        <p class="text-gray-700">Any disruptive or inappropriate behavior may result in being asked to leave.</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start gap-3">
                                    <div class="w-6 h-6 bg-yellow-400 rounded-full flex items-center justify-center mt-1">
                                        <div class="w-2 h-2 bg-dark-900 rounded-full"></div>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-dark-900">Dress Code</h3>
                                        <p class="text-gray-700">Smart casual dress is appreciated. We reserve the right to refuse service based on dress code.</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start gap-3">
                                    <div class="w-6 h-6 bg-yellow-400 rounded-full flex items-center justify-center mt-1">
                                        <div class="w-2 h-2 bg-dark-900 rounded-full"></div>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-dark-900">Photography</h3>
                                        <p class="text-gray-700">Personal photography is allowed, but please be respectful of other guests.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Liability -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 hover-lift">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-red-600 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                            </div>
                            <h2 class="text-3xl font-bold text-dark-900 knewave-regular">6. Liability & Disclaimers</h2>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="bg-red-50 p-6 rounded-xl">
                                <h3 class="text-lg font-bold text-dark-900 mb-3">Limitation of Liability</h3>
                                <p class="text-gray-700">Hardball Caribbean Smokehouse shall not be liable for any indirect, incidental, special, or consequential damages.</p>
                            </div>
                            
                            <div class="bg-yellow-50 p-6 rounded-xl">
                                <h3 class="text-lg font-bold text-dark-900 mb-3">Force Majeure</h3>
                                <p class="text-gray-700">We are not liable for any failure to perform due to circumstances beyond our reasonable control.</p>
                            </div>
                            
                            <div class="bg-blue-50 p-6 rounded-xl">
                                <h3 class="text-lg font-bold text-dark-900 mb-3">Accuracy of Information</h3>
                                <p class="text-gray-700">While we strive for accuracy, we cannot guarantee that all information on our website is always current or complete.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Changes to Terms -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 hover-lift">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </div>
                            <h2 class="text-3xl font-bold text-dark-900 knewave-regular">7. Changes to Terms</h2>
                        </div>
                        
                        <p class="text-lg text-gray-700 leading-relaxed">
                            We reserve the right to modify these terms at any time. Changes will be effective immediately upon posting on our website. Your continued use of our services constitutes acceptance of the modified terms.
                        </p>
                    </div>
                    
                    <!-- Contact Information -->
                    <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 rounded-2xl shadow-lg p-8 text-dark-900">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-dark-900 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h2 class="text-3xl font-bold text-dark-900 knewave-regular">Contact Us</h2>
                        </div>
                        
                        <p class="text-lg text-dark-900 mb-6">
                            If you have any questions about these Terms and Conditions, please contact us:
                        </p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-white/50 p-6 rounded-xl">
                                <h3 class="font-bold text-dark-900 mb-2">Email</h3>
                                <p class="text-dark-900">info@hardballsmokehouse.co.uk</p>
                            </div>
                            
                            <div class="bg-white/50 p-6 rounded-xl">
                                <h3 class="font-bold text-dark-900 mb-2">Phone</h3>
                                <p class="text-dark-900">+44 01473 807117</p>
                            </div>
                            
                            <div class="bg-white/50 p-6 rounded-xl">
                                <h3 class="font-bold text-dark-900 mb-2">Address</h3>
                                <p class="text-dark-900">24 Lloyds Ave, Ipswich IP1 3HD</p>
                            </div>
                            
                            <div class="bg-white/50 p-6 rounded-xl">
                                <h3 class="font-bold text-dark-900 mb-2">Response Time</h3>
                                <p class="text-dark-900">We aim to respond within 48 hours</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Last Updated -->
                    <div class="text-center text-gray-600">
                        <p class="text-lg">
                            <strong>Last Updated:</strong> January 2025
                        </p>
                        <p class="text-sm mt-2">
                            These Terms and Conditions may be updated periodically. We will notify you of any material changes.
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </MainLayout>
</template> 