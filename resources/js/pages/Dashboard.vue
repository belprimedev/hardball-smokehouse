<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
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
    Eye,
    Bell
} from 'lucide-vue-next';

// Restaurant information from general settings
const restaurantInfo = ref({
    business_name: 'Hardball Caribbean Smokehouse',
    address: '24 Lloyds Ave, Ipswich IP1 3HD',
    email: 'info@hardballsmokehouse.co.uk',
    phone: '+44 01473 807117'
});

// Fetch general settings
const fetchGeneralSettings = async () => {
    try {
        const response = await fetch('/api/general-settings');
        const settings = await response.json();
        
        // Update restaurant info with settings data
        restaurantInfo.value = {
            business_name: settings.business_name || 'Hardball Caribbean Smokehouse',
            address: settings.address || '24 Lloyds Ave, Ipswich IP1 3HD',
            email: settings.business_email || 'info@hardballsmokehouse.co.uk',
            phone: settings.contact_number || '+44 01473 807117'
        };
    } catch (error) {
        console.error('Error fetching general settings:', error);
    }
};

onMounted(() => {
    fetchGeneralSettings();
});

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

interface Notification {
    id: number;
    type: string;
    title: string;
    message: string;
    data?: any;
    is_read: boolean;
    read_at?: string;
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
    recentNotifications: Notification[];
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

// Notification state
const showNotifications = ref(false);
const notifications = ref<Notification[]>([]);
const unreadCount = ref(0);

// Fetch notifications
const fetchNotifications = async () => {
    try {
        const response = await fetch('/api/notifications/unread');
        const data = await response.json();
        notifications.value = data.notifications;
        unreadCount.value = data.unread_count;
    } catch (error) {
        console.error('Error fetching notifications:', error);
    }
};

// Toggle notifications dropdown
const toggleNotifications = () => {
    showNotifications.value = !showNotifications.value;
    if (showNotifications.value) {
        fetchNotifications();
    }
};

// Handle notification click
const handleNotificationClick = async (notification: Notification) => {
    try {
        // Mark as read
        await fetch('/api/notifications/mark-as-read', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            },
            body: JSON.stringify({ notification_id: notification.id }),
        });
        
        // Navigate to reservation if it's a reservation notification
        if (notification.type === 'reservation' && notification.data?.reservation_id) {
            window.location.href = `/reservation/${notification.data.reservation_id}`;
        }
        
        // Refresh notifications
        fetchNotifications();
    } catch (error) {
        console.error('Error handling notification click:', error);
    }
};

// Mark all notifications as read
const markAllAsRead = async () => {
    try {
        await fetch('/api/notifications/mark-all-as-read', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            },
        });
        
        // Refresh notifications
        fetchNotifications();
        showNotifications.value = false;
    } catch (error) {
        console.error('Error marking all as read:', error);
    }
};

// Format notification date
const formatNotificationDate = (dateString: string) => {
    const date = new Date(dateString);
    const now = new Date();
    const diffInHours = (now.getTime() - date.getTime()) / (1000 * 60 * 60);
    
    if (diffInHours < 1) {
        return 'Just now';
    } else if (diffInHours < 24) {
        return `${Math.floor(diffInHours)} hours ago`;
    } else {
        return date.toLocaleDateString('en-GB', {
            day: 'numeric',
            month: 'short',
            year: 'numeric'
        });
    }
};

