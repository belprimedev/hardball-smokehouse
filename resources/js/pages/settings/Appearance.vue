<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';

import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { useAppearance } from '@/composables/useAppearance';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Appearance settings',
        href: '/settings/appearance',
    },
];

const { appearance, updateAppearance } = useAppearance();

const themes = [
    {
        name: 'Light',
        value: 'light',
        description: 'Light theme for bright environments',
        icon: '☀️',
        preview: 'bg-white border-gray-200'
    },
    {
        name: 'Dark',
        value: 'dark',
        description: 'Dark theme for low-light environments',
        icon: '🌙',
        preview: 'bg-gray-900 border-gray-700'
    },
    {
        name: 'System',
        value: 'system',
        description: 'Automatically match your system preference',
        icon: '💻',
        preview: 'bg-gradient-to-r from-gray-100 to-gray-900 border-gray-300'
    }
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Appearance Settings" />

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <HeadingSmall 
                    title="Appearance" 
                    description="Customize the appearance of the application to match your preference" 
                />

                <div class="space-y-6">
                    <!-- Theme Selection -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <span class="text-2xl">🎨</span>
                                Theme
                            </CardTitle>
                            <CardDescription>
                                Choose how the application should appear. You can select a light theme, dark theme, or use your system's default setting.
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div 
                                    v-for="theme in themes" 
                                    :key="theme.value"
                                    @click="updateAppearance(theme.value as 'light' | 'dark' | 'system')"
                                    :class="[
                                        'relative cursor-pointer rounded-lg border-2 p-4 transition-all duration-200 hover:shadow-md',
                                        appearance === theme.value 
                                            ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20' 
                                            : 'border-gray-200 dark:border-gray-700 hover:border-emerald-300 dark:hover:border-emerald-600'
                                    ]"
                                >
                                    <!-- Selected indicator -->
                                    <div 
                                        v-if="appearance === theme.value"
                                        class="absolute -top-2 -right-2 w-6 h-6 bg-emerald-500 rounded-full flex items-center justify-center"
                                    >
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>

                                    <div class="flex items-center gap-3 mb-3">
                                        <span class="text-2xl">{{ theme.icon }}</span>
                                        <div>
                                            <h3 class="font-semibold text-gray-900 dark:text-neutral-200">
                                                {{ theme.name }}
                                            </h3>
                                            <p class="text-sm text-gray-600 dark:text-neutral-400">
                                                {{ theme.description }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Theme preview -->
                                    <div class="space-y-2">
                                        <div :class="['h-8 rounded border-2', theme.preview]"></div>
                                        <div class="flex gap-1">
                                            <div :class="['h-4 rounded flex-1', theme.preview]"></div>
                                            <div :class="['h-4 rounded w-8', theme.preview]"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Additional Settings -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <span class="text-2xl">⚙️</span>
                                Additional Settings
                            </CardTitle>
                            <CardDescription>
                                Fine-tune your appearance preferences
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-4">
                                <!-- Auto-switch notification -->
                                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-neutral-800 rounded-lg">
                                    <div>
                                        <h4 class="font-medium text-gray-900 dark:text-neutral-200">
                                            System Theme Detection
                                        </h4>
                                        <p class="text-sm text-gray-600 dark:text-neutral-400">
                                            Automatically switch themes based on your system preference
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="w-12 h-6 bg-emerald-500 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Current theme info -->
                                <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg border border-emerald-200 dark:border-emerald-800">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-emerald-900 dark:text-emerald-100">
                                                Current Theme
                                            </h4>
                                            <p class="text-sm text-emerald-700 dark:text-emerald-300">
                                                {{ appearance === 'system' ? 'Following system preference' : `${appearance.charAt(0).toUpperCase() + appearance.slice(1)} theme active` }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Help Section -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <span class="text-2xl">❓</span>
                                Need Help?
                            </CardTitle>
                            <CardDescription>
                                Learn more about appearance settings
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-3 text-sm text-gray-600 dark:text-neutral-400">
                                <p>
                                    <strong>Light Theme:</strong> Best for bright environments and daytime use. Provides high contrast and readability.
                                </p>
                                <p>
                                    <strong>Dark Theme:</strong> Ideal for low-light environments and reducing eye strain. Uses darker colors throughout the interface.
                                </p>
                                <p>
                                    <strong>System Theme:</strong> Automatically matches your operating system's appearance setting. The theme will change when you switch your system preference.
                                </p>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </SettingsLayout>
    </AppLayout>
</template> 