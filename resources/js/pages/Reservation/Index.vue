<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';

// Define props to receive data from the backend
const props = defineProps<{
    reservations: {
        data: Array<{
            id: number;
            customer_name: string;
            customer_phone: string;
            customer_email: string;
            reservation_date: string;
            reservation_time: string;
            number_of_guest: number;
            special_request: string;
        }>;
        current_page: number;
        last_page: number;
        total: number;
        prev_page_url: string | null;
        next_page_url: string | null;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Reservation',
        href: '/reservation.index',
    },
];


// Function to navigate to a page
const goToPage = (url: string) => {
	if (url) {
		router.get(url); // Fetch new page using Inertia
	}
};

const createItem = () => {
	router.get('/reservation/create');
};



const deleteItem = (id: number) => {
	if (confirm("Are you sure you want to delete this item?")) {
		router.delete(`/reservation/${id}`);
	}
};
</script>

<template>
    <Head title="Reservation" />

    <AppLayout :breadcrumbs="breadcrumbs">

        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex flex-col">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="p-1.5 min-w-full inline-block align-middle">
                        <div
                            class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-900 dark:border-neutral-700">
                            <!-- Header -->
                            <div
                                class="px-8 py-6 grid gap-4 md:flex md:justify-between md:items-center border-b border-gray-200 dark:border-neutral-700 bg-gradient-to-r from-green-50 to-blue-50 dark:from-neutral-800 dark:to-neutral-700">
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-800 dark:text-neutral-200 flex items-center">
                                        <div class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mr-3">
                                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        Reservation Management
                                    </h2>
                                    <p class="text-sm text-gray-600 dark:text-neutral-400 mt-2">
                                        Manage all customer reservations and booking settings
                                    </p>
                                </div>

                                <div>
                                    <div class="inline-flex gap-x-3">
                                        <button @click="createItem"
                                            class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-green-600 text-white hover:bg-green-700 focus:outline-none focus:bg-green-700 disabled:opacity-50 disabled:pointer-events-none shadow-lg hover:shadow-xl transition-all duration-200">
                                            <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg"
                                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="M5 12h14" />
                                                <path d="M12 5v14" />
                                            </svg>
                                            Create Reservation
                                        </button>
                                        
                                        <a href="/reservation-settings"
                                            class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-gray-200 bg-white text-gray-800 shadow-lg hover:shadow-xl hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 transition-all duration-200">
                                            <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg"
                                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                                                <line x1="12" y1="9" x2="12" y2="13"/>
                                                <line x1="12" y1="17" x2="12.01" y2="17"/>
                                            </svg>
                                            Reservation Settings
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- End Header -->

                            <!-- Table -->
                            <div class="-mx-4 mt-8 sm:-mx-0">
                                <table class="min-w-full h-80 divide-y divide-gray-200 dark:divide-neutral-700">
                                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-neutral-900 dark:to-neutral-800">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-start">
                                                <div class="flex items-center gap-x-2">
                                                    <span
                                                        class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                                        Date
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
                                                        Phone
                                                    </span>
                                                </div>
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-start">
                                                <div class="flex items-center gap-x-2">
                                                    <span
                                                        class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                                        # of Guests
                                                    </span>
                                                </div>
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-end"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                                        <tr v-for="item in props.reservations.data" :key="item.id" class="hover:bg-gray-50 dark:hover:bg-neutral-800 transition-colors duration-200">
                                            <td class="whitespace-nowrap px-5 text-sm ">
                                                <div class="flex items-center">
                                                    <div class="w-[32px] h-[32px] p-0 flex-shrink-0">
                                                        <svg class="fill-current flex p-0 items-center justify-center" width="32" height="32"
                                                            viewBox="0 0 32 32" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M15.7499 2.9812H14.2874V2.36245C14.2874 2.02495 14.0062 1.71558 13.6405 1.71558C13.2749 1.71558 12.9937 1.99683 12.9937 2.36245V2.9812H4.97803V2.36245C4.97803 2.02495 4.69678 1.71558 4.33115 1.71558C3.96553 1.71558 3.68428 1.99683 3.68428 2.36245V2.9812H2.2499C1.29365 2.9812 0.478027 3.7687 0.478027 4.75308V14.5406C0.478027 15.4968 1.26553 16.3125 2.2499 16.3125H15.7499C16.7062 16.3125 17.5218 15.525 17.5218 14.5406V4.72495C17.5218 3.7687 16.7062 2.9812 15.7499 2.9812ZM1.77178 8.21245H4.1624V10.9968H1.77178V8.21245ZM5.42803 8.21245H8.38115V10.9968H5.42803V8.21245ZM8.38115 12.2625V15.0187H5.42803V12.2625H8.38115ZM9.64678 12.2625H12.5999V15.0187H9.64678V12.2625ZM9.64678 10.9968V8.21245H12.5999V10.9968H9.64678ZM13.8374 8.21245H16.228V10.9968H13.8374V8.21245ZM2.2499 4.24683H3.7124V4.83745C3.7124 5.17495 3.99365 5.48433 4.35928 5.48433C4.7249 5.48433 5.00615 5.20308 5.00615 4.83745V4.24683H13.0499V4.83745C13.0499 5.17495 13.3312 5.48433 13.6968 5.48433C14.0624 5.48433 14.3437 5.20308 14.3437 4.83745V4.24683H15.7499C16.0312 4.24683 16.2562 4.47183 16.2562 4.75308V6.94683H1.77178V4.75308C1.77178 4.47183 1.96865 4.24683 2.2499 4.24683ZM1.77178 14.5125V12.2343H4.1624V14.9906H2.2499C1.96865 15.0187 1.77178 14.7937 1.77178 14.5125ZM15.7499 15.0187H13.8374V12.2625H16.228V14.5406C16.2562 14.7937 16.0312 15.0187 15.7499 15.0187Z"
                                                                fill="" />
                                                        </svg>
                                                    </div>
                                                    <div class="">
                                                        <div class="font-medium text-gray-900">{{
                                                            item?.reservation_date || 'N/A' }}</div>
                                                        <div class="mt-1 text-gray-500">{{ item?.reservation_time ||
                                                            'N/A' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="whitespace-nowrap py-5 pl-4 pr-3 text-sm sm:pl-0">
                                                <div class="flex items-center">
                                                    <div class="ml-4">
                                                        <div class="font-medium text-gray-900">{{ item.customer_name
                                                            }} </div>
                                                        <div class="mt-1 text-gray-500">{{ item.customer_email }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="hidden px-3 py-4 text-sm text-gray-500 lg:table-cell">{{
                                                item.customer_phone }}
                                            </td>
                                            <td class="hidden px-3 py-4 text-sm text-gray-500 sm:table-cell">{{
                                                item.number_of_guest }}
                                            </td>
                                            <td class="px-3 py-4 text-sm text-gray-500">{{ item.special_request }}
                                            </td>
                                            <td class="px-5 flex justify-end h-full mx-auto items-center">
                                                <a :href="route('reservation.show', { reservation: item.id })"
                                                    class="bg-yellow-500 rounded-l-md p-2 text-white hover:shadow-lg text-xs font-thin">
                                                    View
                                                </a>
                                                <a :href="route('reservation.edit', { reservation: item.id })"
                                                    class="bg-orange-500 p-2 text-white hover:shadow-lg text-xs font-thin">
                                                    Edit
                                                </a>

                                                
                                            
                                                <a href="#" @click="deleteItem(item.id)"
                                                    class="bg-red-600 rounded-r-md p-2 text-white hover:shadow-lg text-xs font-thin">
                                                    Remove
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!-- Pagination -->
                                <div
                                    class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-t border-gray-200 dark:border-neutral-700">
                                    <div>
                                        <p class="text-sm text-gray-600 dark:text-neutral-400">
                                            <span class="font-semibold text-gray-800 dark:text-neutral-200">{{
                                                props.reservations.total }}</span>
                                            results
                                        </p>
                                    </div>

                                    <div>
                                        <div class="inline-flex items-center gap-x-2">
                                            <button type="button" v-if="props.reservations.prev_page_url"
                                                @click="goToPage(props.reservations.prev_page_url)"
                                                class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                                                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg"
                                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path d="m15 18-6-6 6-6" />
                                                </svg>
                                                Prev
                                            </button>

                                            <span class="px-1">Page {{ props.reservations.current_page }} of {{
                                                props.reservations.last_page }}</span>

                                            <button type="button" v-if="props.reservations.next_page_url"
                                                @click="goToPage(props.reservations.next_page_url)"
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
                                <!-- EPagination -->
                            </div>
                        </div>

                    </div>
                </div>
                <!-- ====== Table One End -->

            </div>
        </div>
    </AppLayout>
</template>
