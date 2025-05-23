<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

interface MenuItem {
    id: number;
    name: string;
    description: string | null;
    price: string;
    image_path: string | null;
    category?: {
        name: string;
    };
    created_at: string;
}

interface PaginatedData {
    data: MenuItem[];
    current_page: number;
    last_page: number;
    total: number;
    prev_page_url: string | null;
    next_page_url: string | null;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Menu',
        href: '/menu.index',
    },
];

const props = defineProps({
    menuItems: {
        type: Object as () => PaginatedData,
        required: true
    },
    categories: {
        type: Array,
        required: true
    }
});
console.log(props.menuItems);
// Handle page navigation
const goToPage = (url: string | null) => {
    if (url) {
        router.get(url);
    }
};

// Navigate to the create page
const createItem = () => {
    router.get('/menu-items/create');
};

const editItem = (item: MenuItem) => {
    router.get(`/menu-items/${item.id}/edit`);
};

const deleteItem = (id: number) => {
    if (confirm("Are you sure you want to delete this item?")) {
        router.delete(`/menu-items/${id}`);
    }
};

const searchQuery = ref('');

const filteredItems = computed(() => {
    if (!props.menuItems?.data) return [];

    return props.menuItems.data.filter(item => {
        const searchLower = searchQuery.value.toLowerCase();
        return (
            (item.name?.toLowerCase() || '').includes(searchLower) ||
            (item.description?.toLowerCase() || '').includes(searchLower) ||
            (item.category?.name?.toLowerCase() || '').includes(searchLower)
        );
    });
});
</script>

