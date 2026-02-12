<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: route('admin.dashboard') },
    { title: 'Newsletter Editions', href: route('admin.newsletter-editions.index') },
    { title: 'New Edition', href: route('admin.newsletter-editions.create') },
];

const form = useForm({
    subject: '',
    body: '',
    scheduled_at: '' as string,
    status: 'draft' as 'draft' | 'scheduled',
});

const submit = () => {
    form.post(route('admin.newsletter-editions.store'), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="New Newsletter Edition" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-6">
            <div class="bg-gradient-to-r from-emerald-600 to-teal-700 rounded-xl p-6 text-white">
                <div class="flex items-center gap-4">
                    <Link
                        :href="route('admin.newsletter-editions.index')"
                        class="inline-flex items-center gap-2 text-emerald-100 hover:text-white transition-colors"
                    >
                        <ArrowLeft class="w-5 h-5" />
                        Back to Editions
                    </Link>
                </div>
                <h1 class="text-3xl font-bold mt-4">New Newsletter Edition</h1>
                <p class="text-emerald-100 mt-2">Create a weekly newsletter. You can use HTML in the body.</p>
            </div>

            <div v-if="form.errors.error" class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400 p-4 rounded-lg">
                <p class="text-sm text-red-700 dark:text-red-300">{{ form.errors.error }}</p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <form @submit.prevent="submit" class="p-6 space-y-6">
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Subject line</label>
                        <input
                            id="subject"
                            v-model="form.subject"
                            type="text"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white px-4 py-2 focus:ring-2 focus:ring-emerald-500"
                            placeholder="e.g. This week at Hardball — new specials & events"
                        />
                        <p v-if="form.errors.subject" class="mt-1 text-sm text-red-600">{{ form.errors.subject }}</p>
                    </div>

                    <div>
                        <label for="body" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Body (HTML allowed)</label>
                        <textarea
                            id="body"
                            v-model="form.body"
                            rows="14"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white px-4 py-2 font-mono text-sm focus:ring-2 focus:ring-emerald-500"
                            placeholder="<p>Hello from Hardball!</p><p>This week we have...</p>"
                        />
                        <p v-if="form.errors.body" class="mt-1 text-sm text-red-600">{{ form.errors.body }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="scheduled_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Schedule send (optional)</label>
                            <input
                                id="scheduled_at"
                                v-model="form.scheduled_at"
                                type="datetime-local"
                                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white px-4 py-2 focus:ring-2 focus:ring-emerald-500"
                            />
                            <p class="mt-1 text-xs text-gray-500">Run the scheduler (e.g. weekly cron) to send scheduled editions.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                            <select
                                v-model="form.status"
                                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white px-4 py-2 focus:ring-2 focus:ring-emerald-500"
                            >
                                <option value="draft">Draft</option>
                                <option value="scheduled">Scheduled</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button
                            type="submit"
                            class="px-6 py-2.5 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 disabled:opacity-50"
                            :disabled="form.processing"
                        >
                            {{ form.processing ? 'Saving...' : 'Save Edition' }}
                        </button>
                        <Link
                            :href="route('admin.newsletter-editions.index')"
                            class="px-6 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700"
                        >
                            Cancel
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
