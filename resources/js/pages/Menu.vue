<script setup lang="ts">
import MainLayout from '@/layouts/MainLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted, computed } from 'vue';

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
    image_path?: string;
    is_available: boolean;
}

const props = defineProps<{
    menuCategories: MenuCategory[];
    menuItems: MenuItem[];
}>();

const activeCategory = ref<string>('');
const observer = ref<IntersectionObserver | null>(null);
const activeFilter = ref<string>('all');

// Filter out beverage categories
const filteredCategories = computed(() => {
    return props.menuCategories.filter(category => 
        !['Beer', 'Cocktails & Beverages'].includes(category.name)
    );
});

// Computed property for filtered items
const filteredItems = computed(() => {
    const items = props.menuItems.filter(item => item.is_available);
    
    if (activeFilter.value === 'all') return items;
    if (activeFilter.value === 'featured') return items.filter(item => item.is_featured);
    if (activeFilter.value === 'chef-special') return items.filter(item => item.is_chef_special);
    return items;
});

// Function to generate proper image URL
const getImageUrl = (imagePath: string | null | undefined): string | null => {
    if (!imagePath) return null;
    const url = `/img/food/${imagePath}`;
    return url;
};

// Function to handle image load errors
const handleImageError = (event: Event) => {
    const img = event.target as HTMLImageElement;
    img.style.display = 'none';
    // The fallback SVG will be shown instead
};

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
        <div class="relative overflow-hidden bg-gradient-to-br from-emerald-900 to-emerald-800" style="background: linear-gradient(
                        rgba(0, 0, 0, 0.9),rgba(0, 0, 0, 0.4)), url('../img/menu_bg.jpg'); background-size: cover; background-position: center bottom;">
            <div class="max-w-[85rem] mx-auto px-4 sm:px-6 lg:px-8 lg:py-32 py-24">
                <div class="text-center">
                    <h1 class="text-4xl mt-8 font-bold text-white sm:text-6xl knewave-regular">
                        Our Menu
                    </h1>
                    <p class="mt-3 text-lg text-yellow-300">
                        Discover our delicious selection of dishes.
                    </p>
                </div>
            </div>
        </div>

        <!-- Scroll Spy Navigation -->
        <div class="sticky top-0 z-50 bg-gray-100/80 dark:bg-gray-900/80 backdrop-blur-sm border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-[85rem] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col gap-4 py-4">
                    <!-- Category Navigation -->
                    <div class="flex overflow-x-auto gap-4 hide-scrollbar">
                        <button
                            v-for="category in filteredCategories"
                            :key="category.id"
                            @click="scrollToCategory(category.id)"
                            class="flex-shrink-0 px-4 py-2 rounded-full text-sm font-medium transition-all duration-300"
                            :class="{
                                'bg-emerald-600 text-white shadow-lg shadow-emerald-500/20': activeCategory === `category-${category.id}`,
                                'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800': activeCategory !== `category-${category.id}`
                            }"
                        >
                            {{ category.name }}
                        </button>
                    </div>

                    <!-- Filter Buttons -->
                    <div class="flex justify-end gap-2 mt-2">
                        <button
                            v-for="filter in ['all', 'featured', 'chef-special']"
                            :key="filter"
                            @click="activeFilter = filter"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300"
                            :class="{
                                'bg-yellow-400 text-white shadow-lg shadow-yellow-500/20': activeFilter === filter,
                                'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700': activeFilter !== filter
                            }"
                        >
                            {{ filter.charAt(0).toUpperCase() + filter.slice(1).replace('-', ' ') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu Categories -->
        <div class="max-w-[85rem] bg-gray- px-4 pb-10 sm:px-6 lg:px-8 lg:pb-14 mx-auto">
            <!-- Menu Items by Category -->
            <div class="mt-12 space-y-12">
                <template v-for="category in filteredCategories" :key="category.id">
                    <div :id="`category-${category.id}`" class="space-y-6 scroll-mt-24">
                        <div class="flex items-center gap-4">
                            <h2 class="text-3xl font-bold text-yellow-400 dark:text-white knewave-regular">
                                {{ category.name }}
                            </h2>
                            <div class="h-px flex-1 bg-gradient-to-r from-emerald-500/50 to-transparent"></div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <template v-for="(item, index) in filteredItems.filter(item => item.category_id === category.id)" :key="item.id">
                                <div 
                                    class="group relative flex flex-col h-full bg-white border border-gray-200 shadow-sm rounded-xl transition-all duration-300 hover:shadow-lg hover:border-emerald-500/50 dark:bg-neutral-900 dark:border-neutral-700 dark:hover:border-emerald-500/30"
                                    :style="{
                                        opacity: 0,
                                        transform: 'translateY(20px)',
                                        animation: `fadeInUp 0.5s ease-out ${index * 0.1}s forwards`
                                    }"
                                >
                                    <!-- Image placeholder with gradient background -->
                                    <div class="relative h-48 rounded-t-xl overflow-hidden bg-gradient-to-br from-emerald-100 to-emerald-50 dark:from-emerald-900/20 dark:to-emerald-800/20">
                                        <div v-if="item.image_path" class="absolute inset-0">
                                            <img :src="getImageUrl(item.image_path) || ''" :alt="item.name" class="w-full h-full object-cover" @error="handleImageError" />
                                        </div>
                                        <div v-else class="absolute inset-0 flex items-center justify-center">
                                            <svg class="w-16 h-16 text-emerald-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    </div>
                                    
                                    <div class="p-6 flex flex-col flex-grow">
                                        <div class="flex justify-between items-start gap-4">
                                            <h3 class="text-xl font-bold text-gray-800 dark:text-neutral-200 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">
                                                {{ item.name }}
                                            </h3>
                                            <span class="text-lg font-bold text-emerald-600 dark:text-emerald-400">
                                                £{{ item.price }}
                                            </span>
                                        </div>
                                        
                                        <p v-if="item.description" class="mt-3 text-gray-600 dark:text-neutral-400 line-clamp-2">
                                            {{ item.description }}
                                        </p>
                                        
                                        <div v-if="item.side_note" class="mt-2 text-sm text-yellow-600 dark:text-yellow-400 italic">
                                            {{ item.side_note }}
                                        </div>
                                        
                                        <div class="mt-4 flex flex-wrap gap-2">
                                            <span v-if="item.is_featured" class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800/30 dark:text-blue-400">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                                </svg>
                                                Featured
                                            </span>
                                            <span v-if="item.is_chef_special" class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-400">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                                </svg>
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

<style>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.hide-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

.hide-scrollbar::-webkit-scrollbar {
    display: none;
}
</style> 