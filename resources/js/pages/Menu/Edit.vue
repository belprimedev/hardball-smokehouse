<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { ref, watchEffect } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Create Menu Item',
        href: '/menu.create',
    },
];

const props = defineProps({
  menuItem: Object,
  categories: Array
});

const editingItem = ref({ ...props.menuItem });

// Ensure boolean fields have default values (Vue may treat them as undefined)
watchEffect(() => {
  editingItem.value.is_visible = !!props.menuItem.is_visible;
  editingItem.value.is_available = !!props.menuItem.is_available;
  editingItem.value.is_featured = !!props.menuItem.is_featured;
  editingItem.value.is_chef_special = !!props.menuItem.is_chef_special;
});

const imageInput = ref(null);
const previewImage = ref(null);

const handleImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        previewImage.value = URL.createObjectURL(file);
        editingItem.value.image = file;
    }
};

// Update the updateItem function
const updateItem = () => {
    console.log('Editing Item Data:', editingItem.value);

    const formData = new FormData();
    
    // Add _method field to handle PUT request
    formData.append('_method', 'PUT');
    
    // Append all form fields with explicit type conversion
    formData.append('name', editingItem.value.name || '');
    formData.append('price', editingItem.value.price || '');
    formData.append('category_id', editingItem.value.category_id || '');
    formData.append('description', editingItem.value.description || '');
    formData.append('short_label', editingItem.value.short_label || '');
    formData.append('side_note', editingItem.value.side_note || '');
    
    // Boolean fields need explicit conversion
    formData.append('is_visible', editingItem.value.is_visible ? '1' : '0');
    formData.append('is_available', editingItem.value.is_available ? '1' : '0');
    formData.append('is_featured', editingItem.value.is_featured ? '1' : '0');
    formData.append('is_chef_special', editingItem.value.is_chef_special ? '1' : '0');

    // Log FormData contents
    for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
    }

    // Append image if exists
    if (imageInput.value?.files[0]) {
        formData.append('image', imageInput.value.files[0]);
    }

    // Change to post method but include _method field for PUT
    router.post(route('menu-items.update', editingItem.value.id), formData, {
        onSuccess: () => {
            router.get(route('menu-items.index'));
        },
        onError: (errors) => {
            console.error('Update failed:', errors);
        },
        preserveScroll: true,
        forceFormData: true,
    });
};
</script>

<template>
    <Head title="Reservation" />

    <AppLayout :breadcrumbs="breadcrumbs">

        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="py-10 px-6 border border-gray-200 rounded-xl shadow-sm gap-4 md:grid-cols-2 md:gap-6 xl:grid-cols-4 2xl:gap-7.5 dark:bg-gray-950 ">
                <form @submit.prevent="updateItem" enctype="multipart/form-data">
                    <div class="grid grid-cols-4 gap-x-4">
                        <div class="grid grid-cols-2 gap-x-2 col-span-3">
                            <div class="mb-4 sm:mb-8 col-span-3">
                                <label for="hs-feedback-post-comment-name-1" class="block mb-2 text-sm font-medium dark:text-white">Item Name</label>
                                <input v-model="editingItem.name" type="text" id="hs-feedback-post-comment-name-1" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-green-500 focus:ring-green-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="Item Name" required>
                            </div>
                                        
                            <div class="mb-4 sm:mb-8">
                                <label for="name" class="block mb-2 text-sm font-medium dark:text-white">Item Price</label>
                                <input v-model="editingItem.price" type="text" id="name" class="w-full rounded-md border border-green-600/50 bg-white py-3 px-6 text-base font-medium text-green-800 outline-none focus:border-green-600 focus:shadow-md" placeholder="Item Price" required>
                            </div>

                            <div class="mb-4 sm:mb-8">
                                <label for="categories" class="block mb-2 text-sm font-medium dark:text-white">Category</label>
                                <select v-model="editingItem.category_id" id="categories" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-green-500 dark:focus:border-green-500">
                                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                                </select>
                            </div>

                            <div class="mb-4 sm:mb-8 col-span-3">
                                <label for="short_label" class="block mb-2 text-sm font-medium dark:text-white">Short Label</label>
                                <input type="text" v-model="editingItem.short_label" id="short_label" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-green-500 focus:ring-green-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="Short Label/ Featured title" >
                            </div>

                            <div class="mb-4 sm:mb-8 col-span-2">
                                <label for="side_note" class="block mb-2 text-sm font-medium dark:text-white">Side Note</label>
                                <input type="text" v-model="editingItem.side_note" id="side_note" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-green-500 focus:ring-green-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="Note about this item">
                            </div>

                            <div class="mb-4 sm:mb-8 col-span-4">
                                <label for="item_description" class="block mb-2 text-sm font-medium dark:text-white">Item Description</label>
                                <textarea v-model="editingItem.description" type="text" id="item_description" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-green-500 focus:ring-green-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="Item description"></textarea>
                            </div>   

                            <div class="py-4">
                                <button type="button" @click="router.get(route('menu-items.index'))" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Cancel</button>
                                <button type="submit" class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800">Save</button>
                            </div>
                        </div>
                        <div class="col-span-1 px-4">
                        <label class="relative flex items-center mb-5 cursor-pointer">
                            <input v-model="editingItem.is_visible" type="checkbox" class="sr-only peer" />
                            <div class="w-9 h-5 bg-gray-200 hover:bg-gray-300 peer-focus:outline-none rounded-full peer transition-all ease-in-out duration-500 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-600 hover:peer-checked:bg-green-700"></div>
                            <span class="ml-3 text-sm font-medium text-gray-600">Show / Visible</span>
                        </label>

                        <label class="relative flex items-center mb-5 cursor-pointer">
                            <input v-model="editingItem.is_available" type="checkbox" class="sr-only peer" />
                            <div class="w-9 h-5 bg-gray-200 hover:bg-gray-300 peer-focus:outline-none rounded-full peer transition-all ease-in-out duration-500 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-600 hover:peer-checked:bg-green-700"></div>
                            <span class="ml-3 text-sm font-medium text-gray-600">Available</span>
                        </label>

                        <div class="flex items-center mb-5">
                            <label class="relative flex items-center cursor-pointer">
                                <input v-model="editingItem.is_featured" type="checkbox" class="sr-only peer" />
                                <div class="w-9 h-5 bg-gray-200 hover:bg-gray-300 peer-focus:outline-none rounded-full peer transition-all ease-in-out duration-500 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-600 hover:peer-checked:bg-green-700"></div>
                            </label>
                            <span class="ml-3 text-sm font-medium text-gray-600">Featured</span>
                        </div>

                        <div class="flex items-center">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input v-model="editingItem.is_chef_special" type="checkbox" class="sr-only peer" />
                                <div class="w-9 h-5 bg-gray-200 hover:bg-gray-300 peer-focus:outline-none rounded-full peer transition-all ease-in-out duration-500 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-600 hover:peer-checked:bg-green-700"></div>
                            </label>
                        <span class="ml-3 text-sm font-medium text-gray-600">Chef's Special</span>
                        </div>

                        <div class="my-4 pt-10 sm:mb-8">
                            <label class="block mb-2 text-sm font-medium dark:text-white">
                                Featured Image
                            </label>
                            <div class="mt-2">
                                <img 
                                    v-if="previewImage || editingItem.image_path" 
                                    :src="previewImage || `/storage/${editingItem.image_path}`" 
                                    class="w-44 h-44 object-cover rounded-lg mb-2"
                                />
                                <input
                                    ref="imageInput"
                                    type="file"
                                    @change="handleImageChange"
                                    accept="image/*"
                                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                />
                            </div>
                        </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
