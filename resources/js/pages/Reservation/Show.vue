<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Reservation',
        href: '/reservation.index',
    },
    {
        title: 'View Reservation',
        href: '#',
    },
];

const { reservation } = defineProps<{
    reservation: any;
}>();

const formatDate = (dateString: string) => {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

const formatTime = (timeString: string) => {
    if (!timeString) return 'N/A';
    const [hours, minutes] = timeString.split(':');
    const date = new Date();
    date.setHours(parseInt(hours), parseInt(minutes));
    return date.toLocaleTimeString('en-US', {
        hour: 'numeric',
        minute: '2-digit',
        hour12: true
    });
};
</script>

<template>
    <Head title="View Reservation" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-900 dark:border-neutral-700">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                        Reservation Details
                    </h2>
                </div>

                <!-- Content -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Customer Information -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-neutral-200">Customer Information</h3>
                            <div class="space-y-2">
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-neutral-400">Name</label>
                                    <p class="mt-1 text-gray-900 dark:text-neutral-200">{{ reservation?.customer_name }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-neutral-400">Email</label>
                                    <p class="mt-1 text-gray-900 dark:text-neutral-200">{{ reservation?.customer_email }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-neutral-400">Phone</label>
                                    <p class="mt-1 text-gray-900 dark:text-neutral-200">{{ reservation?.customer_phone }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Reservation Details -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-neutral-200">Reservation Details</h3>
                            <div class="space-y-2">
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-neutral-400">Date</label>
                                    <p class="mt-1 text-gray-900 dark:text-neutral-200">{{ formatDate(reservation?.reservation_date) }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-neutral-400">Time</label>
                                    <p class="mt-1 text-gray-900 dark:text-neutral-200">{{ formatTime(reservation?.reservation_time) }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-neutral-400">Number of Guests</label>
                                    <p class="mt-1 text-gray-900 dark:text-neutral-200">{{ reservation?.number_of_guest }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-neutral-400">Special Requests</label>
                                    <p class="mt-1 text-gray-900 dark:text-neutral-200">{{ reservation?.special_request || 'None' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-6 flex justify-end space-x-3">
                        <a :href="route('reservation.edit', { reservation: reservation?.id })"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                            Edit Reservation
                        </a>
                        <a :href="route('reservation.index')"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template> 