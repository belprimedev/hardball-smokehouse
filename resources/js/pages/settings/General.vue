<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { type BreadcrumbItem } from '@/types';

const props = defineProps({
    settings: Object,
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'General settings',
        href: '/settings/general',
    },
];

const form = useForm({
    business_name: props.settings?.business_name || '',
    business_email: props.settings?.business_email || '',
    contact_number: props.settings?.contact_number || '',
    address: props.settings?.address || '',
    operation_hours: props.settings?.operation_hours || '',
    website: props.settings?.website || '',
    description: props.settings?.description || '',
});

// Function to parse operation hours for preview
const parseOperationHours = (hoursText: string) => {
    if (!hoursText) return [];
    
    const lines = hoursText.split('\n').filter(line => line.trim());
    return lines.map(line => {
        const parts = line.split(':');
        if (parts.length >= 2) {
            return {
                day: parts[0].trim(),
                hours: parts.slice(1).join(':').trim()
            };
        }
        return {
            day: line.trim(),
            hours: 'Hours not specified'
        };
    });
};

// Computed property for operation hours preview
const operationHoursPreview = computed(() => {
    return parseOperationHours(form.operation_hours);
});

const submitForm = () => {
    form.put(route('settings.general.update'), {
        onSuccess: () => {
            // Form will be reset with new data
        },
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="General Settings" />

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <HeadingSmall title="General Settings" description="Manage your business information and contact details" />

                <!-- Settings Form -->
                <form @submit.prevent="submitForm" class="space-y-6">
                    <!-- Business Information Section -->
                    <div class="bg-gray-50 dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-6 shadow-sm">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-neutral-200 mb-6 flex items-center">
                            <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            Business Information
                        </h3>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Business Name -->
                            <div class="space-y-2">
                                <label for="business_name" class="block text-sm font-semibold text-gray-700 dark:text-neutral-300">
                                    Business Name
                                </label>
                                <input 
                                    type="text" 
                                    id="business_name"
                                    v-model="form.business_name"
                                    class="block w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-neutral-600 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 focus:ring-opacity-50 dark:bg-neutral-700 dark:text-white transition-all duration-200 text-base"
                                    required
                                >
                            </div>

                            <!-- Business Email -->
                            <div class="space-y-2">
                                <label for="business_email" class="block text-sm font-semibold text-gray-700 dark:text-neutral-300">
                                    Business Email
                                </label>
                                <input 
                                    type="email" 
                                    id="business_email"
                                    v-model="form.business_email"
                                    class="block w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-neutral-600 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 focus:ring-opacity-50 dark:bg-neutral-700 dark:text-white transition-all duration-200 text-base"
                                    required
                                >
                            </div>

                            <!-- Contact Number -->
                            <div class="space-y-2">
                                <label for="contact_number" class="block text-sm font-semibold text-gray-700 dark:text-neutral-300">
                                    Contact Number
                                </label>
                                <input 
                                    type="tel" 
                                    id="contact_number"
                                    v-model="form.contact_number"
                                    class="block w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-neutral-600 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 focus:ring-opacity-50 dark:bg-neutral-700 dark:text-white transition-all duration-200 text-base"
                                    required
                                >
                            </div>

                            <!-- Website -->
                            <div class="space-y-2">
                                <label for="website" class="block text-sm font-semibold text-gray-700 dark:text-neutral-300">
                                    Website URL
                                </label>
                                <input 
                                    type="url" 
                                    id="website"
                                    v-model="form.website"
                                    class="block w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-neutral-600 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 focus:ring-opacity-50 dark:bg-neutral-700 dark:text-white transition-all duration-200 text-base"
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Address Section -->
                    <div class="bg-gray-50 dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-6 shadow-sm">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-neutral-200 mb-6 flex items-center">
                            <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            Address
                        </h3>
                        
                        <div class="space-y-2">
                            <label for="address" class="block text-sm font-semibold text-gray-700 dark:text-neutral-300">
                                Full Address
                            </label>
                            <textarea 
                                id="address"
                                v-model="form.address"
                                rows="3"
                                class="block w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-neutral-600 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 focus:ring-opacity-50 dark:bg-neutral-700 dark:text-white transition-all duration-200 text-base"
                                required
                            ></textarea>
                        </div>
                    </div>

                    <!-- Operation Hours Section -->
                    <div class="bg-gray-50 dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-6 shadow-sm">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-neutral-200 mb-6 flex items-center">
                            <div class="w-8 h-8 bg-orange-100 dark:bg-orange-900 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            Operation Hours
                        </h3>
                        
                        <div class="space-y-2">
                            <label for="operation_hours" class="block text-sm font-semibold text-gray-700 dark:text-neutral-300">
                                Operating Hours
                            </label>
                            <textarea 
                                id="operation_hours"
                                v-model="form.operation_hours"
                                rows="6"
                                placeholder="Format: Day: Hours (one per line)&#10;Example:&#10;Monday - Friday: 9:00 AM - 10:00 PM&#10;Saturday: 10:00 AM - 11:00 PM&#10;Sunday: 11:00 AM - 9:00 PM"
                                class="block w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-neutral-600 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 focus:ring-opacity-50 dark:bg-neutral-700 dark:text-white transition-all duration-200 text-base"
                                required
                            ></textarea>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                💡 Tip: Use the format "Day: Hours" for each line. This will display beautifully on the contact page.
                            </p>
                            
                            <!-- Preview of how hours will look -->
                            <div v-if="operationHoursPreview.length > 0" class="mt-4 p-4 bg-gray-50 dark:bg-neutral-700 rounded-lg">
                                <h4 class="text-sm font-semibold text-gray-700 dark:text-neutral-300 mb-3">Preview:</h4>
                                <div class="space-y-2">
                                    <div 
                                        v-for="(hour, index) in operationHoursPreview" 
                                        :key="index"
                                        class="flex justify-between items-center py-2 px-3 bg-white dark:bg-neutral-600 rounded-lg border border-gray-200 dark:border-neutral-500"
                                    >
                                        <span class="font-medium text-gray-900 dark:text-neutral-200 text-sm">
                                            {{ hour.day }}
                                        </span>
                                        <span class="text-emerald-600 dark:text-emerald-400 font-semibold text-sm">
                                            {{ hour.hours }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description Section -->
                    <div class="bg-gray-50 dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-6 shadow-sm">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-neutral-200 mb-6 flex items-center">
                            <div class="w-8 h-8 bg-teal-100 dark:bg-teal-900 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            Business Description
                        </h3>
                        
                        <div class="space-y-2">
                            <label for="description" class="block text-sm font-semibold text-gray-700 dark:text-neutral-300">
                                Business Description
                            </label>
                            <textarea 
                                id="description"
                                v-model="form.description"
                                rows="4"
                                placeholder="Describe your business, cuisine, atmosphere, etc."
                                class="block w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-neutral-600 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 focus:ring-opacity-50 dark:bg-neutral-700 dark:text-white transition-all duration-200 text-base"
                            ></textarea>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button 
                            type="submit"
                            :disabled="form.processing"
                            class="inline-flex items-center px-6 py-3 bg-emerald-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wider hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-all duration-200 disabled:opacity-50 shadow-lg hover:shadow-xl"
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
        </SettingsLayout>
    </AppLayout>
</template> 