<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Plus, Eye, Edit, Trash2, Calendar, MapPin, Star } from 'lucide-vue-next';

interface EventFeature {
    title: string;
    description: string;
}

interface EventItem {
    id: number;
    title_primary: string;
    title_secondary: string;
    title_suffix: string | null;
    description: string;
    image_path: string;
    features: EventFeature[] | null;
    cta_text: string;
    cta_link: string | null;
    status: string;
    show_on_homepage: boolean;
    sort_order: number;
    created_at: string;
    updated_at: string;
}

interface PaginatedData {
    data: EventItem[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

const props = defineProps<{
    events: PaginatedData;
    flash?: {
        success?: string;
        error?: string;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: route('admin.dashboard') },
    { title: 'Event Management', href: route('admin.events.index') },
];

const deleteEvent = (id: number) => {
    if (confirm('Are you sure you want to delete this event?')) {
        router.delete(route('admin.events.destroy', id));
    }
};

const stats = {
    total: props.events.total,
    published: props.events.data.filter((e) => e.status === 'published').length,
    draft: props.events.data.filter((e) => e.status === 'draft').length,
    featured: props.events.data.filter((e) => e.show_on_homepage).length,
};

const getVisiblePages = () => {
    const pages: (number | '...')[] = [];
    const current = props.events.current_page;
    const last = props.events.last_page;
    if (last <= 5) {
        for (let i = 1; i <= last; i++) pages.push(i);
    } else {
        pages.push(1);
        if (current > 3) pages.push('...');
        for (let i = Math.max(2, current - 1); i <= Math.min(last - 1, current + 1); i++) pages.push(i);
        if (current < last - 2) pages.push('...');
        pages.push(last);
    }
    return pages;
};
</script>

<template>
    <Head title="Event Management" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-6">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-600 to-yellow-500 rounded-xl p-6 text-white">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">Event Management</h1>
                        <p class="text-green-100">Manage events displayed on the homepage</p>
                    </div>
                    <Link
                        :href="route('admin.events.create')"
                        class="inline-flex items-center gap-2 bg-white text-green-700 px-6 py-3 rounded-lg font-bold hover:bg-gray-100 transition-colors"
                    >
                        <Plus class="w-5 h-5" />
                        Add Event
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
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Events</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ stats.total }}</p>
                        </div>
                        <div class="p-3 bg-gray-100 dark:bg-gray-700 rounded-lg">
                            <Calendar class="w-6 h-6 text-gray-600 dark:text-gray-400" />
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Published</p>
                            <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ stats.published }}</p>
                        </div>
                        <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                            <MapPin class="w-6 h-6 text-green-600 dark:text-green-400" />
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Draft</p>
                            <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ stats.draft }}</p>
                        </div>
                        <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                            <Edit class="w-6 h-6 text-yellow-600 dark:text-yellow-400" />
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">On Homepage</p>
                            <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ stats.featured }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                            <Star class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Events Table -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Events</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Event</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Homepage</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Order</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            <tr v-for="event in props.events.data" :key="event.id" class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ event.title_primary }} {{ event.title_secondary }}
                                        <span v-if="event.title_suffix">{{ event.title_suffix }}</span>
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400 truncate max-w-xs">{{ event.description }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                                        :class="event.status === 'published' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400'"
                                    >
                                        {{ event.status === 'published' ? 'Published' : 'Draft' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span v-if="event.show_on_homepage" class="text-green-600 dark:text-green-400 font-medium">Yes</span>
                                    <span v-else class="text-gray-400">—</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ event.sort_order }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <Link :href="route('admin.events.edit', event.id)" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                            <Edit class="w-4 h-4" />
                                        </Link>
                                        <Link :href="route('admin.events.show', event.id)" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                            <Eye class="w-4 h-4" />
                                        </Link>
                                        <button @click="deleteEvent(event.id)" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                            <Trash2 class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-if="props.events.data.length === 0" class="text-center py-12 text-gray-500 dark:text-gray-400">
                    No events yet. <Link :href="route('admin.events.create')" class="text-green-600 dark:text-green-400 font-medium">Create one</Link>.
                </div>
                <!-- Pagination -->
                <div v-if="props.events.last_page > 1" class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700 dark:text-gray-300">
                            Showing {{ (props.events.current_page - 1) * props.events.per_page + 1 }} to
                            {{ Math.min(props.events.current_page * props.events.per_page, props.events.total) }} of
                            {{ props.events.total }} results
                        </div>
                        <div class="flex items-center space-x-1">
                            <Link
                                v-if="props.events.current_page > 1"
                                :href="route('admin.events.index', { page: props.events.current_page - 1 })"
                                class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700"
                            >
                                Previous
                            </Link>
                            <template v-for="page in getVisiblePages()" :key="page">
                                <span v-if="page === '...'" class="px-3 py-2 text-sm text-gray-500">...</span>
                                <Link
                                    v-else
                                    :href="route('admin.events.index', { page })"
                                    class="px-3 py-2 text-sm font-medium border border-gray-300"
                                    :class="page === props.events.current_page ? 'bg-green-600 text-white border-green-600' : 'text-gray-500 bg-white hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700'"
                                >
                                    {{ page }}
                                </Link>
                            </template>
                            <Link
                                v-if="props.events.current_page < props.events.last_page"
                                :href="route('admin.events.index', { page: props.events.current_page + 1 })"
                                class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700"
                            >
                                Next
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
