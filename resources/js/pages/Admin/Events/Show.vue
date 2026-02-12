<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Calendar, ArrowLeft, Image, Star, ExternalLink } from 'lucide-vue-next';

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

const props = defineProps<{ event: EventItem }>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: route('admin.dashboard') },
    { title: 'Event Management', href: route('admin.events.index') },
    { title: 'Event Details', href: route('admin.events.show', props.event.id) },
];
</script>

<template>
    <Head title="Event Details" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-6">
            <div class="bg-gradient-to-r from-green-600 to-yellow-500 rounded-xl p-6 text-white">
                <div class="flex items-center gap-4">
                    <Link
                        :href="route('admin.events.index')"
                        class="inline-flex items-center gap-2 text-green-100 hover:text-white transition-colors"
                    >
                        <ArrowLeft class="w-5 h-5" />
                        Back to Event Management
                    </Link>
                </div>
                <h1 class="text-3xl font-bold mt-4">Event Details</h1>
                <p class="text-green-100 mt-2">
                    <span class="text-green-300">{{ event.title_primary }}</span>
                    <span class="text-yellow-200"> {{ event.title_secondary }}</span>
                    <span v-if="event.title_suffix"> {{ event.title_suffix }}</span>
                </p>
            </div>

            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6 space-y-6">
                    <div class="flex items-center gap-4 p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                        <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                            <Calendar class="w-6 h-6 text-green-600 dark:text-green-400" />
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Title</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white">
                                <span class="text-green-600 dark:text-green-400">{{ event.title_primary }}</span>
                                <span class="text-yellow-500 dark:text-yellow-400"> {{ event.title_secondary }}</span>
                                <span v-if="event.title_suffix"> {{ event.title_suffix }}</span>
                            </p>
                        </div>
                        <span
                            class="inline-flex px-3 py-1 text-sm font-semibold rounded-full"
                            :class="event.status === 'published' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400'"
                        >
                            {{ event.status === 'published' ? 'Published' : 'Draft' }}
                        </span>
                        <span v-if="event.show_on_homepage" class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 rounded-full text-sm font-semibold">
                            <Star class="w-4 h-4" /> Homepage
                        </span>
                    </div>

                    <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Description</p>
                        <p class="text-gray-900 dark:text-white">{{ event.description }}</p>
                    </div>

                    <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <div class="p-3 bg-gray-200 dark:bg-gray-700 rounded-lg">
                            <Image class="w-6 h-6 text-gray-600 dark:text-gray-400" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Image</p>
                            <p class="text-gray-900 dark:text-white font-mono text-sm">{{ event.image_path }}</p>
                        </div>
                    </div>

                    <div v-if="event.features && event.features.length" class="space-y-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Feature bullets</p>
                        <div class="space-y-2">
                            <div
                                v-for="(feat, i) in event.features"
                                :key="i"
                                class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg"
                            >
                                <span class="font-semibold text-gray-900 dark:text-white min-w-[120px]">{{ feat.title }}</span>
                                <span class="text-gray-600 dark:text-gray-300">{{ feat.description }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">CTA</p>
                            <p class="text-gray-900 dark:text-white font-medium">{{ event.cta_text }}</p>
                            <p v-if="event.cta_link" class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ event.cta_link }}</p>
                            <p v-else class="text-sm text-gray-500 dark:text-gray-400 mt-1">Reservation page</p>
                        </div>
                        <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Sort order</p>
                            <p class="text-gray-900 dark:text-white">{{ event.sort_order }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Created</p>
                            <p class="text-sm text-gray-900 dark:text-white">{{ new Date(event.created_at).toLocaleString() }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Updated</p>
                            <p class="text-sm text-gray-900 dark:text-white">{{ new Date(event.updated_at).toLocaleString() }}</p>
                        </div>
                    </div>

                    <div class="flex justify-end gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <Link
                            :href="route('admin.events.index')"
                            class="px-6 py-3 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700"
                        >
                            Back to List
                        </Link>
                        <Link
                            :href="route('admin.events.edit', event.id)"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700"
                        >
                            <ExternalLink class="w-4 h-4" />
                            Edit Event
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
