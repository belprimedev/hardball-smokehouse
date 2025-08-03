<script setup lang="ts">
import AdminLayout from '@/layouts/app/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { Plus, Edit, Trash2, Eye } from 'lucide-vue-next';

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
    is_active: boolean;
    created_at: string;
}

interface Props {
    vacancies: Vacancy[];
}

const props = defineProps<Props>();

const deleteVacancy = (id: number) => {
    if (confirm('Are you sure you want to delete this vacancy?')) {
        router.delete(route('admin.vacancies.destroy', id));
    }
};
</script>

<template>
    <AdminLayout>
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Vacancy Management</h1>
                    <p class="text-gray-600 dark:text-gray-400">Manage job vacancies and applications</p>
                </div>
                <Link 
                    :href="route('admin.vacancies.create')"
                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors"
                >
                    <Plus class="w-4 h-4 mr-2" />
                    Add Vacancy
                </Link>
            </div>

            <!-- Vacancies List -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Active Vacancies</h2>
                </div>
                
                <div v-if="props.vacancies.length > 0" class="divide-y divide-gray-200 dark:divide-gray-700">
                    <div v-for="vacancy in props.vacancies" :key="vacancy.id" class="p-6">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ vacancy.title }}
                                    </h3>
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                                            {{ vacancy.type }}
                                        </span>
                                        <span v-if="vacancy.department" class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                                            {{ vacancy.department }}
                                        </span>
                                        <span v-if="!vacancy.is_active" class="px-2 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full">
                                            Inactive
                                        </span>
                                        <span v-if="vacancy.is_expired" class="px-2 py-1 bg-orange-100 text-orange-800 text-xs font-medium rounded-full">
                                            Expired
                                        </span>
                                    </div>
                                </div>
                                
                                <p class="text-gray-600 dark:text-gray-300 mb-3">{{ vacancy.description }}</p>
                                
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
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
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-gray-600 dark:text-gray-400">
                                            {{ vacancy.application_deadline ? new Date(vacancy.application_deadline).toLocaleDateString() : 'No deadline' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-2 ml-4">
                                <Link 
                                    :href="route('admin.vacancies.show', vacancy.id)"
                                    class="p-2 text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-md transition-colors"
                                    title="View Details"
                                >
                                    <Eye class="w-4 h-4" />
                                </Link>
                                <Link 
                                    :href="route('admin.vacancies.edit', vacancy.id)"
                                    class="p-2 text-green-600 hover:text-green-700 hover:bg-green-50 rounded-md transition-colors"
                                    title="Edit"
                                >
                                    <Edit class="w-4 h-4" />
                                </Link>
                                <button 
                                    @click="deleteVacancy(vacancy.id)"
                                    class="p-2 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-md transition-colors"
                                    title="Delete"
                                >
                                    <Trash2 class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div v-else class="p-6 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No vacancies</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating a new vacancy.</p>
                    <div class="mt-6">
                        <Link 
                            :href="route('admin.vacancies.create')"
                            class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors"
                        >
                            <Plus class="w-4 h-4 mr-2" />
                            Add Vacancy
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template> 