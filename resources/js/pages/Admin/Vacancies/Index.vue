<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Plus, Edit, Trash2, Eye, Briefcase, Users, MapPin, Calendar } from 'lucide-vue-next';

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

interface PaginatedData {
    data: Vacancy[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

interface Props {
    vacancies: PaginatedData;
    flash?: {
        success?: string;
        error?: string;
    };
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Admin',
        href: route('admin.dashboard'),
    },
    {
        title: 'Vacancy Management',
        href: route('admin.vacancies.index'),
    },
];

const deleteVacancy = (id: number) => {
    if (confirm('Are you sure you want to delete this vacancy?')) {
        router.delete(route('admin.vacancies.destroy', id));
    }
};

// Calculate statistics
const stats = {
    total: props.vacancies.total,
    active: props.vacancies.data.filter(v => v.is_active).length,
    expired: props.vacancies.data.filter(v => v.is_expired).length,
    thisMonth: props.vacancies.data.filter(v => {
        const created = new Date(v.created_at);
        const now = new Date();
        return created.getMonth() === now.getMonth() && created.getFullYear() === now.getFullYear();
    }).length
};

// Helper to get visible pages for pagination
const getVisiblePages = () => {
    const pages: (number | '...')[] = [];
    const current = props.vacancies.current_page;
    const last = props.vacancies.last_page;

    if (last <= 5) {
        for (let i = 1; i <= last; i++) {
            pages.push(i);
        }
    } else {
        pages.push(1);
        if (current > 3) {
            pages.push('...');
        }
        for (let i = Math.max(2, current - 1); i <= Math.min(last - 1, current + 1); i++) {
            pages.push(i);
        }
        if (current < last - 2) {
            pages.push('...');
        }
        pages.push(last);
    }
    return pages;
};
</script>

<template>
    <Head title="Vacancy Management" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-6">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-600 to-emerald-600 rounded-xl p-6 text-white">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">Vacancy Management</h1>
                        <p class="text-green-100">Manage job vacancies and track applications</p>
                    </div>
                    <Link :href="route('admin.vacancies.create')"
                        class="inline-flex items-center gap-2 bg-white text-green-600 px-6 py-3 rounded-lg font-bold hover:bg-gray-100 transition-colors">
                        <Plus class="w-5 h-5" />
                        Add Vacancy
                    </Link>
                </div>
            </div>

            <!-- Flash Messages -->
            <div v-if="props.flash?.success" class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-400 p-4 rounded-lg">
                <p class="text-sm text-green-700 dark:text-green-300">{{ props.flash.success }}</p>
            </div>
            
