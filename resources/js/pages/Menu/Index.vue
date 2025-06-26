<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import { ChevronUp, ChevronDown } from 'lucide-vue-next';

interface MenuItem {
    id: number;
    name: string;
    description: string | null;
    price: string;
    image_path: string | null;
    category?: {
        name: string;
    };
    created_at: string;
}

interface MenuCategory {
    id: number;
    name: string;
}

interface PaginatedData {
    data: MenuItem[];
    current_page: number;
    last_page: number;
    total: number;
    prev_page_url: string | null;
    next_page_url: string | null;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Menu',
        href: '/menu.index',
    },
];

const props = defineProps({
    menuItems: {
        type: Object as () => PaginatedData,
        required: true
    },
    categories: {
        type: Array as () => MenuCategory[],
        required: true
    },
    search: {
        type: String,
        default: ''
    },
    filters: {
        type: Object,
        default: () => ({
            category: '',
            min_price: '',
            max_price: '',
            availability: '',
            visibility: '',
            featured: '',
            chef_special: ''
        })
    },
    sort: {
        type: Object,
        default: () => ({
            by: 'created_at',
            order: 'desc'
        })
    }
});
//console.log(props.menuItems);
// Reactive filter state
const searchQuery = ref(props.search);
const showFilters = ref(false);
const filterState = ref({
    category: props.filters.category,
    min_price: props.filters.min_price,
    max_price: props.filters.max_price,
    availability: props.filters.availability,
    visibility: props.filters.visibility,
    featured: props.filters.featured,
    chef_special: props.filters.chef_special
});

// Handle page navigation
const goToPage = (url: string | null) => {
    if (url) {
        router.get(url);
    }
};

// Navigate to the create page
const createItem = () => {
    router.get('/menu-items/create');
};

const editItem = (item: MenuItem) => {
    router.get(`/menu-items/${item.id}/edit`);
};

const deleteItem = (id: number) => {
    if (confirm("Are you sure you want to delete this item?")) {
        router.delete(`/menu-items/${id}`);
    }
};

// Debounced search function
let searchTimeout: number;

const performSearch = () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        updateResults();
    }, 300);
};

// Update results with current filters and search
const updateResults = () => {
    const params: Record<string, any> = {
        search: searchQuery.value,
        ...filterState.value,
        sort_by: props.sort.by,
        sort_order: props.sort.order
    };
    
    // Remove empty values
    Object.keys(params).forEach(key => {
        if (params[key] === '' || params[key] === null || params[key] === undefined) {
            delete params[key];
        }
    });
    
    router.get(route('menu-items.index'), params, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    });
};

// Handle sorting
const handleSort = (column: string) => {
    try {
        const newOrder = props.sort.by === column && props.sort.order === 'asc' ? 'desc' : 'asc';
        
        const params: Record<string, any> = {
            search: searchQuery.value,
            ...filterState.value,
            sort_by: column,
            sort_order: newOrder
        };
        
        // Remove empty values
        Object.keys(params).forEach(key => {
            if (params[key] === '' || params[key] === null || params[key] === undefined) {
                delete params[key];
            }
        });
        
        router.get(route('menu-items.index'), params, {
            preserveScroll: true,
            replace: true,
            onError: (errors) => {
                console.error('Sort error:', errors);
            },
            onSuccess: () => {
                console.log('Sort successful');
            }
        });
    } catch (error) {
        console.error('Error in handleSort:', error);
    }
};

// Clear all filters
const clearFilters = () => {
    filterState.value = {
        category: '',
        min_price: '',
        max_price: '',
        availability: '',
        visibility: '',
        featured: '',
        chef_special: ''
    };
    searchQuery.value = '';
    updateResults();
};

// Apply filters
const applyFilters = () => {
    updateResults();
};

// Watch for search query changes
watch(searchQuery, () => {
    performSearch();
});

// Watch for filter changes
watch(filterState, () => {
    // Don't auto-apply filters, let user click apply button
}, { deep: true });

// Use the server-side data directly
const filteredItems = computed(() => {
    if (!props.menuItems) {
        return [];
    }
    
    if (!props.menuItems.data) {
        return [];
    }
    
    return props.menuItems.data;
});

// Get sort icon for column
const getSortIcon = (column: string) => {
    if (props.sort.by !== column) return null;
    return props.sort.order === 'asc' ? ChevronUp : ChevronDown;
};

// Check if any filters are active
const hasActiveFilters = computed(() => {
    return Object.values(filterState.value).some(value => value !== '') || searchQuery.value !== '';
});
</script>

