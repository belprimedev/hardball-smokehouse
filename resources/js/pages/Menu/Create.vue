<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Create Menu Item',
        href: '/menu.create',
    },
];

const newItem = ref({
  name: '',
  price: '',
  description: '',
  category_id: ''
});

const props = defineProps({
  categories: Array
});

const createItem = () => {
  router.post('/menu-items', newItem.value, {
    onSuccess: () => {
      router.visit('/menu-items'); // Redirect to menu list
    }
  });
};
</script>

<template>
    <Head title="Reservation" />

    <AppLayout :breadcrumbs="breadcrumbs">

        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid grid-cols-1 py-10 px-6 rounded-xl bg-white border border-gray-200 gap-4 md:grid-cols-2 md:gap-6 xl:grid-cols-4 2xl:gap-7.5">
                
                <form @submit.prevent="createItem">
                    <div class="mb-4 sm:mb-8">
                        <label for="item_name" class="block mb-2 text-sm font-medium dark:text-white">Item Name</label>
                        <input v-model="newItem.name" type="text" id="item_name" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-green-500 focus:ring-green-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="Item Name" required>
                    </div>

                    <div class="mb-4 sm:mb-8">
                        <label for="item_price" class="block mb-2 text-sm font-medium dark:text-white">Item Price</label>
                        <input v-model="newItem.price" type="text" id="item_price" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-green-500 focus:ring-green-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="Item Price" required>
                    </div>

                    <div class="mb-4 sm:mb-8">
                        <label for="item_description" class="block mb-2 text-sm font-medium dark:text-white">Item Description</label>
                        <textarea v-model="newItem.description" type="text" id="item_description" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-green-500 focus:ring-green-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="Item description"></textarea>
                    </div>
                <!-- <input  class="block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6" required />
                 -->
                    <!-- <label>Price:</label>
                <input  type="number" required /> -->
                
                <label for="categories" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select a Category</label>
                    <select v-model="newItem.category_id" id="categories" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-green-500 dark:focus:border-green-500">
                        <option> --Select a category-- </option>
                        <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                    </select>
                
                    <div class="py-4">
                        <button type="button" @click="router.visit('/menu-items')" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Cancel</button>
                        <button type="submit" class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
