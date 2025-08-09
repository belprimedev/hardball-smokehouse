<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { ref, watchEffect } from 'vue';
import { X, AlertCircle, Loader2, Check } from 'lucide-vue-next';

interface MenuCategory {
    id: number;
    name: string;
    description: string;
    display_image?: string;
}

const props = defineProps<{
    category: MenuCategory;
    flash?: {
        success?: string;
        error?: string;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Menu Categories',
        href: route('menu-category.index'),
    },
    {
        title: 'Edit Menu Category',
        href: `/menu-category/${props.category.id}/edit`,
    },
];

const form = ref({
    name: props.category.name,
    description: props.category.description || '',
    display_image: null as File | null,
    remove_existing_image: false
});

const imagePreview = ref<string | null>(null);
const isSubmitting = ref(false);
const errors = ref<Record<string, string>>({});

// Initialize image preview with existing image
watchEffect(() => {
    if (props.category.display_image && !form.value.display_image) {
        imagePreview.value = `/storage/${props.category.display_image}`;
    }
});

// Handle image upload
const handleImageUpload = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0];
    
    if (file) {
        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
        if (!allowedTypes.includes(file.type)) {
            errors.value.display_image = 'Please select a valid image file (JPEG, PNG, GIF, WebP)';
            return;
        }
        
        // Validate file size (2MB max)
        if (file.size > 2 * 1024 * 1024) {
            errors.value.display_image = 'Image size must be less than 2MB';
            return;
        }
        
        form.value.display_image = file;
        errors.value.display_image = '';
        
        // Create preview
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target?.result as string;
        };
        reader.readAsDataURL(file);
    }
};

// Remove image
const removeImage = () => {
    form.value.display_image = null;
    imagePreview.value = null;
    errors.value.display_image = '';
    
    // If there was an existing image, we need to signal that we want to remove it
    if (props.category.display_image) {
        form.value.remove_existing_image = true;
    }
};

// Validate form
const validateForm = () => {
    errors.value = {};
    
    if (!form.value.name.trim()) {
        errors.value.name = 'Category name is required';
    }
    
    return Object.keys(errors.value).length === 0;
};

// Submit form
const submitForm = async () => {
    if (!validateForm()) return;
    
    isSubmitting.value = true;
    
    try {
        const formData = new FormData();
        
        // Add _method field to handle PUT request
        formData.append('_method', 'PUT');
        
        // Add all form fields to FormData
        formData.append('name', form.value.name);
        formData.append('description', form.value.description || '');
        
        // Add image if selected
        if (form.value.display_image) {
            formData.append('display_image', form.value.display_image);
        }
        
        // Add image removal flag if needed
        if (form.value.remove_existing_image) {
            formData.append('remove_existing_image', '1');
        }
        
        await router.post(route('menu-category.update', props.category.id), formData, {
            onSuccess: () => {
                router.visit('/menu-category');
            },
            onError: (validationErrors) => {
                errors.value = validationErrors;
            }
        });
    } catch (error) {
        console.error('Form submission error:', error);
        errors.value.general = 'An unexpected error occurred. Please try again.';
    } finally {
        isSubmitting.value = false;
    }
};

// Cancel form
const cancelForm = () => {
    router.visit('/menu-category');
};
</script>

<template>
    <Head title="Edit Menu Category" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-6">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-cyan-600 rounded-xl p-6 text-white">
                <h1 class="text-3xl font-bold mb-2">Edit Menu Category</h1>
                <p class="text-blue-100">Update your menu category information</p>
            </div>

            <!-- Form Container -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <!-- Success Message Display -->
                <div v-if="props.flash?.success" class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-400 p-4 m-6">
                    <div class="flex">
                        <Check class="w-5 h-5 text-green-400" />
                        <div class="ml-3">
                            <p class="text-sm text-green-700 dark:text-green-300">{{ props.flash.success }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Error Message Display -->
                <div v-if="props.flash?.error" class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400 p-4 m-6">
                    <div class="flex">
                        <AlertCircle class="w-5 h-5 text-red-400" />
                        <div class="ml-3">
                            <p class="text-sm text-red-700 dark:text-red-300">{{ props.flash.error }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- General Error Display -->
                <div v-if="errors.general" class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400 p-4 m-6">
                    <div class="flex">
                        <AlertCircle class="w-5 h-5 text-red-400" />
                        <div class="ml-3">
                            <p class="text-sm text-red-700 dark:text-red-300">{{ errors.general }}</p>
                        </div>
                    </div>
                </div>
                
                <form @submit.prevent="submitForm" class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <!-- Basic Information -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2">
                                    Basic Information
                                </h3>
                                
                                <!-- Category Name -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Category Name *
                                    </label>
                                    <input
                                        v-model="form.name"
                                        type="text"
                                        id="name"
                                        :class="[
                                            'w-full px-4 py-3 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors dark:bg-gray-800 dark:text-white dark:placeholder-gray-400',
                                            errors.name ? 'border-red-300 bg-red-50 dark:bg-red-900/20 dark:border-red-500' : 'border-gray-300 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500'
                                        ]"
                                        placeholder="e.g., Main Dishes"
                                        required
                                    />
                                    <p v-if="errors.name" class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                                        <AlertCircle class="w-4 h-4" />
                                        {{ errors.name }}
                                    </p>
                                </div>

                                <!-- Description -->
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Description
                                    </label>
                                    <textarea
                                        v-model="form.description"
                                        id="description"
                                        rows="4"
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors hover:border-gray-400 dark:hover:border-gray-500 dark:bg-gray-800 dark:text-white dark:placeholder-gray-400"
                                        placeholder="Describe the category and what types of items it contains..."
                                    />
                                    <p v-if="errors.description" class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                                        <AlertCircle class="w-4 h-4" />
                                        {{ errors.description }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <!-- Image Upload -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2">
                                    Category Image
                                </h3>
                                
                                <!-- Simple File Input -->
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Upload Image
                                        </label>
                                        <input
                                            type="file"
                                            accept="image/*"
                                            @change="handleImageUpload"
                                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                        />
                                    </div>

                                    <!-- Image Preview -->
                                    <div v-if="imagePreview" class="relative">
                                        <img
                                            :src="imagePreview"
                                            alt="Preview"
                                            class="w-full h-48 object-cover rounded-lg border border-gray-200 dark:border-gray-600"
                                        />
                                        <button
                                            type="button"
                                            @click="removeImage"
                                            class="absolute top-2 right-2 bg-red-500 text-white p-2 rounded-full hover:bg-red-600 transition-colors"
                                        >
                                            <X class="w-4 h-4" />
                                        </button>
                                    </div>

                                    <p v-if="errors.display_image" class="text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                                        <AlertCircle class="w-4 h-4" />
                                        {{ errors.display_image }}
                                    </p>
                                    
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Upload an image to represent this category. Recommended size: 400x300 pixels. Max file size: 2MB.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <button
                            type="button"
                            @click="cancelForm"
                            class="px-6 py-3 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 dark:focus:ring-gray-400 transition-colors"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            :disabled="isSubmitting"
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors flex items-center gap-2"
                        >
                            <Loader2 v-if="isSubmitting" class="w-4 h-4 animate-spin" />
                            <span v-if="isSubmitting">Updating...</span>
                            <span v-else>Update Category</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
