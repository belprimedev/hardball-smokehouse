<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Plus, Edit, Trash2, Send, Mail, Calendar, FileText } from 'lucide-vue-next';

interface Edition {
    id: number;
    subject: string;
    body: string;
    scheduled_at: string | null;
    sent_at: string | null;
    status: string;
    created_at: string;
    updated_at: string;
}

interface PaginatedData {
    data: Edition[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

const props = defineProps<{
    editions: PaginatedData;
    flash?: { success?: string; error?: string };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: route('admin.dashboard') },
    { title: 'Newsletter Editions', href: route('admin.newsletter-editions.index') },
];

const deleteEdition = (id: number) => {
    if (confirm('Are you sure you want to delete this newsletter edition?')) {
        router.delete(route('admin.newsletter-editions.destroy', id));
    }
};

const sendEdition = (id: number) => {
    if (confirm('Send this newsletter to all active subscribers now?')) {
        router.post(route('admin.newsletter-editions.send', id));
    }
};

const stats = {
    total: props.editions.total,
    draft: props.editions.data.filter((e) => e.status === 'draft').length,
    scheduled: props.editions.data.filter((e) => e.status === 'scheduled').length,
    sent: props.editions.data.filter((e) => e.status === 'sent').length,
};

const getVisiblePages = () => {
    const pages: (number | '...')[] = [];
    const current = props.editions.current_page;
    const last = props.editions.last_page;
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

const formatDate = (d: string | null) => (d ? new Date(d).toLocaleDateString(undefined, { dateStyle: 'medium' }) : '—');
</script>

<template>
    <Head title="Newsletter Editions" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-6">
            <div class="bg-gradient-to-r from-emerald-600 to-teal-700 rounded-xl p-6 text-white">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">Newsletter Editions</h1>
                        <p class="text-emerald-100">Create and send weekly newsletters to your subscribers</p>
                    </div>
                    <Link
                        :href="route('admin.newsletter-editions.create')"
                        class="inline-flex items-center gap-2 bg-white text-emerald-700 px-6 py-3 rounded-lg font-bold hover:bg-gray-100 transition-colors"
                    >
                        <Plus class="w-5 h-5" />
                        New Edition
                    </Link>
                </div>
            </div>

            <div v-if="props.flash?.success" class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-400 p-4 rounded-lg">
                <p class="text-sm text-green-700 dark:text-green-300">{{ props.flash.success }}</p>
            </div>
            <div v-if="props.flash?.error" class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400 p-4 rounded-lg">
                <p class="text-sm text-red-700 dark:text-red-300">{{ props.flash.error }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ stats.total }}</p>
                        </div>
                        <div class="p-3 bg-gray-100 dark:bg-gray-700 rounded-lg">
                            <FileText class="w-6 h-6 text-gray-600 dark:text-gray-400" />
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Draft</p>
                            <p class="text-3xl font-bold text-amber-600">{{ stats.draft }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Scheduled</p>
                            <p class="text-3xl font-bold text-blue-600">{{ stats.scheduled }}</p>
                        </div>
                        <Calendar class="w-8 h-8 text-blue-500" />
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Sent</p>
                            <p class="text-3xl font-bold text-green-600">{{ stats.sent }}</p>
                        </div>
                        <Send class="w-8 h-8 text-green-500" />
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Editions</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Subject</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Scheduled</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Sent</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <tr v-for="edition in props.editions.data" :key="edition.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white max-w-xs truncate">{{ edition.subject }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        :class="{
                                            'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400': edition.status === 'draft',
                                            'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400': edition.status === 'scheduled',
                                            'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400': edition.status === 'sent',
                                        }"
                                        class="px-2 py-1 text-xs font-medium rounded-full"
                                    >
                                        {{ edition.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ formatDate(edition.scheduled_at) }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ formatDate(edition.sent_at) }}</td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <Link
                                        v-if="edition.status !== 'sent'"
                                        :href="route('admin.newsletter-editions.edit', edition.id)"
                                        class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 dark:text-blue-400"
                                    >
                                        <Edit class="w-4 h-4" /> Edit
                                    </Link>
                                    <button
                                        v-if="edition.status !== 'sent'"
                                        type="button"
                                        @click="sendEdition(edition.id)"
                                        class="inline-flex items-center gap-1 text-green-600 hover:text-green-800 dark:text-green-400"
                                    >
                                        <Send class="w-4 h-4" /> Send
                                    </button>
                                    <button
                                        v-if="edition.status !== 'sent'"
                                        type="button"
                                        @click="deleteEdition(edition.id)"
                                        class="inline-flex items-center gap-1 text-red-600 hover:text-red-800 dark:text-red-400"
                                    >
                                        <Trash2 class="w-4 h-4" /> Delete
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-if="props.editions.last_page > 1" class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Showing {{ (props.editions.current_page - 1) * props.editions.per_page + 1 }} to
                        {{ Math.min(props.editions.current_page * props.editions.per_page, props.editions.total) }} of
                        {{ props.editions.total }}
                    </p>
                    <div class="flex gap-2">
                        <template v-for="page in getVisiblePages()" :key="page">
                            <Link
                                v-if="typeof page === 'number'"
                                :href="route('admin.newsletter-editions.index', { page })"
                                class="px-3 py-1 rounded border"
                                :class="page === props.editions.current_page
                                    ? 'bg-emerald-600 text-white border-emerald-600'
                                    : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300'"
                            >
                                {{ page }}
                            </Link>
                            <span v-else class="px-2 text-gray-500">...</span>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
