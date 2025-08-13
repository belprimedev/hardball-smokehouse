<script setup lang="ts">
import MainLayout from '@/layouts/MainLayout.vue';

interface Vacancy {
    id: number;
    title: string;
    description: string;
    requirements?: string;
    responsibilities?: string;
    location: string;
    type: string;
    department?: string;
    salary: string;
    application_deadline?: string;
    positions_available: number;
    is_expired: boolean;
    created_at: string;
}

interface Props {
    vacancies: Vacancy[];
}

const props = defineProps<Props>();
</script>

<template>
    <MainLayout>
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-12">
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h1 class="text-5xl lg:text-6xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 via-white to-accent-red mb-4 knewave-regular">
                            Join Our Team
                        </h1>
                        <p class="text-xl lg:text-2xl text-gray-200 leading-relaxed">
                            Current job opportunities at Hardball Caribbean Smokehouse
                        </p>
                    </div>
                </div>
            </div>
        </section>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="mt-12 space-y-8">
                    <!-- Job listings -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Available Positions</h2>
                        
                        <div v-if="props.vacancies.length > 0" class="space-y-6">
                            <div v-for="vacancy in props.vacancies" :key="vacancy.id" 
                                 class="border-b border-gray-200 dark:border-gray-700 pb-6 last:border-b-0">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ vacancy.title }}</h3>
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                                            {{ vacancy.type }}
                                        </span>
                                        <span v-if="vacancy.department" class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                                            {{ vacancy.department }}
                                        </span>
                                    </div>
                                </div>
                                
                                <p class="text-gray-600 dark:text-gray-300 mb-3">{{ vacancy.description }}</p>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 text-sm">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span class="text-gray-600 dark:text-gray-400">{{ vacancy.location }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                        </svg>
                                        <span class="text-gray-600 dark:text-gray-400">{{ vacancy.salary }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <span class="text-gray-600 dark:text-gray-400">{{ vacancy.positions_available }} position(s)</span>
                                    </div>
                                </div>
                                
                                <div v-if="vacancy.requirements" class="mb-3">
                                    <h4 class="font-semibold text-gray-900 dark:text-white mb-1">Requirements:</h4>
                                    <p class="text-gray-600 dark:text-gray-300 text-sm">{{ vacancy.requirements }}</p>
                                </div>
                                
                                <div v-if="vacancy.responsibilities" class="mb-3">
                                    <h4 class="font-semibold text-gray-900 dark:text-white mb-1">Responsibilities:</h4>
                                    <p class="text-gray-600 dark:text-gray-300 text-sm">{{ vacancy.responsibilities }}</p>
                                </div>
                                
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center gap-4 text-sm text-gray-500">
                                        <span v-if="vacancy.application_deadline">
                                            Deadline: {{ new Date(vacancy.application_deadline).toLocaleDateString() }}
                                        </span>
                                        <span v-if="vacancy.is_expired" class="text-red-600 font-medium">
                                            Application Closed
                                        </span>
                                    </div>
                                    <a href="mailto:careers@hardballsmokehouse.co.uk?subject=Application for {{ vacancy.title }}" 
                                       class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors">
                                        Apply Now
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div v-else class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No positions available</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Check back later for new opportunities.</p>
                        </div>
                    </div>
                    
                    <!-- Application information -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                        <h2 class="text-2xl font-bold text-green-600 dark:text-white mb-4">How to Apply</h2>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">
                            To apply for any of our positions, please send your CV and a cover letter to 
                            <a href="mailto:careers@hardballsmokehouse.co.uk" class="text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300">
                                info@hardballsmokehouse.co.uk
                            </a>
                        </p>
                        <p class="text-gray-600 dark:text-gray-300">
                            Please include the position title in your email subject line and mention why you'd like to join our team.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template> 