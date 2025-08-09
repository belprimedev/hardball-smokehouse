<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Menu Category',
        href: '/menu-category.index',
    },
];

const props = defineProps<{
    categories: {
        data: Array<{
            id: number;
            name: string;
            description: string;
            display_image?: string;
        }>;
        prev_page_url?: string;
        next_page_url?: string;
    };
}>();

const deleteCategory = (id: number) => {
    if (confirm("Are you sure you want to delete this category?")) {
        router.delete(route("menu-category.destroy", id));
    }
};
</script>

<template>
    <Head title="Menu Categories" />

    <AppLayout :breadcrumbs="breadcrumbs">

        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex flex-col">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-900 dark:border-neutral-700">
                        <!-- Header -->
                        <div
                            class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200 dark:border-neutral-700">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                                    Category Listing
                                </h2>
                                <p class="text-sm text-yellow-500 dark:text-neutral-400">
                                    List of all menu categories.
                                </p>
                            </div>

                            <div>
                                <div class="inline-flex gap-x-2">
                                    <a :href="route('menu-category.create')" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-green-600 text-white hover:bg-green-700 focus:outline-none focus:bg-green-700 disabled:opacity-50 disabled:pointer-events-none">
                                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M5 12h14" />
                                            <path d="M12 5v14" />
                                        </svg>
                                        Create
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- End Header -->
                        <div class=" mx-auto">
                            <table class="min-w-full h-80 divide-y divide-gray-200 dark:divide-neutral-700">
                                <thead class="bg-gray-50 dark:bg-neutral-900">
                                    <tr class="bg-gray-100">
                                        <th scope="col" class="px-6 py-3 text-start">
                                        <div class="flex items-center gap-x-2">
                                            <span
                                                class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                                Image
                                            </span>
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-start">
                                        <div class="flex items-center gap-x-2">
                                            <span
                                                class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                                Name
                                            </span>
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-start">
                                        <div class="flex items-center gap-x-2">
                                            <span
                                                class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                                Description
                                            </span>
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-end"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                                    <tr v-for="category in props.categories.data" :key="category.id">
                                        <td class="size-px whitespace-nowrap">
                                            <div class="px-6 py-3">
                                                <div v-if="category.display_image" class="w-16 h-16 rounded-lg overflow-hidden">
                                                    <img 
                                                        :src="`/storage/${category.display_image}`" 
                                                        :alt="category.name"
                                                        class="w-full h-full object-cover"
                                                    />
                                                </div>
                                                <div v-else class="w-16 h-16 rounded-lg bg-gray-200 flex items-center justify-center">
                                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="size-px whitespace-nowrap">
                                            <div class="px-6 py-3">
                                                <span class="text-sm text-gray-600 dark:text-neutral-400">{{
                                                    category.name }}</span>
                                            </div>
                                        </td>
                                        <td class="size-px whitespace-nowrap">
                                            <div class="px-6 py-3">
                                                <span class="text-sm text-gray-600 dark:text-neutral-400">{{
                                                    category.description }}</span>
                                            </div>
                                        </td>
                                        <td class="px-5 flex justify-end h-full mx-auto items-center">
                                            <a :href="route('menu-category.edit', category.id)" 
                                                class="bg-orange-500 items-center rounded-l-md p-2 text-white hover:shadow-lg text-xs font-thin">
                                                Edit
                                            </a>
                                            <a @click="deleteCategory(category.id)"
                                                class="bg-red-600 rounded-r-md p-2 text-white hover:shadow-lg text-xs font-thin cursor-pointer">
                                                Remove
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="mt-4">
                                <button v-if="props.categories.prev_page_url" @click="router.get(props.categories.prev_page_url)" class="mr-2 px-4 py-2 bg-gray-200">Prev</button>
                                <button v-if="props.categories.next_page_url" @click="router.get(props.categories.next_page_url)" class="px-4 py-2 bg-gray-200">Next</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
