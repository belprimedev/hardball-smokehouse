<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { 
    Users, 
    Utensils, 
    Calendar, 
    FolderOpen, 
    TrendingUp, 
    Star,
    ChefHat,
    Clock,
    Phone,
    Mail,
    MapPin,
    Plus,
    Eye
} from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

interface MenuItem {
    id: number;
    name: string;
    description: string | null;
    price: number;
    image_path: string | null;
    is_featured: boolean;
    is_chef_special: boolean;
    category?: {
        id: number;
        name: string;
    };
}

interface Reservation {
    id: number;
    customer_name: string;
    customer_email: string;
    customer_phone: string;
    reservation_date: string;
    reservation_time: string;
    number_of_guest: number;
    special_request: string | null;
    created_at: string;
}

interface MenuCategory {
    id: number;
    name: string;
    menu_items_count: number;
}

const props = defineProps<{
    stats: {
        totalMenuItems: number;
        totalCategories: number;
        totalUsers: number;
        totalReservations: number;
    };
    featuredItems: MenuItem[];
    chefSpecialItems: MenuItem[];
    recentReservations: Reservation[];
    menuItemsByCategory: MenuCategory[];
    reservationsByDate: any[];
    topCategories: MenuCategory[];
}>();

// Format price
const formatPrice = (price: number) => {
    return new Intl.NumberFormat('en-GB', {
        style: 'currency',
        currency: 'GBP'
    }).format(price);
};

// Format date
const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-GB', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
    });
};

// Format time
const formatTime = (timeString: string) => {
    return new Date(`2000-01-01T${timeString}`).toLocaleTimeString('en-GB', {
        hour: '2-digit',
        minute: '2-digit'
    });
};

