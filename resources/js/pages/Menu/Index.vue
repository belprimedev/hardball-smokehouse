<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import { ChevronUp, ChevronDown, Plus, Search, Filter } from 'lucide-vue-next';

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

// Calculate statistics
const stats = {
    total: props.menuItems.total,
    categories: props.categories.length,
    averagePrice: props.menuItems.data.length > 0 
        ? (props.menuItems.data.reduce((sum, item) => sum + parseFloat(item.price), 0) / props.menuItems.data.length).toFixed(2)
        : '0.00',
    thisMonth: props.menuItems.data.filter(item => {
        const created = new Date(item.created_at);
        const now = new Date();
        return created.getMonth() === now.getMonth() && created.getFullYear() === now.getFullYear();
    }).length
};
</script>

<template>
    <Head title="Menu Items" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-6">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-600 to-emerald-600 rounded-xl p-6 text-white">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">Menu Management</h1>
                        <p class="text-green-100">Manage food & beverage items and categories</p>
                    </div>
                    <button @click="createItem"
                        class="inline-flex items-center gap-2 bg-white text-green-600 px-6 py-3 rounded-lg font-bold hover:bg-gray-100 transition-colors">
                        <Plus class="w-5 h-5" />
                        Add Item
                    </button>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Items</p>
                            <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ stats.total }}</p>
                        </div>
                        <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Categories</p>
                            <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ stats.categories }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Avg Price</p>
                            <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">£{{ stats.averagePrice }}</p>
                        </div>
                        <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">This Month</p>
                            <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ stats.thisMonth }}</p>
                        </div>
                        <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <!-- Search Input -->
                        <div class="flex-1">
                            <div class="relative">
                                <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" />
                                <input type="text" v-model="searchQuery" placeholder="Search items..."
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white" />
                            </div>
                        </div>

                        <!-- Filter Toggle Button -->
                        <button @click="showFilters = !showFilters"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 dark:bg-gray-800 dark:text-white dark:hover:bg-gray-700 transition-colors">
                            <Filter class="w-4 h-4" />
                            Filters
                            <span v-if="hasActiveFilters" class="inline-flex items-center justify-center w-5 h-5 text-xs font-medium bg-red-500 text-white rounded-full">
                                {{ Object.values(filterState).filter(v => v !== '').length + (searchQuery ? 1 : 0) }}
                            </span>
                        </button>
                    </div>
                </div>

                <!-- Filters Panel -->
                <div v-if="showFilters" class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Category Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category</label>
                            <select v-model="filterState.category" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">All Categories</option>
                                <option v-for="category in categories" :key="category.id" :value="category.id">
                                    {{ category.name }}
                                </option>
                            </select>
                        </div>

                        <!-- Price Range -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Min Price</label>
                            <input type="number" v-model="filterState.min_price" placeholder="0.00" step="0.01"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Max Price</label>
                            <input type="number" v-model="filterState.max_price" placeholder="100.00" step="0.01"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                        </div>

                        <!-- Availability -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Availability</label>
                            <select v-model="filterState.availability" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">All</option>
                                <option value="1">Available</option>
                                <option value="0">Not Available</option>
                            </select>
                        </div>
                    </div>

                    <!-- Filter Actions -->
                    <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                        <button @click="clearFilters" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600">
                            Clear All Filters
                        </button>
                        <button @click="applyFilters" 
                                class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                            Apply Filters
                        </button>
                    </div>
                </div>
            </div>

            <!-- Menu Items Table -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Menu Items</h2>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    <button @click="handleSort('name')" 
                                            class="flex items-center gap-x-2 hover:text-gray-900 dark:hover:text-white transition-colors">
                                        <span>Name</span>
                                        <component :is="getSortIcon('name')" v-if="getSortIcon('name')" class="w-4 h-4" />
                                    </button>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    <button @click="handleSort('price')" 
                                            class="flex items-center gap-x-2 hover:text-gray-900 dark:hover:text-white transition-colors">
                                        <span>Price</span>
                                        <component :is="getSortIcon('price')" v-if="getSortIcon('price')" class="w-4 h-4" />
                                    </button>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Category
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    <button @click="handleSort('created_at')" 
                                            class="flex items-center gap-x-2 hover:text-gray-900 dark:hover:text-white transition-colors">
                                        <span>Created</span>
                                        <component :is="getSortIcon('created_at')" v-if="getSortIcon('created_at')" class="w-4 h-4" />
                                    </button>
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            <tr v-for="item in filteredItems" :key="item.id" class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <img class="h-10 w-10 rounded-full object-cover"
                                            :src="item.image_path ? `/storage/${item.image_path}` : '/img/food/burger.png'"
                                            :alt="item.name" />
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ item.name }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ item.description }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    £{{ item.price }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-teal-100 text-teal-800 dark:bg-teal-500/10 dark:text-teal-500">
                                        {{ item.category ? item.category.name : 'No Category' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ new Date(item.created_at).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' }) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <button @click="editItem(item)"
                                            class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                            Edit
                                        </button>
                                        <button @click="deleteItem(item.id)"
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <div v-if="filteredItems.length === 0" class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No menu items</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating a new menu item.</p>
                        <div class="mt-6">
                            <button @click="createItem"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <Plus class="-ml-1 mr-2 h-5 w-5" />
                                Add Item
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="props.menuItems.last_page > 1" class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700 dark:text-gray-300">
                            Showing {{ (props.menuItems.current_page - 1) * 15 + 1 }} to 
                            {{ Math.min(props.menuItems.current_page * 15, props.menuItems.total) }} of 
                            {{ props.menuItems.total }} results
                        </div>
                        
                        <div class="flex space-x-2">
                            <button v-if="props.menuItems.prev_page_url" @click="goToPage(props.menuItems.prev_page_url)"
                                class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 transition-colors">
                                Previous
                            </button>
                            
                            <span class="px-3 py-2 text-sm text-gray-700 dark:text-gray-300">
                                Page {{ props.menuItems.current_page }} of {{ props.menuItems.last_page }}
                            </span>
                            
                            <button v-if="props.menuItems.next_page_url" @click="goToPage(props.menuItems.next_page_url)"
                                class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 transition-colors">
                                Next
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
