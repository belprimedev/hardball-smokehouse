<script setup lang="ts">
import MainLayout from '@/layouts/MainLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';

interface MenuCategory {
    id: number;
    name: string;
    description: string;
}

interface MenuItem {
    id: number;
    category_id: number;
    name: string;
    description: string;
    price: number;
    side_note?: string;
    is_featured: boolean;
    is_chef_special: boolean;
}

const props = defineProps<{
    menuCategories: MenuCategory[];
    menuItems: MenuItem[];
}>();

const activeCategory = ref<string>('');
const observer = ref<IntersectionObserver | null>(null);

const setupIntersectionObserver = () => {
    observer.value = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    activeCategory.value = entry.target.id;
                }
            });
        },
        {
            rootMargin: '-50% 0px -50% 0px',
            threshold: 0,
        }
    );

    props.menuCategories.forEach((category) => {
        const element = document.getElementById(`category-${category.id}`);
        if (element && observer.value) {
            observer.value.observe(element);
        }
    });
};

const scrollToCategory = (categoryId: number) => {
    const element = document.getElementById(`category-${categoryId}`);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth' });
    }
};

onMounted(() => {
    setupIntersectionObserver();
});

onUnmounted(() => {
    if (observer.value) {
        observer.value.disconnect();
    }
});
</script>

<template>
    <Head title="Menu" />

    <MainLayout>
        <!-- Hero Section -->
        <div class="relative overflow-hidden bg-gray-900">
            <div class="max-w-[85rem] mx-auto px-4 sm:px-6 lg:px-8 py-24">
                <div class="text-center">
                    <h1 class="text-4xl font-bold text-white sm:text-6xl">
                        Our Menu
                    </h1>
                    <p class="mt-3 text-lg text-gray-300">
                        Discover our delicious selection of dishes and drinks
                    </p>
                </div>
            </div>
        </div>

        <!-- Scroll Spy Navigation -->
        <div class="sticky top-0 z-50 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-[85rem] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex overflow-x-auto py-4 gap-4 hide-scrollbar">
                    <button
                        v-for="category in menuCategories"
                        :key="category.id"
                        @click="scrollToCategory(category.id)"
                        class="flex-shrink-0 px-4 py-2 rounded-full text-sm font-medium transition-colors"
                        :class="{
                            'bg-emerald-600 text-white': activeCategory === `category-${category.id}`,
                            'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800': activeCategory !== `category-${category.id}`
                        }"
                    >
                        {{ category.name }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Menu Categories -->
        <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
            

            <!-- Menu Items by Category -->
            <div class="mt-12 space-y-8">
                <template v-for="category in menuCategories" :key="category.id">
                    <div :id="`category-${category.id}`" class="space-y-4 scroll-mt-24">
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                            {{ category.name }}
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <template v-for="item in menuItems.filter(item => item.category_id === category.id)" :key="item.id">
                                <div class="group flex flex-col h-full bg-white border border-gray-200 shadow-sm rounded-xl dark:bg-neutral-900 dark:border-neutral-700">
                                    <div class="p-4 md:p-6">
                                        <div class="flex justify-between items-center">
                                            <h3 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                                                {{ item.name }}
                                            </h3>
                                            <span class="text-lg font-bold text-emerald-600 dark:text-emerald-500">
                                                £{{ item.price }}
                                            </span>
                                        </div>
                                        <p class="mt-3 text-gray-500 dark:text-neutral-400">
                                            {{ item.description }}
                                        </p>
                                        <div v-if="item.side_note" class="mt-2 text-sm text-yellow-600 dark:text-yellow-500">
                                            {{ item.side_note }}
                                        </div>
                                        <div class="mt-4 flex flex-wrap gap-2">
                                            <span v-if="item.is_featured" class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800/30 dark:text-blue-500">
                                                Featured
                                            </span>
                                            <span v-if="item.is_chef_special" class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-500">
                                                Chef's Special
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </MainLayout>
</template>

<style scoped>
.hide-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

.hide-scrollbar::-webkit-scrollbar {
    display: none;
}
</style> 