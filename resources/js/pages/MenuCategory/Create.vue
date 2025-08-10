<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Create Menu Category',
        href: '/menu-category.create',
    },
];

const form = useForm({
    name: '',
    description: '',
    display_image: null as File | null,
});

const handleFileUpload = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        const file = target.files[0];
        console.log('File selected:', file.name, file.size);
        form.display_image = file;
    }
};

const submit = () => {
    console.log('Submitting form with file:', form.display_image);
    console.log('Form data:', {
        name: form.name,
        description: form.description,
        display_image: form.display_image
    });
    
    form.post(route('menu-category.store'), {
        preserveScroll: true,
        onError: (errors) => {
            console.error('Validation errors:', errors);
        },
        onSuccess: () => {
            console.log('Success!');
        }
    });
};
</script>

<template>
    <Head title="Create Menu Category" />

    <AppLayout :breadcrumbs="breadcrumbs">

        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex flex-col">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="max-w-4xl mx-auto p-6">
            <h2 class="text-xl font-bold mb-4">Add Menu Category</h2>
            <form @submit.prevent="submit" enctype="multipart/form-data">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                    <input v-model="form.name" class="w-full border border-gray-300 rounded-md p-3 focus:ring-2 focus:ring-green-500 focus:border-transparent" required />
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea v-model="form.description" class="w-full border border-gray-300 rounded-md p-3 focus:ring-2 focus:ring-green-500 focus:border-transparent" rows="3"></textarea>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Display Image</label>
                    <input 
                        type="file" 
                        @change="handleFileUpload" 
                        accept="image/*"
                        class="w-full border border-gray-300 rounded-md p-3 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    />
                    <p class="text-sm text-gray-500 mt-1">Upload an image to represent this category (optional)</p>
                </div>
                
                <button type="submit" class="mt-4 bg-green-500 text-white px-6 py-3 rounded-md hover:bg-green-600 transition-colors">Save Category</button>
            </form>
        </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
