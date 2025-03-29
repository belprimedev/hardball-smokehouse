<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Menu',
        href: '/menu.index',
    },
];

const props = defineProps({
  menuItems: Object, // Paginated object from Laravel
  categories: Array
});
console.log(props.menuItems);
// Handle page navigation
const goToPage = (url) => {
  if (url) {
    router.get(url);
  }
};

// Navigate to the create page
const createItem = () => {
  router.get('/menu-items/create');
};

const editItem = (item) => {
  router.get(`/menu-items/${item.id}/edit`);
};

const newItem = ref({
    name: "",
    description: "",
    price: "",
    category_id: "", 
    image_path: "",
});

const deleteItem = (id) => {
    if (confirm("Are you sure you want to delete this item?")) {
        router.delete(`/menu-items/${id}`);
    }
};
</script>

<template>
    <Head title="Reservation" />

    <AppLayout :breadcrumbs="breadcrumbs">

        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex flex-col">
                <div class="overflow-x-auto">
                    <div
                                class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-900 dark:border-neutral-700">
                                <!-- Header -->
                                <div
                                    class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200 dark:border-neutral-700">
                                    <div>
                                        <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                                            Food Listing
                                        </h2>
                                        <p class="text-sm text-yellow-500 dark:text-neutral-400">
                                            List of all food & beverage items.
                                            
                                        </p>
                                    </div>

                                    <div>
                                        <div class="inline-flex gap-x-2">
                                            <!-- <a class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                                                href="#">
                                                View all
                                            </a> -->

                                            <button @click="createItem" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-green-600 text-white hover:bg-green-700 focus:outline-none focus:bg-green-700 disabled:opacity-50 disabled:pointer-events-none">
                                                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg"
                                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path d="M5 12h14" />
                                                    <path d="M12 5v14" />
                                                </svg>
                                                Create
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Header -->

                                <!-- Table -->
                                <table class="min-w-full h-80 divide-y divide-gray-200 dark:divide-neutral-700">
                                    <thead class="bg-gray-50 dark:bg-neutral-900">
                                        <tr>
                                            <th scope="col" class="ps-6 py-3 text-start">
                                                <label for="hs-at-with-checkboxes-main" class="flex">
                                                    <input type="checkbox"
                                                        class="shrink-0 border-gray-300 rounded text-green-600 focus:ring-green-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-600 dark:checked:bg-green-500 dark:checked:border-green-500 dark:focus:ring-offset-gray-800"
                                                        id="hs-at-with-checkboxes-main" />
                                                    <span class="sr-only">Checkbox</span>
                                                </label>
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
                                                        Price
                                                    </span>
                                                </div>
                                            </th>

                                            <th scope="col" class="px-6 py-3 text-start">
                                                <div class="flex items-center gap-x-2">
                                                    <span
                                                        class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                                        Details
                                                    </span>
                                                </div>
                                            </th>

                                            <th scope="col" class="px-6 py-3 text-start">
                                                <div class="flex items-center gap-x-2">
                                                    <span
                                                        class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                                        Category
                                                    </span>
                                                </div>
                                            </th>

                                            <th scope="col" class="px-6 py-3 text-start">
                                                <div class="flex items-center gap-x-2">
                                                    <span
                                                        class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                                        Created
                                                    </span>
                                                </div>
                                            </th>

                                            <th scope="col" class="px-6 py-3 text-end"></th>
                                        </tr>
                                    </thead>

                                    <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                                        <!-- <tr v-for="item in menuItems" :key="item.id"> -->
                                            <tr v-for="item in menuItems.data" :key="item.id">
                                            <td class="size-px whitespace-nowrap">
                                                <div class="ps-6 py-3">
                                                    <label for="hs-at-with-checkboxes-1" class="flex">
                                                        <input type="checkbox"
                                                            class="shrink-0 border-gray-300 rounded text-green-600 focus:ring-green-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-600 dark:checked:bg-green-500 dark:checked:border-green-500 dark:focus:ring-offset-gray-800"
                                                            id="hs-at-with-checkboxes-1" />
                                                        <span class="sr-only">Checkbox</span>
                                                    </label>
                                                </div>
                                            </td>

                                            <td class="size-px whitespace-nowrap">
                                                <div class="px-6 py-3">
                                                    <div class="flex items-center gap-x-2">
                                                        <img class="inline-block size-6 rounded-full"
                                                            src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=2960&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                                                            alt="Avatar" />
                                                        <div class="grow">
                                                            <span class="text-sm text-gray-600 dark:text-neutral-400">{{
                                                                item.name }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="size-px whitespace-nowrap">
                                                <div class="px-6 py-3">
                                                    <span class="text-sm text-gray-600 dark:text-neutral-400">£{{
                                                        item.price }}</span>
                                                </div>
                                            </td>
                                            <td class="size-px whitespace-nowrap">
                                                <div class="px-6 py-3">
                                                    <button type="button"
                                                        class="py-2 px-3 truncate text-clip inline-flex items-center gap-x-2 text-xs rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800 dark:focus:bg-neutral-800">
                                                        {{ item.description }}
                                                        <svg class="shrink-0 size-4 text-gray-400 dark:text-neutral-600"
                                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round">
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
                                                        <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg"
                                                            width="16" height="16" fill="currentColor"
                                                            viewBox="0 0 16 16">
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
                                            <td class="px-5 flex justify-end h-full mx-auto items-center">
                                                <button @click="editItem(item)" 
                                                    class="bg-orange-500 rounded-l-md p-2 text-white hover:shadow-lg text-xs font-thin">
                                                    Edit
                                                </button>
                                                <button @click="deleteItem(item.id)"
                                                    class="bg-red-600 rounded-r-md p-2 text-white hover:shadow-lg text-xs font-thin">
                                                    Remove
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!-- End Table -->

                                
                                <!-- Footer -->
                                <div
                                    class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-t border-gray-200 dark:border-neutral-700">
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
                                                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg"
                                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path d="m15 18-6-6 6-6" />
                                                </svg>
                                                Prev
                                            </button>

                                            <span class="px-1">Page {{ menuItems.current_page }} of {{ menuItems.last_page }}</span>

                                            <button type="button" v-if="menuItems.next_page_url" @click="goToPage(menuItems.next_page_url)"
                                                class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                                                Next
                                                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg"
                                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path d="m9 18 6-6-6-6" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Footer -->
                                 
                            </div>
                </div>
                <!-- ====== Table One End -->

            </div>
        </div>
    </AppLayout>
</template>