<template>
    <Head title="Menu Items" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col h-full overflow-hidden p-4 dark:bg-gray-900">
            <div class="flex justify-between bg-white rounded-xl shadow-sm dark:bg-slate-900 dark:border-neutral-700 overflow-hidden">
                <!-- Header - Fixed -->
                <div class="shrink-0 bg-white border-b border-gray-200 dark:bg-gray-900 dark:border-neutral-900 p-4">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                        Food Listing
                    </h2>
                    <p class="text-sm text-yellow-500 dark:text-neutral-400">
                        List of all food & beverage items.
                    </p>
                </div>

                <div class="flex items-center gap-4 p-4">
                    <!-- Search Input -->
                    <div class="relative">
                        <input type="text" v-model="searchQuery" placeholder="Search items..."
                            class="py-2 px-3 ps-9 pe-9 block w-full border-gray-500 ring-1 ring-emerald-200 rounded-lg text-sm focus:border-green-500 focus:ring-green-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:ring-neutral-600" />
                        <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-3">
                            <svg class="size-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8" />
                                <path d="m21 21-4.3-4.3" />
                            </svg>
                        </div>
                        <!-- Clear search button -->
                        <button v-if="searchQuery" 
                                @click="searchQuery = ''; performSearch()"
                                class="absolute inset-y-0 end-0 flex items-center pe-3 text-gray-400 hover:text-gray-600">
                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18" />
                                <path d="m6 6 12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Filter Toggle Button -->
                    <button @click="showFilters = !showFilters"
                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="22,3 2,3 10,12.46 10,19 14,21 14,12.46" />
                        </svg>
                        Filters
                        <span v-if="hasActiveFilters" class="inline-flex items-center justify-center w-5 h-5 text-xs font-medium bg-red-500 text-white rounded-full">
                            {{ Object.values(filterState).filter(v => v !== '').length + (searchQuery ? 1 : 0) }}
                        </span>
                    </button>

                    <div class="inline-flex gap-x-2">
                        <button @click="createItem"
                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-green-600 text-white hover:bg-green-700 focus:outline-none focus:bg-green-700 disabled:opacity-50 disabled:pointer-events-none">
                            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14" />
                                <path d="M12 5v14" />
                            </svg>
                            Create
                        </button>
                    </div>
                </div>
            </div>

            <!-- Filters Panel -->
            <div v-if="showFilters" class="bg-white border border-gray-200 rounded-lg p-4 mb-4 dark:bg-slate-900 dark:border-neutral-700">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Category Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Category</label>
                        <select v-model="filterState.category" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white">
                            <option value="">All Categories</option>
                            <option v-for="category in categories" :key="category.id" :value="category.id">
                                {{ category.name }}
                            </option>
                        </select>
                    </div>

                    <!-- Price Range -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Min Price</label>
                        <input type="number" v-model="filterState.min_price" placeholder="0.00" step="0.01"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Max Price</label>
                        <input type="number" v-model="filterState.max_price" placeholder="100.00" step="0.01"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white" />
                    </div>

                    <!-- Availability -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Availability</label>
                        <select v-model="filterState.availability" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white">
                            <option value="">All</option>
                            <option value="1">Available</option>
                            <option value="0">Not Available</option>
                        </select>
                    </div>

                    <!-- Visibility -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Visibility</label>
                        <select v-model="filterState.visibility" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white">
                            <option value="">All</option>
                            <option value="1">Visible</option>
                            <option value="0">Hidden</option>
                        </select>
                    </div>

                    <!-- Featured -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Featured</label>
                        <select v-model="filterState.featured" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white">
                            <option value="">All</option>
                            <option value="1">Featured</option>
                            <option value="0">Not Featured</option>
                        </select>
                    </div>

                    <!-- Chef Special -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Chef Special</label>
                        <select v-model="filterState.chef_special" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white">
                            <option value="">All</option>
                            <option value="1">Chef Special</option>
                            <option value="0">Regular</option>
                        </select>
                    </div>
                </div>

                <!-- Filter Actions -->
                <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-200 dark:border-neutral-700">
                    <button @click="clearFilters" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700">
                        Clear All Filters
                    </button>
                    <button @click="applyFilters" 
                            class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                        Apply Filters
                    </button>
                </div>
            </div>

            <!-- Table Container - Scrollable -->
            <div class="flex-1 overflow-auto p-4 border-t border-gray-200 dark:border-gray-800">
                <div class="overflow-x-auto">
                    <div class="min-w-full inline-block align-middle">
                        <table class="min-w-[800px] w-full table-auto divide-y divide-gray-200 dark:divide-neutral-700 dark:border dark:border-gray-800">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th scope="col" class="w-[200px] px-6 py-3 text-start">
                                        <button @click="handleSort('name')" 
                                                class="flex items-center gap-x-2 hover:text-gray-900 dark:hover:text-white transition-colors">
                                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                                Name
                                            </span>
                                            <component :is="getSortIcon('name')" v-if="getSortIcon('name')" class="w-4 h-4" />
                                        </button>
                                    </th>

                                    <th scope="col" class="w-[100px] px-6 py-3 text-start">
                                        <button @click="handleSort('price')" 
                                                class="flex items-center gap-x-2 hover:text-gray-900 dark:hover:text-white transition-colors">
                                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                                Price
                                            </span>
                                            <component :is="getSortIcon('price')" v-if="getSortIcon('price')" class="w-4 h-4" />
                                        </button>
                                    </th>

                                    <th scope="col" class="w-[250px] px-6 py-3 text-start">
                                        <div class="flex items-center gap-x-2">
                                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                                Details
                                            </span>
                                        </div>
                                    </th>

                                    <th scope="col" class="w-[150px] px-6 py-3 text-start">
                                        <div class="flex items-center gap-x-2">
                                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                                Category
                                            </span>
                                        </div>
                                    </th>

                                    <th scope="col" class="w-[150px] px-6 py-3 text-start">
                                        <button @click="handleSort('created_at')" 
                                                class="flex items-center gap-x-2 hover:text-gray-900 dark:hover:text-white transition-colors">
                                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                                Created
                                            </span>
                                            <component :is="getSortIcon('created_at')" v-if="getSortIcon('created_at')" class="w-4 h-4" />
                                        </button>
                                    </th>

                                    <th scope="col" class="w-[150px] px-6 py-3 text-end"></th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200 dark:divide-neutral-700 dark:bg-gray-800">
                                <tr v-for="item in filteredItems" :key="item.id">
                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-6 py-3">
                                            <div class="flex items-center gap-x-2">
                                                <img class="inline-block size-6 rounded-full object-cover"
                                                    :src="item.image_path ? `/storage/${item.image_path}` : '/img/food/burger.png'"
                                                    :alt="item.name" />
                                                <div class="grow">
                                                    <span class="text-sm text-gray-600 dark:text-neutral-400">{{
                                                        item.name }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-6 py-3">
                                            <span class="text-sm text-gray-600 dark:text-neutral-400">£{{ item.price
                                                }}</span>
                                        </div>
                                    </td>

                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-6 py-3">
                                            <button type="button"
                                                class="py-2 px-3 inline-flex items-center gap-x-2 text-xs rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800 dark:focus:bg-neutral-800">
                                                <span class="truncate max-w-[200px]">{{ item.description }}</span>
                                                <svg class="shrink-0 size-4 text-gray-400 dark:text-neutral-600"
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <rect width="8" height="4" x="8" y="2" rx="1" ry="1" />
                                                    <path
                                                        d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>

                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-6 py-3">
                                            <span
                                                class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-teal-100 text-teal-800 rounded-full dark:bg-teal-500/10 dark:text-teal-500">
                                                <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" width="16"
                                                    height="16" fill="currentColor" viewBox="0 0 16 16">
                                                    <path
                                                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                                </svg>
                                                {{ item.category ? item.category.name : 'No Category' }}
                                            </span>
                                        </div>
                                    </td>

                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-6 py-3">
                                            <span class="text-sm text-gray-600 dark:text-neutral-400">{{ new Date(item.created_at).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' }) }}</span>
                                        </div>
                                    </td>

                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-6 py-3 text-end">
                                            <button @click="editItem(item)"
                                                class="bg-orange-500 rounded-l-md p-2 text-white hover:shadow-lg text-xs font-thin">
                                                Edit
                                            </button>
                                            <button @click="deleteItem(item.id)"
                                                class="bg-red-600 rounded-r-md p-2 text-white hover:shadow-lg text-xs font-thin">
                                                Remove
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Footer - Fixed -->
            <div class="shrink-0 flex justify-between bg-white border-t border-gray-200 px-6 py-4 dark:bg-neutral-900 dark:border-neutral-700">
                <div>
                    <p class="text-sm text-gray-600 dark:text-neutral-400">
                        <span class="font-semibold text-gray-800 dark:text-neutral-200">{{ menuItems.total }}</span>
                        {{ search ? 'search results' : 'results' }}
                        <span v-if="search" class="text-gray-500">for "{{ search }}"</span>
                    </p>
                </div>

                <div>
                    <div class="inline-flex items-center gap-x-2">
                        <button type="button" v-if="menuItems.prev_page_url" @click="goToPage(menuItems.prev_page_url)"
                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="m15 18-6-6 6-6" />
                            </svg>
                            Prev
                        </button>

                        <span class="px-1">Page {{ menuItems.current_page }} of {{ menuItems.last_page }}</span>

                        <button type="button" v-if="menuItems.next_page_url" @click="goToPage(menuItems.next_page_url)"
                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                            Next
                            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="m9 18 6-6-6-6" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
