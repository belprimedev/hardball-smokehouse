<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';

import { Plus, Eye, Edit, Trash2, Mail, Users, TrendingUp, Calendar } from 'lucide-vue-next';

interface Newsletter {
    id: number;
    email: string;
    status: string;
    source: string;
    created_at: string;
    updated_at: string;
}

interface PaginatedData {
    data: Newsletter[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

const props = defineProps<{
    newsletters: PaginatedData;
    flash?: {
        success?: string;
        error?: string;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Admin',
        href: route('admin.dashboard'),
    },
    {
        title: 'Newsletter Management',
        href: route('admin.newsletters.index'),
    },
];

const deleteNewsletter = (id: number) => {
    if (confirm('Are you sure you want to delete this newsletter subscriber?')) {
        router.delete(route('admin.newsletters.destroy', id));
    }
};

// Calculate statistics
const stats = {
    total: props.newsletters.total,
    active: props.newsletters.data.filter(n => n.status === 'active').length,
    unsubscribed: props.newsletters.data.filter(n => n.status === 'unsubscribed').length,
    thisMonth: props.newsletters.data.filter(n => {
        const created = new Date(n.created_at);
        const now = new Date();
        return created.getMonth() === now.getMonth() && created.getFullYear() === now.getFullYear();
    }).length
};

// Helper to get visible pages for pagination
const getVisiblePages = () => {
    const pages: (number | '...')[] = [];
    const current = props.newsletters.current_page;
    const last = props.newsletters.last_page;

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
    <Head title="Newsletter Management" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-6">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-cyan-600 rounded-xl p-6 text-white">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">Newsletter Management</h1>
                        <p class="text-blue-100">Manage your newsletter subscribers and track engagement</p>
                    </div>
                    <Link :href="route('admin.newsletters.create')"
                        class="inline-flex items-center gap-2 bg-white text-blue-600 px-6 py-3 rounded-lg font-bold hover:bg-gray-100 transition-colors">
                        <Plus class="w-5 h-5" />
                        Add Subscriber
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
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Subscribers</p>
                            <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ stats.total }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                            <Mail class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Active Subscribers</p>
                            <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ stats.active }}</p>
                        </div>
                        <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                            <Users class="w-6 h-6 text-green-600 dark:text-green-400" />
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Unsubscribed</p>
                            <p class="text-3xl font-bold text-red-600 dark:text-red-400">{{ stats.unsubscribed }}</p>
                        </div>
                        <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-lg">
                            <TrendingUp class="w-6 h-6 text-red-600 dark:text-red-400" />
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

            <!-- Newsletter Table -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Newsletter Subscribers</h2>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Email
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Source
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Subscribed Date
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            <tr v-for="newsletter in props.newsletters.data" :key="newsletter.id" class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ newsletter.email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                                        :class="newsletter.status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400'">
                                        {{ newsletter.status.charAt(0).toUpperCase() + newsletter.status.slice(1) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ newsletter.source.charAt(0).toUpperCase() + newsletter.source.slice(1) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ new Date(newsletter.created_at).toLocaleDateString() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <Link :href="route('admin.newsletters.edit', newsletter.id)"
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                            <Edit class="w-4 h-4" />
                                        </Link>
                                        <Link :href="route('admin.newsletters.show', newsletter.id)"
                                            class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                            <Eye class="w-4 h-4" />
                                        </Link>
                                        <button @click="deleteNewsletter(newsletter.id)"
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                            <Trash2 class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="props.newsletters.last_page > 1" class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700 dark:text-gray-300">
                            Showing {{ ((props.newsletters.current_page - 1) * props.newsletters.per_page) + 1 }} to 
                            {{ Math.min(props.newsletters.current_page * props.newsletters.per_page, props.newsletters.total) }} of 
                            {{ props.newsletters.total }} results
                        </div>
                        
                        <!-- Pagination Controls -->
                        <div class="flex items-center space-x-1">
                            <!-- First Page -->
                            <Link v-if="props.newsletters.current_page > 1" 
                                :href="route('admin.newsletters.index', { page: 1 })"
                                class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 transition-colors"
                                title="First Page"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                                </svg>
                            </Link>
                            
                            <!-- Previous Page -->
                            <Link v-if="props.newsletters.current_page > 1" 
                                :href="route('admin.newsletters.index', { page: props.newsletters.current_page - 1 })"
                                class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 transition-colors"
                                :class="{ 'rounded-l-md': props.newsletters.current_page <= 1 }"
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
                                    :href="route('admin.newsletters.index', { page: page })"
                                    class="px-3 py-2 text-sm font-medium border border-gray-300 transition-colors"
                                    :class="page === props.newsletters.current_page 
                                        ? 'bg-blue-600 text-white border-blue-600 dark:bg-blue-600 dark:border-blue-600' 
                                        : 'text-gray-500 bg-white hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700'"
                                >
                                    {{ page }}
                                </Link>
                            </template>
                            
                            <!-- Next Page -->
                            <Link v-if="props.newsletters.current_page < props.newsletters.last_page"
                                :href="route('admin.newsletters.index', { page: props.newsletters.current_page + 1 })"
                                class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 transition-colors"
                                :class="{ 'rounded-r-md': props.newsletters.current_page >= props.newsletters.last_page }"
                                title="Next Page"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </Link>
                            
                            <!-- Last Page -->
                            <Link v-if="props.newsletters.current_page < props.newsletters.last_page"
                                :href="route('admin.newsletters.index', { page: props.newsletters.last_page })"
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