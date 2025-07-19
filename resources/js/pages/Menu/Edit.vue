<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { ref, watchEffect } from 'vue';
import { X, ChefHat, Star, Eye, Check, AlertCircle, Loader2 } from 'lucide-vue-next';

interface MenuItem {
    id: number;
    name: string;
    description: string | null;
    price: number;
    image_path: string | null;
    category_id: number;
    is_featured: boolean;
    is_chef_special: boolean;
    is_available: boolean;
    is_visible: boolean;
    short_label: string | null;
    side_note: string | null;
    image?: File;
}

interface MenuCategory {
    id: number;
    name: string;
}

const props = defineProps<{
    menuItem: MenuItem;
    categories: MenuCategory[];
    flash?: {
        success?: string;
        error?: string;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Menu Items',
        href: route('menu-items.index'),
    },
    {
        title: 'Edit Menu Item',
        href: `/menu-items/${props.menuItem.id}/edit`,
    },
];

const form = ref({
    name: props.menuItem.name,
    description: props.menuItem.description || '',
    short_label: props.menuItem.short_label || '',
    side_note: props.menuItem.side_note || '',
    price: props.menuItem.price.toString(),
    category_id: props.menuItem.category_id.toString(),
    is_featured: !!props.menuItem.is_featured,
    is_chef_special: !!props.menuItem.is_chef_special,
    is_available: !!props.menuItem.is_available,
    is_visible: !!props.menuItem.is_visible,
    image: null as File | null,
    remove_existing_image: false
});

const imagePreview = ref<string | null>(null);
const isSubmitting = ref(false);
const errors = ref<Record<string, string>>({});