            <div v-if="props.flash?.error" class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400 p-4 rounded-lg">
                <p class="text-sm text-red-700 dark:text-red-300">{{ props.flash.error }}</p>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Vacancies</p>
                            <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ stats.total }}</p>
                        </div>
                        <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                            <Briefcase class="w-6 h-6 text-green-600 dark:text-green-400" />
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Active Vacancies</p>
                            <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ stats.active }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                            <Users class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Expired Vacancies</p>
                            <p class="text-3xl font-bold text-red-600 dark:text-red-400">{{ stats.expired }}</p>
                        </div>
                        <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-lg">
                            <Calendar class="w-6 h-6 text-red-600 dark:text-red-400" />
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">This Month</p>
                            <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ stats.thisMonth }}</p>
                        </div>
                        <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                            <Calendar class="w-6 h-6 text-yellow-600 dark:text-yellow-400" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vacancies List -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Active Vacancies</h2>
                </div>
                
                <div v-if="props.vacancies.data.length > 0" class="divide-y divide-gray-200 dark:divide-gray-700">
                    <div v-for="vacancy in props.vacancies.data" :key="vacancy.id" class="p-6 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-3">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ vacancy.title }}
                                    </h3>
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-1 bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 text-xs font-medium rounded-full">
                                            {{ vacancy.type }}
                                        </span>
                                        <span v-if="vacancy.department" class="px-2 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 text-xs font-medium rounded-full">
                                            {{ vacancy.department }}
                                        </span>
                                        <span v-if="!vacancy.is_active" class="px-2 py-1 bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 text-xs font-medium rounded-full">
                                            Inactive
                                        </span>
                                        <span v-if="vacancy.is_expired" class="px-2 py-1 bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400 text-xs font-medium rounded-full">
                                            Expired
                                        </span>
                                    </div>
                                </div>
                                
                                <p class="text-gray-600 dark:text-gray-300 mb-4 line-clamp-2">{{ vacancy.description }}</p>
                                
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                                    <div class="flex items-center gap-2">
                                        <MapPin class="w-4 h-4 text-gray-500" />
                                        <span class="text-gray-600 dark:text-gray-400">{{ vacancy.location }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                        </svg>
                                        <span class="text-gray-600 dark:text-gray-400">{{ vacancy.salary }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <Users class="w-4 h-4 text-gray-500" />
                                        <span class="text-gray-600 dark:text-gray-400">{{ vacancy.positions_available }} position(s)</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <Calendar class="w-4 h-4 text-gray-500" />
                                        <span class="text-gray-600 dark:text-gray-400">
                                            {{ vacancy.application_deadline ? new Date(vacancy.application_deadline).toLocaleDateString() : 'No deadline' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-2 ml-4">
                                <Link 
                                    :href="route('admin.vacancies.show', vacancy.id)"
                                    class="p-2 text-blue-600 hover:text-blue-700 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-md transition-colors"
                                    title="View Details"
                                >
                                    <Eye class="w-4 h-4" />
                                </Link>
                                <Link 
                                    :href="route('admin.vacancies.edit', vacancy.id)"
                                    class="p-2 text-green-600 hover:text-green-700 hover:bg-green-50 dark:hover:bg-green-900/30 rounded-md transition-colors"
                                    title="Edit"
                                >
                                    <Edit class="w-4 h-4" />
                                </Link>
                                <button 
                                    @click="deleteVacancy(vacancy.id)"
                                    class="p-2 text-red-600 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-md transition-colors"
                                    title="Delete"
                                >
                                    <Trash2 class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div v-else class="p-12 text-center">
                    <div class="mx-auto h-12 w-12 text-gray-400 mb-4">
                        <Briefcase class="w-12 h-12" />
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No vacancies</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">Get started by creating a new vacancy.</p>
                    <Link 
                        :href="route('admin.vacancies.create')"
                        class="inline-flex items-center gap-2 bg-green-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-green-700 transition-colors"
                    >
                        <Plus class="w-5 h-5" />
                        Add Vacancy
                    </Link>
                </div>

                <!-- Pagination -->
                <div v-if="props.vacancies.last_page > 1" class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700 dark:text-gray-300">
                            Showing {{ ((props.vacancies.current_page - 1) * props.vacancies.per_page) + 1 }} to 
                            {{ Math.min(props.vacancies.current_page * props.vacancies.per_page, props.vacancies.total) }} of 
                            {{ props.vacancies.total }} results
                        </div>
                        
                        <!-- Pagination Controls -->
                        <div class="flex items-center space-x-1">
                            <!-- First Page -->
                            <Link v-if="props.vacancies.current_page > 1" 
                                :href="route('admin.vacancies.index', { page: 1 })"
                                class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 transition-colors"
                                title="First Page"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                                </svg>
                            </Link>
                            
                            <!-- Previous Page -->
                            <Link v-if="props.vacancies.current_page > 1" 
                                :href="route('admin.vacancies.index', { page: props.vacancies.current_page - 1 })"
                                class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 transition-colors"
                                :class="{ 'rounded-l-md': props.vacancies.current_page <= 1 }"
                                title="Previous Page"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </Link>
                            
                            <!-- Page Numbers -->
                            <template v-for="page in getVisiblePages()" :key="page">
                                <!-- Ellipsis -->
                                <span v-if="page === '...'" class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400">
                                    ...
                                </span>
                                
                                <!-- Page Number -->
                                <Link v-else
                                    :href="route('admin.vacancies.index', { page: page })"
                                    class="px-3 py-2 text-sm font-medium border border-gray-300 transition-colors"
                                    :class="page === props.vacancies.current_page 
                                        ? 'bg-green-600 text-white border-green-600 dark:bg-green-600 dark:border-green-600' 
                                        : 'text-gray-500 bg-white hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700'"
                                >
                                    {{ page }}
                                </Link>
                            </template>
                            
                            <!-- Next Page -->
                            <Link v-if="props.vacancies.current_page < props.vacancies.last_page"
                                :href="route('admin.vacancies.index', { page: props.vacancies.current_page + 1 })"
                                class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 transition-colors"
                                :class="{ 'rounded-r-md': props.vacancies.current_page >= props.vacancies.last_page }"
                                title="Next Page"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </Link>
                            
                            <!-- Last Page -->
                            <Link v-if="props.vacancies.current_page < props.vacancies.last_page"
                                :href="route('admin.vacancies.index', { page: props.vacancies.last_page })"
                                class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 transition-colors"
                                title="Last Page"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7m-8 0l7-7-7-7" />
                                </svg>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style> 