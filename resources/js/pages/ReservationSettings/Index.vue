<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Reservation Settings',
        href: '/reservation-settings',
    },
];

interface ReservationSetting {
    id: number;
    day_of_week: string;
    opening_time: string;
    closing_time: string;
    max_capacity_per_hour: number;
    is_open: boolean;
}

const props = defineProps<{
    settings: ReservationSetting[];
}>();

const form = useForm({
    settings: props.settings || []
} as any);

const daysOfWeek = [
    { key: 'monday', label: 'Monday' },
    { key: 'tuesday', label: 'Tuesday' },
    { key: 'wednesday', label: 'Wednesday' },
    { key: 'thursday', label: 'Thursday' },
    { key: 'friday', label: 'Friday' },
    { key: 'saturday', label: 'Saturday' },
    { key: 'sunday', label: 'Sunday' },
];

const submitForm = () => {
    form.put(route('reservation-settings.update'), {
        onSuccess: () => {
            // Form will automatically handle success
        },
    });
};

// Create a reactive map of settings by day
const settingsByDay = computed(() => {
    const settings = form.settings as ReservationSetting[];
    const map = new Map();
    
    if (!settings || !Array.isArray(settings) || settings.length === 0) {
        console.warn('Settings array is empty or undefined');
        // Create default settings for all days
        daysOfWeek.forEach(day => {
            map.set(day.key, {
                id: 0,
                day_of_week: day.key,
                opening_time: '09:00:00',
                closing_time: '17:00:00',
                max_capacity_per_hour: 20,
                is_open: false
            });
        });
        return map;
    }
    
    settings.forEach(setting => {
        map.set(setting.day_of_week, setting);
    });
    
    // Ensure all days have a setting
    daysOfWeek.forEach(day => {
        if (!map.has(day.key)) {
            console.warn('No setting found for day:', day.key);
            map.set(day.key, {
                id: 0,
                day_of_week: day.key,
                opening_time: '09:00:00',
                closing_time: '17:00:00',
                max_capacity_per_hour: 20,
                is_open: false
            });
        }
    });
    
    return map;
});

const getSettingForDay = (dayKey: string) => {
    return settingsByDay.value.get(dayKey);
};
</script>

<template>
    <Head title="Reservation Settings" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex flex-col">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="p-1.5 min-w-full inline-block align-middle">
                        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-900 dark:border-neutral-700">
                            <!-- Header -->
                            <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200 dark:border-neutral-700">
                                <div>
                                    <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                                        Reservation Settings
                                    </h2>
                                    <p class="text-sm text-yellow-500 dark:text-neutral-400">
                                        Configure capacity and hours for each day of the week.
                                    </p>
                                </div>
                            </div>

                            <!-- Settings Form -->
                            <form @submit.prevent="submitForm" class="p-8">
                                <div class="space-y-8">
                                    <div v-for="day in daysOfWeek" :key="day.key" class="bg-gray-50 dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                                        <div class="flex items-center justify-between mb-6">
                                            <h3 class="text-xl font-semibold text-gray-900 dark:text-neutral-200 flex items-center">
                                                <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mr-3">
                                                    <span class="text-green-600 dark:text-green-400 font-bold text-sm">
                                                        {{ day.label.charAt(0) }}
                                                    </span>
                                                </div>
                                                {{ day.label }}
                                            </h3>
                                            <div class="flex items-center">
                                                <input 
                                                    type="checkbox" 
                                                    :id="`is_open_${day.key}`"
                                                    v-model="getSettingForDay(day.key).is_open"
                                                    class="h-5 w-5 text-green-600 focus:ring-green-500 border-gray-300 rounded transition-colors duration-200"
                                                >
                                                <label :for="`is_open_${day.key}`" class="ml-3 text-sm font-medium text-gray-700 dark:text-neutral-300">
                                                    Open for Business
                                                </label>
                                            </div>
                                        </div>

                                        <div v-if="getSettingForDay(day.key).is_open" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                            <!-- Opening Time -->
                                            <div class="space-y-2">
                                                <label :for="`opening_${day.key}`" class="block text-sm font-semibold text-gray-700 dark:text-neutral-300">
                                                    Opening Time
                                                </label>
                                                <input 
                                                    type="time" 
                                                    :id="`opening_${day.key}`"
                                                    v-model="getSettingForDay(day.key).opening_time"
                                                    class="block w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-neutral-600 shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 dark:bg-neutral-700 dark:text-white transition-all duration-200 text-base"
                                                    required
                                                >
                                            </div>

                                            <!-- Closing Time -->
                                            <div class="space-y-2">
                                                <label :for="`closing_${day.key}`" class="block text-sm font-semibold text-gray-700 dark:text-neutral-300">
                                                    Closing Time
                                                </label>
                                                <input 
                                                    type="time" 
                                                    :id="`closing_${day.key}`"
                                                    v-model="getSettingForDay(day.key).closing_time"
                                                    class="block w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-neutral-600 shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 dark:bg-neutral-700 dark:text-white transition-all duration-200 text-base"
                                                    required
                                                >
                                            </div>

                                            <!-- Max Capacity -->
                                            <div class="space-y-2">
                                                <label :for="`capacity_${day.key}`" class="block text-sm font-semibold text-gray-700 dark:text-neutral-300">
                                                    Max Capacity per Hour
                                                </label>
                                                <input 
                                                    type="number" 
                                                    :id="`capacity_${day.key}`"
                                                    v-model="getSettingForDay(day.key).max_capacity_per_hour"
                                                    min="1"
                                                    max="100"
                                                    class="block w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-neutral-600 shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 dark:bg-neutral-700 dark:text-white transition-all duration-200 text-base"
                                                    required
                                                >
                                            </div>
                                        </div>

                                        <div v-else class="flex items-center justify-center py-8">
                                            <div class="text-center">
                                                <div class="w-16 h-16 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center mx-auto mb-4">
                                                    <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </div>
                                                <p class="text-lg font-medium text-gray-600 dark:text-neutral-400">
                                                    Restaurant is closed on {{ day.label }}
                                                </p>
                                                <p class="text-sm text-gray-500 dark:text-neutral-500 mt-1">
                                                    No reservations accepted
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="mt-10 flex justify-end">
                                    <button 
                                        type="submit"
                                        :disabled="form.processing"
                                        class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wider hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 disabled:opacity-50 shadow-lg hover:shadow-xl"
                                    >
                                        <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <svg v-else class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span v-if="form.processing">Saving Settings...</span>
                                        <span v-else>Save Settings</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template> 