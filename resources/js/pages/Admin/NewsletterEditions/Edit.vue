<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { ArrowLeft, Send, Mail } from 'lucide-vue-next';
import { ref } from 'vue';

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

const props = defineProps<{
    edition: Edition;
    flash?: { success?: string; error?: string };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: route('admin.dashboard') },
    { title: 'Newsletter Editions', href: route('admin.newsletter-editions.index') },
    { title: 'Edit Edition', href: route('admin.newsletter-editions.edit', props.edition.id) },
];

const form = useForm({
    subject: props.edition.subject,
    body: props.edition.body,
    scheduled_at: props.edition.scheduled_at ? new Date(props.edition.scheduled_at).toISOString().slice(0, 16) : '',
    status: props.edition.status as 'draft' | 'scheduled',
});

const testEmail = ref('');
const testSending = ref(false);

const submit = () => {
    form.put(route('admin.newsletter-editions.update', props.edition.id));
};

const sendTest = () => {
    if (!testEmail.value.trim()) return;
    testSending.value = true;
    router.post(route('admin.newsletter-editions.send-test', props.edition.id), { email: testEmail.value }, {
        preserveScroll: true,
        onFinish: () => { testSending.value = false; },
    });
};

const sendToAll = () => {
    if (confirm('Send this newsletter to all active subscribers now?')) {
        router.post(route('admin.newsletter-editions.send', props.edition.id));
    }
};

const isSendable = props.edition.status === 'draft' || props.edition.status === 'scheduled';
</script>

<template>
    <Head title="Edit Newsletter Edition" />

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
                <h1 class="text-3xl font-bold mt-4">Edit Newsletter Edition</h1>
                <p class="text-emerald-100 mt-2">{{ edition.subject }}</p>
            </div>

            <div v-if="props.flash?.success" class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-400 p-4 rounded-lg">
                <p class="text-sm text-green-700 dark:text-green-300">{{ props.flash.success }}</p>
            </div>
            <div v-if="props.flash?.error || form.errors.error" class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400 p-4 rounded-lg">
                <p class="text-sm text-red-700 dark:text-red-300">{{ props.flash?.error || form.errors.error }}</p>
            </div>

            <!-- Send test / Send to list -->
            <div v-if="isSendable" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Send</h2>
                <div class="flex flex-wrap items-end gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <label for="test_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Send test to</label>
                        <div class="flex gap-2">
                            <input
                                id="test_email"
                                v-model="testEmail"
                                type="email"
                                placeholder="your@email.com"
                                class="flex-1 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white px-4 py-2"
                            />
                            <button
                                type="button"
                                @click="sendTest"
                                :disabled="testSending || !testEmail.trim()"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 disabled:opacity-50"
                            >
                                <Mail class="w-4 h-4" /> {{ testSending ? 'Sending...' : 'Send test' }}
                            </button>
                        </div>
                    </div>
                    <button
                        type="button"
                        @click="sendToAll"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700"
                    >
                        <Send class="w-4 h-4" /> Send to all subscribers
                    </button>
                </div>
            </div>

            <div v-if="!isSendable" class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-4">
                <p class="text-sm text-amber-800 dark:text-amber-200">This edition has already been sent and cannot be edited.</p>
            </div>

            <div v-if="isSendable" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <form @submit.prevent="submit" class="p-6 space-y-6">
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Subject line</label>
                        <input
                            id="subject"
                            v-model="form.subject"
                            type="text"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white px-4 py-2 focus:ring-2 focus:ring-emerald-500"
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
                            {{ form.processing ? 'Saving...' : 'Update Edition' }}
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
