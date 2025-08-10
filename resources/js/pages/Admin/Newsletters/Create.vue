<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { Mail, ArrowLeft } from 'lucide-vue-next';

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
        title: 'Add Subscriber',
        href: route('admin.newsletters.create'),
    },
];

const form = useForm({
    email: '',
    source: 'admin'
});

const submit = () => {
    form.post(route('admin.newsletters.store'), {
        onSuccess: () => {
            form.reset();
        }
    });
};
</script>

<template>
    <Head title="Add Newsletter Subscriber" />

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
                <h1 class="text-3xl font-bold mt-4">Add Newsletter Subscriber</h1>
                <p class="text-blue-100 mt-2">Add a new email to your newsletter subscription list</p>
            </div>

            <!-- Form Container -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <form @submit.prevent="submit" class="p-6">
                    <div class="space-y-6">
                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Email Address *
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <Mail class="h-5 w-5 text-gray-400" />
                                </div>
                                <input
                                    v-model="form.email"
                                    type="email"
                                    id="email"
                                    required
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors dark:bg-gray-800 dark:text-white dark:placeholder-gray-400"
                                    placeholder="Enter email address"
                                />
                            </div>
                            <p v-if="form.errors.email" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                {{ form.errors.email }}
                            </p>
                        </div>

                        <!-- Source Field -->
                        <div>
                            <label for="source" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Source
                            </label>
                            <select
                                v-model="form.source"
                                id="source"
                                class="block w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors dark:bg-gray-800 dark:text-white"
                            >
                                <option value="admin">Admin</option>
                                <option value="website">Website</option>
                                <option value="footer">Footer</option>
                            </select>
                            <p v-if="form.errors.source" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                {{ form.errors.source }}
                            </p>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <Link :href="route('admin.newsletters.index')"
                            class="px-6 py-3 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 dark:focus:ring-gray-400 transition-colors">
                            Cancel
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors flex items-center gap-2"
                        >
                            <span v-if="form.processing">Adding...</span>
                            <span v-else>Add Subscriber</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template> 