// Initialize notifications on mount
onMounted(() => {
    fetchGeneralSettings();
    fetchNotifications();
    
    // Add click outside handler to close notifications dropdown
    document.addEventListener('click', (event) => {
        const target = event.target as Element;
        if (!target.closest('.notification-bell')) {
            showNotifications.value = false;
        }
    });
});
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-6">
            <!-- Welcome Header -->
            <div class="bg-gradient-to-r from-green-600 to-yellow-600 rounded-xl p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">Welcome to Hardball Smokehouse</h1>
                        <p class="text-green-100">Manage your Caribbean restaurant operations from one place</p>
                    </div>
                    <!-- Notification Bell -->
                    <div class="relative notification-bell">
                        <button @click="toggleNotifications" class="relative p-2 text-orange-400 hover:scale-110 hover:cursor-auto hover:rounded-full transition-colors">
                            <div class="relative">
                                <div class="group absolute -inset-2 bg-white rounded-full opacity-90"></div>
                                <Bell class="w-8 h-8 relative z-10" />
                            </div>
                            <span v-if="unreadCount > 0" 
                                  class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center animate-pulse">
                                {{ unreadCount }}
                            </span>
                        </button>
                        
                        <!-- Notification Dropdown -->
                        <div v-if="showNotifications" 
                             class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50">
                            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Notifications</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ unreadCount }} unread</p>
                            </div>
                            <div class="max-h-64 overflow-y-auto">
                                <div v-if="notifications.length === 0" class="p-4 text-center text-gray-500 dark:text-gray-400">
                                    No notifications
                                </div>
                                <div v-else class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <div v-for="notification in notifications" :key="notification.id"
                                         @click="handleNotificationClick(notification)"
                                         class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0">
                                                <div class="w-2 h-2 bg-red-500 rounded-full mt-2" v-if="!notification.is_read"></div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ notification.title }}</p>
                                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ notification.message }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                                    {{ formatNotificationDate(notification.created_at) }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                                <button @click="markAllAsRead" 
                                        class="w-full text-sm text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300 font-medium">
                                    Mark all as read
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Menu Items -->
                <div class="bg-white dark:bg-gray-900 rounded-xl p-6 shadow-lg border border-gray-100 dark:border-gray-700 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Menu Items</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ stats.totalMenuItems }}</p>
                        </div>
                        <div class="bg-green-100 dark:bg-green-900/30 p-3 rounded-full">
                            <Utensils class="w-6 h-6 text-green-600 dark:text-green-400" />
                        </div>
                    </div>
                    <div class="mt-4">
                        <Link :href="route('menu-items.index')" 
                              class="text-sm text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300 font-medium">
                            View all items →
                        </Link>
                    </div>
                </div>

                <!-- Total Categories -->
                <div class="bg-white dark:bg-gray-900 rounded-xl p-6 shadow-lg border border-gray-100 dark:border-gray-700 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Menu Categories</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ stats.totalCategories }}</p>
                        </div>
                        <div class="bg-blue-100 dark:bg-blue-900/30 p-3 rounded-full">
                            <FolderOpen class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                        </div>
                    </div>
                    <div class="mt-4">
                        <Link :href="route('menu-category.index')" 
                              class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium">
                            Manage categories →
                        </Link>
                    </div>
                </div>

                <!-- Total Users -->
                <div class="bg-white dark:bg-gray-900 rounded-xl p-6 shadow-lg border border-gray-100 dark:border-gray-700 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Registered Users</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ stats.totalUsers }}</p>
                        </div>
                        <div class="bg-purple-100 dark:bg-purple-900/30 p-3 rounded-full">
                            <Users class="w-6 h-6 text-purple-600 dark:text-purple-400" />
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="text-sm text-purple-600 dark:text-purple-400 font-medium">
                            System users
                        </span>
                    </div>
                </div>

                <!-- Total Reservations -->
                <div class="bg-white dark:bg-gray-900 rounded-xl p-6 shadow-lg border border-gray-100 dark:border-gray-700 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Reservations</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ stats.totalReservations }}</p>
                        </div>
                        <div class="bg-orange-100 dark:bg-orange-900/30 p-3 rounded-full">
                            <Calendar class="w-6 h-6 text-orange-600 dark:text-orange-400" />
                        </div>
                    </div>
                    <div class="mt-4">
                        <Link :href="route('reservation.index')" 
                              class="text-sm text-orange-600 dark:text-orange-400 hover:text-orange-700 dark:hover:text-orange-300 font-medium">
                            View reservations →
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Recent Reservations -->
                <div class="lg:col-span-2 bg-white dark:bg-gray-900 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Recent Reservations</h2>
                            <Link :href="route('reservation.create')" 
                                  class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
                                <Plus class="w-4 h-4" />
                                New Reservation
                            </Link>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                            {{ todaysReservations.length }} reservations today
                        </p>
                    </div>
                    <div class="p-6">
                        <div v-if="recentReservations.length === 0" class="text-center py-8">
                            <Calendar class="w-12 h-12 text-gray-400 dark:text-gray-500 mx-auto mb-4" />
                            <p class="text-gray-500 dark:text-gray-400">No reservations yet</p>
                        </div>
                        <div v-else class="space-y-4">
                            <div v-for="reservation in recentReservations.slice(0, 5)" :key="reservation.id"
                                 class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-800 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <div class="flex items-center space-x-4">
                                    <div class="bg-green-100 dark:bg-green-900/30 p-2 rounded-full">
                                        <Calendar class="w-4 h-4 text-green-600 dark:text-green-400" />
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ reservation.customer_name }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ reservation.customer_email }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-500">
                                            {{ formatDate(reservation.reservation_date) }} at {{ formatTime(reservation.reservation_time) }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ reservation.number_of_guest }} guests</p>
                                    <Link :href="route('reservation.show', reservation.id)" 
                                          class="text-sm text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300">
                                        View details
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Quick Actions</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <Link :href="route('menu-items.create')" 
                              class="flex items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors">
                            <Plus class="w-5 h-5 text-green-600 dark:text-green-400 mr-3" />
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">Add Menu Item</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Create new menu item</p>
                            </div>
                        </Link>
                        
                        <Link :href="route('menu-category.create')" 
                              class="flex items-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors">
                            <FolderOpen class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-3" />
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">Add Category</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Create new menu category</p>
                            </div>
                        </Link>
                        
                        <Link :href="route('reservation.create')" 
                              class="flex items-center p-4 bg-orange-50 dark:bg-orange-900/20 rounded-lg hover:bg-orange-100 dark:hover:bg-orange-900/30 transition-colors">
                            <Calendar class="w-5 h-5 text-orange-600 dark:text-orange-400 mr-3" />
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">New Reservation</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Book a table</p>
                            </div>
                        </Link>
                        
                        <Link :href="route('menu')" 
                              class="flex items-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/30 transition-colors">
                            <Eye class="w-5 h-5 text-purple-600 dark:text-purple-400 mr-3" />
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">View Menu</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">See public menu</p>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Recent Notifications Section -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Recent Notifications</h2>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                {{ recentNotifications.filter(n => !n.is_read).length }} unread
                            </span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Latest system notifications</p>
                </div>
                <div class="p-6">
                    <div v-if="recentNotifications.length === 0" class="text-center py-8">
                        <Bell class="w-12 h-12 text-gray-400 dark:text-gray-500 mx-auto mb-4" />
                        <p class="text-gray-500 dark:text-gray-400">No notifications</p>
                    </div>
                    <div v-else class="space-y-4">
                        <div v-for="notification in recentNotifications.slice(0, 5)" :key="notification.id"
                             class="flex items-start space-x-3 p-4 rounded-lg transition-colors"
                             :class="{
                                 'bg-blue-50 dark:bg-blue-900/20': !notification.is_read,
                                 'bg-gray-50 dark:bg-gray-800': notification.is_read
                             }">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center"
                                     :class="{
                                         'bg-green-100 dark:bg-green-900/30': notification.type === 'reservation',
                                         'bg-blue-100 dark:bg-blue-900/30': notification.type === 'system',
                                         'bg-yellow-100 dark:bg-yellow-900/30': notification.type === 'warning',
                                     }">
                                    <Bell class="w-4 h-4"
                                          :class="{
                                              'text-green-600 dark:text-green-400': notification.type === 'reservation',
                                              'text-blue-600 dark:text-blue-400': notification.type === 'system',
                                              'text-yellow-600 dark:text-yellow-400': notification.type === 'warning',
                                          }" />
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ notification.title }}
                                    </p>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ formatTime(notification.created_at) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    {{ notification.message }}
                                </p>
                                
                                <!-- Reservation Details -->
                                <div v-if="notification.type === 'reservation' && notification.data"
                                     class="mt-2 p-2 bg-gray-100 dark:bg-gray-700 rounded text-xs">
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <span class="font-medium text-gray-700 dark:text-gray-300">Date:</span>
                                            <span class="text-gray-600 dark:text-gray-400 ml-1">
                                                {{ notification.data.reservation_date }}
                                            </span>
                                        </div>
                                        <div>
                                            <span class="font-medium text-gray-700 dark:text-gray-300">Time:</span>
                                            <span class="text-gray-600 dark:text-gray-400 ml-1">
                                                {{ notification.data.reservation_time }}
                                            </span>
                                        </div>
                                        <div>
                                            <span class="font-medium text-gray-700 dark:text-gray-300">Guests:</span>
                                            <span class="text-gray-600 dark:text-gray-400 ml-1">
                                                {{ notification.data.number_of_guest }}
                                            </span>
                                        </div>
                                        <div>
                                            <span class="font-medium text-gray-700 dark:text-gray-300">Phone:</span>
                                            <span class="text-gray-600 dark:text-gray-400 ml-1">
                                                {{ notification.data.customer_phone }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Featured Items Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Featured Items -->
                <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Featured Items</h2>
                            <Star class="w-5 h-5 text-yellow-500" />
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Highlighted menu items</p>
                    </div>
                    <div class="p-6">
                        <div v-if="featuredItems.length === 0" class="text-center py-8">
                            <Star class="w-12 h-12 text-gray-400 dark:text-gray-500 mx-auto mb-4" />
                            <p class="text-gray-500 dark:text-gray-400">No featured items</p>
                        </div>
                        <div v-else class="space-y-3">
                            <div v-for="item in featuredItems" :key="item.id"
                                 class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                                        <Utensils class="w-5 h-5 text-green-600 dark:text-green-400" />
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ item.name }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ item.category?.name }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-green-600 dark:text-green-400">{{ formatPrice(item.price) }}</p>
                                    <Link :href="route('menu-items.edit', item.id)" 
                                          class="text-xs text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                        Edit
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chef Special Items -->
                <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Chef Specials</h2>
                            <ChefHat class="w-5 h-5 text-red-500" />
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Chef's special creations</p>
                    </div>
                    <div class="p-6">
                        <div v-if="chefSpecialItems.length === 0" class="text-center py-8">
                            <ChefHat class="w-12 h-12 text-gray-400 dark:text-gray-500 mx-auto mb-4" />
                            <p class="text-gray-500 dark:text-gray-400">No chef specials</p>
                        </div>
                        <div v-else class="space-y-3">
                            <div v-for="item in chefSpecialItems" :key="item.id"
                                 class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                                        <ChefHat class="w-5 h-5 text-red-600 dark:text-red-400" />
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ item.name }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ item.category?.name }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-red-600 dark:text-red-400">{{ formatPrice(item.price) }}</p>
                                    <Link :href="route('menu-items.edit', item.id)" 
                                          class="text-xs text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                        Edit
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Categories Chart -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Menu Categories Overview</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Items per category</p>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div v-for="category in topCategories" :key="category.id"
                             class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-gradient-to-r from-green-400 to-blue-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-bold">{{ category.name.charAt(0) }}</span>
                                </div>
                                <span class="font-medium text-gray-900 dark:text-white">{{ category.name }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-32 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-green-400 to-blue-500 h-2 rounded-full"
                                         :style="{ width: `${(category.menu_items_count / Math.max(...topCategories.map(c => c.menu_items_count))) * 100}%` }">
                                    </div>
                                </div>
                                <span class="text-sm font-semibold text-gray-600 dark:text-gray-400 w-8 text-right">
                                    {{ category.menu_items_count }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Restaurant Info -->
            <div class="bg-gradient-to-r from-gray-900 to-gray-800 dark:from-gray-800 dark:to-gray-900 rounded-xl p-6 text-white">
                <h2 class="text-xl font-bold mb-4">Restaurant Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3">
                            <MapPin class="w-5 h-5 text-green-400" />
                            <span>{{ restaurantInfo.address }}</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <Phone class="w-5 h-5 text-green-400" />
                            <span>{{ restaurantInfo.phone }}</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <Mail class="w-5 h-5 text-green-400" />
                            <span>{{ restaurantInfo.email }}</span>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3">
                            <Clock class="w-5 h-5 text-green-400" />
                            <span>Open Daily: 12:00 - 22:00</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <TrendingUp class="w-5 h-5 text-green-400" />
                            <span>{{ restaurantInfo.business_name }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
