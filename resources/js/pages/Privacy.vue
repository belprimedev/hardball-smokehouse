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
        <Head title="Privacy Policy">
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
                <div class="text-center max-w-5xl mx-auto">
                    <div class="mb-8">
                        <div class="w-20 h-20 bg-yellow-400 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-dark-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <h1 class="text-5xl lg:text-6xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 via-white to-accent-red mb-4 knewave-regular">
                            Privacy Policy
                        </h1>
                        <p class="text-xl lg:text-2xl text-gray-200 leading-relaxed">
                            Your privacy matters to us. Learn how we protect and handle your information.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Privacy Policy Content -->
        <section class="py-20 bg-light-cream relative" style="background: url('/img/bg/food_pattern.jpg') no-repeat center center fixed; background-size: cover;">
            <!-- White overlay to make background lighter -->
            <div class="absolute inset-0 bg-white/95"></div>
            
            <div class="container mx-auto px-4 relative z-10">
                <div class="max-w-6xl mx-auto space-y-8">
                    
                    <!-- Introduction -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 hover-lift">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h2 class="text-3xl font-bold text-dark-900 knewave-regular">Introduction</h2>
                        </div>
                        <p class="text-lg text-gray-700 leading-relaxed">
                            At Hardball Caribbean Smokehouse, we are committed to protecting your privacy and ensuring the security of your personal information. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website, make reservations, or interact with our services.
                        </p>
                    </div>

                    <!-- Information We Collect -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 hover-lift">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h2 class="text-3xl font-bold text-dark-900 knewave-regular">Information We Collect</h2>
                        </div>
                        
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-xl font-bold text-dark-900 mb-3">Personal Information</h3>
                                <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                                    <li>Name and contact information (email, phone number)</li>
                                    <li>Reservation details and preferences</li>
                                    <li>Payment information (processed securely through third-party providers)</li>
                                    <li>Communication preferences and marketing consent</li>
                                </ul>
                            </div>
                            
                            <div>
                                <h3 class="text-xl font-bold text-dark-900 mb-3">Automatically Collected Information</h3>
                                <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                                    <li>IP address and browser information</li>
                                    <li>Pages visited and time spent on our website</li>
                                    <li>Device information and cookies</li>
                                    <li>Analytics data to improve user experience</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- How We Use Your Information -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 hover-lift">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <h2 class="text-3xl font-bold text-dark-900 knewave-regular">How We Use Your Information</h2>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gray-100 p-6 rounded-xl">
                                <h3 class="text-lg font-bold text-dark-900 mb-3">Reservation Management</h3>
                                <p class="text-gray-700">Process and confirm your table reservations, manage special requests, and provide customer service.</p>
                            </div>
                            
                            <div class="bg-gray-100 p-6 rounded-xl">
                                <h3 class="text-lg font-bold text-dark-900 mb-3">Communication</h3>
                                <p class="text-gray-700">Send reservation confirmations, updates, and respond to your inquiries and feedback.</p>
                            </div>
                            
                            <div class="bg-gray-100 p-6 rounded-xl">
                                <h3 class="text-lg font-bold text-dark-900 mb-3">Marketing (with consent)</h3>
                                <p class="text-gray-700">Send newsletters, promotions, and updates about our services and special events.</p>
                            </div>
                            
                            <div class="bg-gray-100 p-6 rounded-xl">
                                <h3 class="text-lg font-bold text-dark-900 mb-3">Service Improvement</h3>
                                <p class="text-gray-700">Analyze usage patterns to improve our website, services, and customer experience.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Information Sharing -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 hover-lift">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <h2 class="text-3xl font-bold text-dark-900 knewave-regular">Information Sharing</h2>
                        </div>
                        
                        <p class="text-lg text-gray-700 leading-relaxed mb-6">
                            We do not sell, trade, or rent your personal information to third parties. We may share your information only in the following circumstances:
                        </p>
                        
                        <div class="space-y-4">
                            <div class="flex items-start gap-3">
                                <div class="w-6 h-6 bg-yellow-400 rounded-full flex items-center justify-center mt-1">
                                    <div class="w-2 h-2 bg-dark-900 rounded-full"></div>
                                </div>
                                <div>
                                    <h3 class="font-bold text-dark-900">Service Providers</h3>
                                    <p class="text-gray-700">Trusted third-party services that help us operate our business (payment processors, email services, analytics).</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start gap-3">
                                <div class="w-6 h-6 bg-yellow-400 rounded-full flex items-center justify-center mt-1">
                                    <div class="w-2 h-2 bg-dark-900 rounded-full"></div>
                                </div>
                                <div>
                                    <h3 class="font-bold text-dark-900">Legal Requirements</h3>
                                    <p class="text-gray-700">When required by law or to protect our rights, safety, or the safety of others.</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start gap-3">
                                <div class="w-6 h-6 bg-yellow-400 rounded-full flex items-center justify-center mt-1">
                                    <div class="w-2 h-2 bg-dark-900 rounded-full"></div>
                                </div>
                                <div>
                                    <h3 class="font-bold text-dark-900">Business Transfers</h3>
                                    <p class="text-gray-700">In the event of a merger, acquisition, or sale of assets, with appropriate privacy protections.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Security -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 hover-lift">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <h2 class="text-3xl font-bold text-dark-900 knewave-regular">Data Security</h2>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gray-100 p-6 rounded-xl">
                                <h3 class="text-lg font-bold text-dark-900 mb-3">Encryption</h3>
                                <p class="text-gray-700">All sensitive data is encrypted using industry-standard SSL/TLS protocols to protect information in transit.</p>
                            </div>
                            
                            <div class="bg-gray-100 p-6 rounded-xl">
                                <h3 class="text-lg font-bold text-dark-900 mb-3">Access Controls</h3>
                                <p class="text-gray-700">Strict access controls and authentication measures to ensure only authorized personnel can access your data.</p>
                            </div>
                            
                            <div class="bg-gray-100 p-6 rounded-xl">
                                <h3 class="text-lg font-bold text-dark-900 mb-3">Regular Audits</h3>
                                <p class="text-gray-700">Regular security assessments and updates to maintain the highest level of data protection.</p>
                            </div>
                            
                            <div class="bg-gray-100 p-6 rounded-xl">
                                <h3 class="text-lg font-bold text-dark-900 mb-3">Incident Response</h3>
                                <p class="text-gray-700">Comprehensive incident response procedures in case of any security breaches or data incidents.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Your Rights -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 hover-lift">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-emerald-500 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <h2 class="text-3xl font-bold text-dark-900 knewave-regular">Your Rights</h2>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="border-l-4 border-yellow-400 pl-6">
                                <h3 class="text-lg font-bold text-dark-900 mb-2">Access & Update</h3>
                                <p class="text-gray-700">Request access to your personal information and update or correct any inaccuracies.</p>
                            </div>
                            
                            <div class="border-l-4 border-green-400 pl-6">
                                <h3 class="text-lg font-bold text-dark-900 mb-2">Deletion</h3>
                                <p class="text-gray-700">Request deletion of your personal information, subject to legal and business requirements.</p>
                            </div>
                            
                            <div class="border-l-4 border-blue-400 pl-6">
                                <h3 class="text-lg font-bold text-dark-900 mb-2">Portability</h3>
                                <p class="text-gray-700">Receive a copy of your personal information in a structured, machine-readable format.</p>
                            </div>
                            
                            <div class="border-l-4 border-red-400 pl-6">
                                <h3 class="text-lg font-bold text-dark-900 mb-2">Marketing Opt-out</h3>
                                <p class="text-gray-700">Unsubscribe from marketing communications at any time using the link in our emails.</p>
                            </div>
                        </div>
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
                            If you have any questions about this Privacy Policy or our data practices, please contact us:
                        </p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-white/50 p-6 rounded-xl">
                                <h3 class="font-bold text-dark-900 mb-2">Email</h3>
                                <p class="text-dark-900">privacy@hardballsmokehouse.co.uk</p>
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
                            This Privacy Policy may be updated periodically. We will notify you of any material changes.
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </MainLayout>
</template> 