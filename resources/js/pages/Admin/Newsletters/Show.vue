<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Mail, ArrowLeft, Calendar, User, Tag } from 'lucide-vue-next';

interface Newsletter {
    id: number;
    email: string;
    status: string;
    source: string;
    created_at: string;
    updated_at: string;
}

const props = defineProps<{
    newsletter: Newsletter;
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
    {
        title: 'Subscriber Details',
        href: route('admin.newsletters.show', props.newsletter.id),
    },
];
</script>

<template>
    <Head title="Newsletter Subscriber Details" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-6">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-cyan-600 rounded-xl p-6 text-white">
                <div class="flex items-center gap-4">
                    <Link :href="route('admin.newsletters.index')"
                        class="inline-flex items-center gap-2 text-blue-100 hover:text-white transition-colors">
                        <ArrowLeft class="w-5 h-5" />
                        Back to Newsletter Management
                    </Link>
                </div>
                <h1 class="text-3xl font-bold mt-4">Newsletter Subscriber Details</h1>
                <p class="text-blue-100 mt-2">View detailed information about this subscriber</p>
            </div>

            <!-- Details Container -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6">
                    <div class="space-y-6">
                        <!-- Email Information -->
                        <div class="flex items-center gap-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                                <Mail class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Email Address</p>
                                <p class="text-lg font-bold text-gray-900 dark:text-white">{{ props.newsletter.email }}</p>
                            </div>
                        </div>

                        <!-- Status Information -->
                        <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                                <User class="w-6 h-6 text-green-600 dark:text-green-400" />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Status</p>
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full"
                                    :class="props.newsletter.status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400'">
                                    {{ props.newsletter.status.charAt(0).toUpperCase() + props.newsletter.status.slice(1) }}
                                </span>
                            </div>
                        </div>

                        <!-- Source Information -->
                        <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                                <Tag class="w-6 h-6 text-purple-600 dark:text-purple-400" />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Source</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ props.newsletter.source.charAt(0).toUpperCase() + props.newsletter.source.slice(1) }}
                                </p>
                            </div>
                        </div>

                        <!-- Timestamps -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                                    <Calendar class="w-6 h-6 text-yellow-600 dark:text-yellow-400" />
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Created</p>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ new Date(props.newsletter.created_at).toLocaleDateString() }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ new Date(props.newsletter.created_at).toLocaleTimeString() }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                <div class="p-3 bg-orange-100 dark:bg-orange-900/30 rounded-lg">
                                    <Calendar class="w-6 h-6 text-orange-600 dark:text-orange-400" />
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Last Updated</p>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ new Date(props.newsletter.updated_at).toLocaleDateString() }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ new Date(props.newsletter.updated_at).toLocaleTimeString() }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Subscriber ID -->
                        <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <div class="p-3 bg-gray-100 dark:bg-gray-700 rounded-lg">
                                <User class="w-6 h-6 text-gray-600 dark:text-gray-400" />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Subscriber ID</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">#{{ props.newsletter.id }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <Link :href="route('admin.newsletters.index')"
                            class="px-6 py-3 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 dark:focus:ring-gray-400 transition-colors">
                            Back to List
                        </Link>
                        <Link :href="route('admin.newsletters.edit', props.newsletter.id)"
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                            Edit Subscriber
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template> 