// Initialize image preview with existing image
watchEffect(() => {
    if (props.menuItem.image_path && !form.value.image) {
        imagePreview.value = `/storage/${props.menuItem.image_path}`;
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
            errors.value.image = 'Please select a valid image file (JPEG, PNG, GIF, WebP)';
            return;
        }
        
        // Validate file size (2MB max)
        if (file.size > 2 * 1024 * 1024) {
            errors.value.image = 'Image size must be less than 2MB';
            return;
        }
        
        form.value.image = file;
        errors.value.image = '';
        
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
    form.value.image = null;
    imagePreview.value = null; // Set to null to hide the preview
    errors.value.image = '';
    
    // If there was an existing image, we need to signal that we want to remove it
    // We'll add a special field to the form to indicate image removal
    if (props.menuItem.image_path) {
        form.value.remove_existing_image = true;
    }
};

// Validate form
const validateForm = () => {
    errors.value = {};
    
    if (!form.value.name.trim()) {
        errors.value.name = 'Item name is required';
    }
    
    if (!form.value.price || parseFloat(form.value.price) <= 0) {
        errors.value.price = 'Valid price is required';
    }
    
    if (!form.value.category_id) {
        errors.value.category_id = 'Please select a category';
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
        formData.append('short_label', form.value.short_label || '');
        formData.append('side_note', form.value.side_note || '');
        formData.append('price', form.value.price);
        formData.append('category_id', form.value.category_id);
        formData.append('is_featured', form.value.is_featured ? '1' : '0');
        formData.append('is_chef_special', form.value.is_chef_special ? '1' : '0');
        formData.append('is_available', form.value.is_available ? '1' : '0');
        formData.append('is_visible', form.value.is_visible ? '1' : '0');
        
        // Add image if selected
        if (form.value.image) {
            formData.append('image', form.value.image);
        }
        
        // Add image removal flag if needed
        if (form.value.remove_existing_image) {
            formData.append('remove_existing_image', '1');
        }
        
        await router.post(route('menu-items.update', props.menuItem.id), formData, {
            onSuccess: () => {
                router.visit('/menu-items');
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
    router.visit('/menu-items');
};
</script>

<template>
    <Head title="Edit Menu Item" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-6">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-cyan-600 rounded-xl p-6 text-white">
                <h1 class="text-3xl font-bold mb-2">Edit Menu Item</h1>
                <p class="text-blue-100">Update your delicious Caribbean menu item</p>
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
                                
                                <!-- Item Name -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Item Name *
                                    </label>
                                    <input
                                        v-model="form.name"
                                        type="text"
                                        id="name"
                                        :class="[
                                            'w-full px-4 py-3 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors dark:bg-gray-800 dark:text-white dark:placeholder-gray-400',
                                            errors.name ? 'border-red-300 bg-red-50 dark:bg-red-900/20 dark:border-red-500' : 'border-gray-300 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500'
                                        ]"
                                        placeholder="e.g., Jerk Chicken"
                                        required
                                    />
                                    <p v-if="errors.name" class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                                        <AlertCircle class="w-4 h-4" />
                                        {{ errors.name }}
                                    </p>
                                </div>

                                <!-- Category -->
                                <div>
                                    <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Category *
                                    </label>
                                    <select
                                        v-model="form.category_id"
                                        id="category_id"
                                        :class="[
                                            'w-full px-4 py-3 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors dark:bg-gray-800 dark:text-white dark:border-gray-600',
                                            errors.category_id ? 'border-red-300 bg-red-50 dark:bg-red-900/20 dark:border-red-500' : 'border-gray-300 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500'
                                        ]"
                                        required
                                    >
                                        <option value="">Select a category</option>
                                        <option v-for="category in props.categories" :key="category.id" :value="category.id">
                                            {{ category.name }}
                                        </option>
                                    </select>
                                    <p v-if="errors.category_id" class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                                        <AlertCircle class="w-4 h-4" />
                                        {{ errors.category_id }}
                                    </p>
                                </div>

                                <!-- Price -->
                                <div>
                                    <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Price (£) *
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400">£</span>
                                        <input
                                            v-model="form.price"
                                            type="number"
                                            id="price"
                                            step="0.01"
                                            min="0"
                                            :class="[
                                                'w-full pl-8 pr-4 py-3 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors dark:bg-gray-800 dark:text-white dark:placeholder-gray-400',
                                                errors.price ? 'border-red-300 bg-red-50 dark:bg-red-900/20 dark:border-red-500' : 'border-gray-300 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500'
                                            ]"
                                            placeholder="0.00"
                                            required
                                        />
                                    </div>
                                    <p v-if="errors.price" class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                                        <AlertCircle class="w-4 h-4" />
                                        {{ errors.price }}
                                    </p>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2">
                                    Description
                                </h3>
                                
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Full Description
                                    </label>
                                    <textarea
                                        v-model="form.description"
                                        id="description"
                                        rows="4"
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors hover:border-gray-400 dark:hover:border-gray-500 dark:bg-gray-800 dark:text-white dark:placeholder-gray-400"
                                        placeholder="Describe the dish, ingredients, cooking method..."
                                    />
                                </div>

                                <div>
                                    <label for="short_label" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Short Label
                                    </label>
                                    <input
                                        v-model="form.short_label"
                                        type="text"
                                        id="short_label"
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors hover:border-gray-400 dark:hover:border-gray-500 dark:bg-gray-800 dark:text-white dark:placeholder-gray-400"
                                        placeholder="Quick description for display"
                                    />
                                </div>

                                <div>
                                    <label for="side_note" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Side Note
                                    </label>
                                    <input
                                        v-model="form.side_note"
                                        type="text"
                                        id="side_note"
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors hover:border-gray-400 dark:hover:border-gray-500 dark:bg-gray-800 dark:text-white dark:placeholder-gray-400"
                                        placeholder="Additional information (e.g., 'Serves 2')"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <!-- Image Upload -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2">
                                    Item Image
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

                                    <p v-if="errors.image" class="text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                                        <AlertCircle class="w-4 h-4" />
                                        {{ errors.image }}
                                    </p>
                                </div>
                            </div>

                            <!-- Item Settings -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2">
                                    Item Settings
                                </h3>
                                
                                <div class="space-y-4">
                                    <!-- Featured Toggle -->
                                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                        <div class="flex items-center gap-3">
                                            <Star class="w-5 h-5 text-yellow-500" />
                                            <div>
                                                <p class="font-medium text-gray-900 dark:text-white">Featured Item</p>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">Highlight this item on the menu</p>
                                            </div>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input
                                                v-model="form.is_featured"
                                                type="checkbox"
                                                class="sr-only peer"
                                            />
                                            <div class="w-11 h-6 bg-gray-200 dark:bg-gray-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-600 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                        </label>
                                    </div>

                                    <!-- Chef Special Toggle -->
                                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                        <div class="flex items-center gap-3">
                                            <ChefHat class="w-5 h-5 text-red-500" />
                                            <div>
                                                <p class="font-medium text-gray-900 dark:text-white">Chef's Special</p>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">Mark as chef's special creation</p>
                                            </div>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input
                                                v-model="form.is_chef_special"
                                                type="checkbox"
                                                class="sr-only peer"
                                            />
                                            <div class="w-11 h-6 bg-gray-200 dark:bg-gray-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-600 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                        </label>
                                    </div>

                                    <!-- Available Toggle -->
                                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                        <div class="flex items-center gap-3">
                                            <Check class="w-5 h-5 text-green-500" />
                                            <div>
                                                <p class="font-medium text-gray-900 dark:text-white">Available</p>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">Item is available for ordering</p>
                                            </div>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input
                                                v-model="form.is_available"
                                                type="checkbox"
                                                class="sr-only peer"
                                            />
                                            <div class="w-11 h-6 bg-gray-200 dark:bg-gray-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-600 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                        </label>
                                    </div>

                                    <!-- Visible Toggle -->
                                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                        <div class="flex items-center gap-3">
                                            <Eye class="w-5 h-5 text-blue-500" />
                                            <div>
                                                <p class="font-medium text-gray-900 dark:text-white">Visible</p>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">Show item on public menu</p>
                                            </div>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input
                                                v-model="form.is_visible"
                                                type="checkbox"
                                                class="sr-only peer"
                                            />
                                            <div class="w-11 h-6 bg-gray-200 dark:bg-gray-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-600 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                        </label>
                                    </div>
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
                            <span v-else>Update Menu Item</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
