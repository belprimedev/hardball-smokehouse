<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Edit Menu Category',
        href: '/menu-category.edit',
    },
];

const props = defineProps({
    category: Object,
});

const form = useForm({
    name: props.category.name,
    description: props.category.description,
});

const submit = () => {
    form.put(route('menu-category.update', props.category.id), {
        onSuccess: () => alert('Category updated successfully!'),
    });
};
</script>

<template>
    <Head title="Reservation" />

    <AppLayout :breadcrumbs="breadcrumbs">

        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="bg-white p-6 shadow-lg rounded-lg">
                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <label class="block font-medium text-gray-700">Name</label>
                        <input v-model="form.name" type="text"
                            class="w-full mt-1 p-2 border rounded-lg focus:ring focus:ring-blue-300">
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-gray-700">Description</label>
                        <textarea v-model="form.description"
                            class="w-full mt-1 p-2 border rounded-lg focus:ring focus:ring-blue-300"></textarea>
                    </div>

                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                        Update Category
                    </button>
                </form> 
            </div>
        </div>
    </AppLayout>
</template>