<template>

    <Head title="Reservation" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col h-full overflow-hidden p-4">
            <div
                class="flex justify-between bg-white rounded-xl shadow-sm dark:bg-neutral-900 dark:border-neutral-700 overflow-hidden">
                <!-- Header - Fixed -->
                <div class="shrink-0 bg-white border-b border-gray-200 dark:bg-neutral-900 dark:border-neutral-700">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                        Food Listing
                    </h2>
                    <p class="text-sm text-yellow-500 dark:text-neutral-400">
                        List of all food & beverage items.
                    </p>
                </div>

                <div class="flex items-center gap-4">
                    <!-- Search Input -->
                    <div class="relative">
                        <input type="text" v-model="searchQuery" placeholder="Search items..."
                            class="py-2 px-3 ps-9 block w-full border-gray-500 ring-1 ring-emerald-200 rounded-lg text-sm focus:border-green-500 focus:ring-green-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:ring-neutral-600" />
                        <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-3">
                            <svg class="size-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8" />
                                <path d="m21 21-4.3-4.3" />
                            </svg>
                        </div>
                    </div>

                    <div class="inline-flex gap-x-2">
                        <button @click="createItem"
                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-green-600 text-white hover:bg-green-700 focus:outline-none focus:bg-green-700 disabled:opacity-50 disabled:pointer-events-none">
                            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14" />
                                <path d="M12 5v14" />
                            </svg>
                            Create
                        </button>
                    </div>
                </div>
            </div>

            <!-- Table Container - Scrollable -->
            <div class="flex-1 overflow-auto p-4 border-t border-gray-200 dark:border-neutral-700">
                <div class="overflow-x-auto">
                    <div class="min-w-full inline-block align-middle">
                        <table class="min-w-[800px] w-full table-auto divide-y divide-gray-200 dark:divide-neutral-700">
                            <thead class="bg-gray-50 dark:bg-neutral-900">
                                <tr>
                                    <th scope="col" class="w-[200px] px-6 py-3 text-start">
                                        <div class="flex items-center gap-x-2">
                                            <span
                                                class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                                Name
                                            </span>
                                        </div>
                                    </th>

                                    <th scope="col" class="w-[100px] px-6 py-3 text-start">
                                        <div class="flex items-center gap-x-2">
                                            <span
                                                class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                                Price
                                            </span>
                                        </div>
                                    </th>

                                    <th scope="col" class="w-[250px] px-6 py-3 text-start">
                                        <div class="flex items-center gap-x-2">
                                            <span
                                                class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                                Details
                                            </span>
                                        </div>
                                    </th>

                                    <th scope="col" class="w-[150px] px-6 py-3 text-start">
                                        <div class="flex items-center gap-x-2">
                                            <span
                                                class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                                Category
                                            </span>
                                        </div>
                                    </th>

                                    <th scope="col" class="w-[150px] px-6 py-3 text-start">
                                        <div class="flex items-center gap-x-2">
                                            <span
                                                class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                                Created
                                            </span>
                                        </div>
                                    </th>

                                    <th scope="col" class="w-[150px] px-6 py-3 text-end"></th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                                <tr v-for="item in filteredItems" :key="item.id">
                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-6 py-3">
                                            <div class="flex items-center gap-x-2">
                                                <img class="inline-block size-6 rounded-full object-cover"
                                                    :src="item.image_path ? `/storage/${item.image_path}` : '/favicon.ico'"
                                                    :alt="item.name" />
                                                <div class="grow">
                                                    <span class="text-sm text-gray-600 dark:text-neutral-400">{{
                                                        item.name }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-6 py-3">
                                            <span class="text-sm text-gray-600 dark:text-neutral-400">£{{ item.price
                                                }}</span>
                                        </div>
                                    </td>

                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-6 py-3">
                                            <button type="button"
                                                class="py-2 px-3 inline-flex items-center gap-x-2 text-xs rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800 dark:focus:bg-neutral-800">
                                                <span class="truncate max-w-[200px]">{{ item.description }}</span>
                                                <svg class="shrink-0 size-4 text-gray-400 dark:text-neutral-600"
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <rect width="8" height="4" x="8" y="2" rx="1" ry="1" />
                                                    <path
                                                        d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>

                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-6 py-3">
                                            <span
                                                class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-teal-100 text-teal-800 rounded-full dark:bg-teal-500/10 dark:text-teal-500">
                                                <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" width="16"
                                                    height="16" fill="currentColor" viewBox="0 0 16 16">
                                                    <path
                                                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                                </svg>
                                                {{ item.category ? item.category.name : 'No Category' }}
                                            </span>
                                        </div>
                                    </td>

                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-6 py-3">
                                            <span class="text-sm text-gray-600 dark:text-neutral-400">28 Dec,
                                                12:12</span>
                                        </div>
                                    </td>

                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-6 py-3 text-end">
                                            <button @click="editItem(item)"
                                                class="bg-orange-500 rounded-l-md p-2 text-white hover:shadow-lg text-xs font-thin">
                                                Edit
                                            </button>
                                            <button @click="deleteItem(item.id)"
                                                class="bg-red-600 rounded-r-md p-2 text-white hover:shadow-lg text-xs font-thin">
                                                Remove
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Footer - Fixed -->
            <div class="shrink-0 flex justify-between bg-white border-t border-gray-200 px-6 py-4 dark:bg-neutral-900 dark:border-neutral-700">
                <div>
                    <p class="text-sm text-gray-600 dark:text-neutral-400">
                        <span class="font-semibold text-gray-800 dark:text-neutral-200">{{ menuItems.total }}</span>
                        results
                    </p>
                </div>

                <div>
                    <div class="inline-flex items-center gap-x-2">
                        <button type="button" v-if="menuItems.prev_page_url" @click="goToPage(menuItems.prev_page_url)"
                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="m15 18-6-6 6-6" />
                            </svg>
                            Prev
                        </button>

                        <span class="px-1">Page {{ menuItems.current_page }} of {{ menuItems.last_page }}</span>

                        <button type="button" v-if="menuItems.next_page_url" @click="goToPage(menuItems.next_page_url)"
                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                            Next
                            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="m9 18 6-6-6-6" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