// Get today's reservations
const todaysReservations = computed(() => {
    const today = new Date().toISOString().split('T')[0];
    return props.recentReservations.filter(reservation => 
        reservation.reservation_date === today
    );
});
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-6">
            <!-- Welcome Header -->
            <div class="bg-gradient-to-r from-green-600 to-yellow-600 rounded-xl p-6 text-white">
                <h1 class="text-3xl font-bold mb-2">Welcome to Hardball Smokehouse</h1>
                <p class="text-green-100">Manage your Caribbean restaurant operations from one place</p>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Menu Items -->
                <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Menu Items</p>
                            <p class="text-3xl font-bold text-gray-900">{{ stats.totalMenuItems }}</p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-full">
                            <Utensils class="w-6 h-6 text-green-600" />
                        </div>
                    </div>
                    <div class="mt-4">
                        <Link :href="route('menu-items.index')" 
                              class="text-sm text-green-600 hover:text-green-700 font-medium">
                            View all items →
                        </Link>
                    </div>
                </div>

                <!-- Total Categories -->
                <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Menu Categories</p>
                            <p class="text-3xl font-bold text-gray-900">{{ stats.totalCategories }}</p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-full">
                            <FolderOpen class="w-6 h-6 text-blue-600" />
                        </div>
                    </div>
                    <div class="mt-4">
                        <Link :href="route('menu-category.index')" 
                              class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                            Manage categories →
                        </Link>
                    </div>
                </div>

                <!-- Total Users -->
                <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Registered Users</p>
                            <p class="text-3xl font-bold text-gray-900">{{ stats.totalUsers }}</p>
                        </div>
                        <div class="bg-purple-100 p-3 rounded-full">
                            <Users class="w-6 h-6 text-purple-600" />
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="text-sm text-purple-600 font-medium">
                            System users
                        </span>
                    </div>
                </div>

                <!-- Total Reservations -->
                <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Reservations</p>
                            <p class="text-3xl font-bold text-gray-900">{{ stats.totalReservations }}</p>
                        </div>
                        <div class="bg-orange-100 p-3 rounded-full">
                            <Calendar class="w-6 h-6 text-orange-600" />
                        </div>
                    </div>
                    <div class="mt-4">
                        <Link :href="route('reservation.index')" 
                              class="text-sm text-orange-600 hover:text-orange-700 font-medium">
                            View reservations →
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Recent Reservations -->
                <div class="lg:col-span-2 bg-white rounded-xl shadow-lg border border-gray-100">
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-bold text-gray-900">Recent Reservations</h2>
                            <Link :href="route('reservation.create')" 
                                  class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
                                <Plus class="w-4 h-4" />
                                New Reservation
                            </Link>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">
                            {{ todaysReservations.length }} reservations today
                        </p>
                    </div>
                    <div class="p-6">
                        <div v-if="recentReservations.length === 0" class="text-center py-8">
                            <Calendar class="w-12 h-12 text-gray-400 mx-auto mb-4" />
                            <p class="text-gray-500">No reservations yet</p>
                        </div>
                        <div v-else class="space-y-4">
                            <div v-for="reservation in recentReservations.slice(0, 5)" :key="reservation.id"
                                 class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="flex items-center space-x-4">
                                    <div class="bg-green-100 p-2 rounded-full">
                                        <Calendar class="w-4 h-4 text-green-600" />
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ reservation.customer_name }}</p>
                                        <p class="text-sm text-gray-600">{{ reservation.customer_email }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ formatDate(reservation.reservation_date) }} at {{ formatTime(reservation.reservation_time) }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">{{ reservation.number_of_guest }} guests</p>
                                    <Link :href="route('reservation.show', reservation.id)" 
                                          class="text-sm text-green-600 hover:text-green-700">
                                        View details
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-100">
                    <div class="p-6 border-b border-gray-100">
                        <h2 class="text-xl font-bold text-gray-900">Quick Actions</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <Link :href="route('menu-items.create')" 
                              class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                            <Plus class="w-5 h-5 text-green-600 mr-3" />
                            <div>
                                <p class="font-semibold text-gray-900">Add Menu Item</p>
                                <p class="text-sm text-gray-600">Create new menu item</p>
                            </div>
                        </Link>
                        
                        <Link :href="route('menu-category.create')" 
                              class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            <FolderOpen class="w-5 h-5 text-blue-600 mr-3" />
                            <div>
                                <p class="font-semibold text-gray-900">Add Category</p>
                                <p class="text-sm text-gray-600">Create new menu category</p>
                            </div>
                        </Link>
                        
                        <Link :href="route('reservation.create')" 
                              class="flex items-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors">
                            <Calendar class="w-5 h-5 text-orange-600 mr-3" />
                            <div>
                                <p class="font-semibold text-gray-900">New Reservation</p>
                                <p class="text-sm text-gray-600">Book a table</p>
                            </div>
                        </Link>
                        
                        <Link :href="route('menu')" 
                              class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                            <Eye class="w-5 h-5 text-purple-600 mr-3" />
                            <div>
                                <p class="font-semibold text-gray-900">View Menu</p>
                                <p class="text-sm text-gray-600">See public menu</p>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Featured Items Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Featured Items -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-100">
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-bold text-gray-900">Featured Items</h2>
                            <Star class="w-5 h-5 text-yellow-500" />
                        </div>
                        <p class="text-sm text-gray-600 mt-1">Highlighted menu items</p>
                    </div>
                    <div class="p-6">
                        <div v-if="featuredItems.length === 0" class="text-center py-8">
                            <Star class="w-12 h-12 text-gray-400 mx-auto mb-4" />
                            <p class="text-gray-500">No featured items</p>
                        </div>
                        <div v-else class="space-y-3">
                            <div v-for="item in featuredItems" :key="item.id"
                                 class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                        <Utensils class="w-5 h-5 text-green-600" />
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ item.name }}</p>
                                        <p class="text-sm text-gray-600">{{ item.category?.name }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-green-600">{{ formatPrice(item.price) }}</p>
                                    <Link :href="route('menu-items.edit', item.id)" 
                                          class="text-xs text-gray-500 hover:text-gray-700">
                                        Edit
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chef Special Items -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-100">
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-bold text-gray-900">Chef Specials</h2>
                            <ChefHat class="w-5 h-5 text-red-500" />
                        </div>
                        <p class="text-sm text-gray-600 mt-1">Chef's special creations</p>
                    </div>
                    <div class="p-6">
                        <div v-if="chefSpecialItems.length === 0" class="text-center py-8">
                            <ChefHat class="w-12 h-12 text-gray-400 mx-auto mb-4" />
                            <p class="text-gray-500">No chef specials</p>
                        </div>
                        <div v-else class="space-y-3">
                            <div v-for="item in chefSpecialItems" :key="item.id"
                                 class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                        <ChefHat class="w-5 h-5 text-red-600" />
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ item.name }}</p>
                                        <p class="text-sm text-gray-600">{{ item.category?.name }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-red-600">{{ formatPrice(item.price) }}</p>
                                    <Link :href="route('menu-items.edit', item.id)" 
                                          class="text-xs text-gray-500 hover:text-gray-700">
                                        Edit
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Categories Chart -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900">Menu Categories Overview</h2>
                    <p class="text-sm text-gray-600 mt-1">Items per category</p>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div v-for="category in topCategories" :key="category.id"
                             class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-gradient-to-r from-green-400 to-blue-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-bold">{{ category.name.charAt(0) }}</span>
                                </div>
                                <span class="font-medium text-gray-900">{{ category.name }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-32 bg-gray-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-green-400 to-blue-500 h-2 rounded-full"
                                         :style="{ width: `${(category.menu_items_count / Math.max(...topCategories.map(c => c.menu_items_count))) * 100}%` }">
                                    </div>
                                </div>
                                <span class="text-sm font-semibold text-gray-600 w-8 text-right">
                                    {{ category.menu_items_count }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Restaurant Info -->
            <div class="bg-gradient-to-r from-gray-900 to-gray-800 rounded-xl p-6 text-white">
                <h2 class="text-xl font-bold mb-4">Restaurant Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3">
                            <MapPin class="w-5 h-5 text-green-400" />
                            <span>24 Lloyds Ave, Ipswich IP1 3HD</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <Phone class="w-5 h-5 text-green-400" />
                            <span>+44 01473 807117</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <Mail class="w-5 h-5 text-green-400" />
                            <span>info@hardballsmokehouse.co.uk</span>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3">
                            <Clock class="w-5 h-5 text-green-400" />
                            <span>Open Daily: 12:00 - 22:00</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <TrendingUp class="w-5 h-5 text-green-400" />
                            <span>Caribbean Smokehouse & Restaurant</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
