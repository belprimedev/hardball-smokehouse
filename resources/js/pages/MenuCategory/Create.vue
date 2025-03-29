<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Create Menu Category',
        href: '/menu-category.create',
    },
];

const form = ref({
    name: '',
    description: ''
});

const submit = () => {
    router.post(route('menu-category.store'), form.value);
};
</script>

<template>
    <Head title="Reservation" />

    <AppLayout :breadcrumbs="breadcrumbs">

        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex flex-col">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="max-w-4xl mx-auto p-6">
            <h2 class="text-xl font-bold mb-4">Add Menu Category</h2>
            <form @submit.prevent="submit">
                <div>
                    <label class="block">Name</label>
                    <input v-model="form.name" class="w-full border p-2" required />
                </div>
                <div class="mt-2">
                    <label class="block">Description</label>
                    <textarea v-model="form.description" class="w-full border p-2"></textarea>
                </div>
                <button type="submit" class="mt-4 bg-green-500 text-white px-4 py-2">Save</button>
            </form>
        </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
