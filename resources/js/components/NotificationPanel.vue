<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import { Bell, X, Clock, Calendar, User, Phone, Mail } from 'lucide-vue-next';
import { router } from '@inertiajs/vue3';

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

const notifications = ref<Notification[]>([]);
const unreadCount = ref(0);
const isOpen = ref(false);
const isLoading = ref(false);

// Pusher for real-time notifications
let pusher: any = null;
let channel: any = null;

const fetchNotifications = async () => {
    try {
        isLoading.value = true;
        const response = await fetch('/api/notifications/unread');
        const data = await response.json();
        notifications.value = data.notifications;
        unreadCount.value = data.unread_count;
    } catch (error) {
        console.error('Error fetching notifications:', error);
    } finally {
        isLoading.value = false;
    }
};

const markAsRead = async (notificationId: number) => {
    try {
        const response = await fetch('/api/notifications/mark-as-read', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            },
            body: JSON.stringify({ notification_id: notificationId }),
        });
        
        if (response.ok) {
            const data = await response.json();
            unreadCount.value = data.unread_count;
            
            // Mark notification as read in local state
            const notification = notifications.value.find(n => n.id === notificationId);
            if (notification) {
                notification.is_read = true;
                notification.read_at = new Date().toISOString();
            }
        }
    } catch (error) {
        console.error('Error marking notification as read:', error);
    }
};

const markAllAsRead = async () => {
    try {
        const response = await fetch('/api/notifications/mark-all-as-read', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            },
        });
        
        if (response.ok) {
            unreadCount.value = 0;
            notifications.value.forEach(notification => {
                notification.is_read = true;
                notification.read_at = new Date().toISOString();
            });
        }
    } catch (error) {
        console.error('Error marking all notifications as read:', error);
    }
};

const formatTime = (dateString: string) => {
    const date = new Date(dateString);
    const now = new Date();
    const diffInMinutes = Math.floor((now.getTime() - date.getTime()) / (1000 * 60));
    
    if (diffInMinutes < 1) return 'Just now';
    if (diffInMinutes < 60) return `${diffInMinutes}m ago`;
    if (diffInMinutes < 1440) return `${Math.floor(diffInMinutes / 60)}h ago`;
    return date.toLocaleDateString();
};

const handleNotificationClick = (notification: Notification) => {
    // Mark as read first
    markAsRead(notification.id);
    
    // Navigate based on notification type
    if (notification.type === 'reservation' && notification.data?.reservation_id) {
        // Navigate to reservation details
        router.visit(`/reservation/${notification.data.reservation_id}`);
    } else if (notification.type === 'reservation') {
        // Navigate to reservations list
        router.visit('/reservation');
    } else {
        // Default navigation for other types
        router.visit('/dashboard');
    }
    
    // Close the dropdown
    isOpen.value = false;
};

const getNotificationIcon = (type: string) => {
    switch (type) {
        case 'reservation':
            return Calendar;
        case 'system':
            return Bell;
        case 'user':
            return User;
        default:
            return Bell;
    }
};

const getNotificationColor = (type: string) => {
    switch (type) {
        case 'reservation':
            return 'text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-900/30';
        case 'system':
            return 'text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-900/30';
        case 'warning':
            return 'text-yellow-600 dark:text-yellow-400 bg-yellow-100 dark:bg-yellow-900/30';
        default:
            return 'text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-900/30';
    }
};

const initializePusher = () => {
    // Initialize Pusher for real-time notifications
    // You'll need to configure Pusher in your .env file
    if (typeof window !== 'undefined' && (window as any).Pusher) {
        const Pusher = (window as any).Pusher;
        pusher = new Pusher(import.meta.env.VITE_PUSHER_APP_KEY || 'your-app-key', {
            cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER || 'mt1',
        });
        
        channel = pusher.subscribe('notifications');
        channel.bind('App\\Events\\NewReservationCreated', (data: any) => {
            // Add new notification to the list
            const newNotification: Notification = {
                id: Date.now(), // Temporary ID
                type: data.type,
                title: data.title,
                message: data.message,
                data: data.data,
                is_read: false,
                created_at: new Date().toISOString(),
            };
            
            notifications.value.unshift(newNotification);
            unreadCount.value++;
            
            // Show browser notification if permission is granted
            if (Notification.permission === 'granted') {
                new Notification(data.title, {
                    body: data.message,
                    icon: '/favicon.ico',
                });
            }
        });
    }
};

const requestNotificationPermission = () => {
    if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission();
    }
};

onMounted(() => {
    fetchNotifications();
    initializePusher();
    requestNotificationPermission();
});

onUnmounted(() => {
    if (channel) {
        channel.unsubscribe();
    }
    if (pusher) {
        pusher.disconnect();
    }
});
</script>

<template>
    <div class="relative">
        <!-- Notification Bell with Badge -->
        <button
            @click="isOpen = !isOpen"
            class="relative p-2 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100 transition-colors rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800"
        >
            <Bell class="w-6 h-6" />
            <!-- Red Badge for Unread Count -->
            <span
                v-if="unreadCount > 0"
                class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold animate-pulse"
            >
                {{ unreadCount > 9 ? '9+' : unreadCount }}
            </span>
        </button>

        <!-- Notification Dropdown -->
        <div
            v-if="isOpen"
            class="absolute right-0 mt-2 w-96 bg-white dark:bg-gray-900 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 z-50"
        >
            <!-- Header -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center space-x-2">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Notifications</h3>
                    <span v-if="unreadCount > 0" class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                        {{ unreadCount }} new
                    </span>
                </div>
                <div class="flex items-center space-x-2">
                    <button
                        v-if="unreadCount > 0"
                        @click="markAllAsRead"
                        class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium"
                    >
                        Mark all read
                    </button>
                    <button
                        @click="isOpen = false"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-1 rounded"
                    >
                        <X class="w-4 h-4" />
                    </button>
                </div>
            </div>

            <!-- Notifications List -->
            <div class="max-h-96 overflow-y-auto">
                <div v-if="isLoading" class="p-4 text-center text-gray-500 dark:text-gray-400">
                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600 mx-auto"></div>
                    <p class="mt-2">Loading notifications...</p>
                </div>
                
                <div v-else-if="notifications.length === 0" class="p-6 text-center text-gray-500 dark:text-gray-400">
                    <Bell class="w-12 h-12 mx-auto mb-3 opacity-50" />
                    <p class="font-medium">No notifications</p>
                    <p class="text-sm">You're all caught up!</p>
                </div>
                
                <div v-else class="divide-y divide-gray-200 dark:divide-gray-700">
                    <div
                        v-for="notification in notifications"
                        :key="notification.id"
                        @click="handleNotificationClick(notification)"
                        class="p-4 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors cursor-pointer"
                        :class="{ 'bg-blue-50 dark:bg-blue-900/20': !notification.is_read }"
                    >
                        <div class="flex items-start space-x-3">
                            <!-- Notification Icon -->
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center"
                                     :class="getNotificationColor(notification.type).split(' ')[1]">
                                    <component :is="getNotificationIcon(notification.type)" class="w-5 h-5" />
                                </div>
                            </div>
                            
                            <!-- Notification Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ notification.title }}
                                    </p>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ formatTime(notification.created_at) }}
                                        </span>
                                        <span v-if="!notification.is_read" class="w-2 h-2 bg-red-500 rounded-full"></span>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    {{ notification.message }}
                                </p>
                                
                                <!-- Reservation Details -->
                                <div v-if="notification.type === 'reservation' && notification.data"
                                     class="mt-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                    <div class="grid grid-cols-2 gap-3 text-xs">
                                        <div class="flex items-center space-x-2">
                                            <User class="w-3 h-3 text-gray-500" />
                                            <span class="text-gray-700 dark:text-gray-300 font-medium">Customer:</span>
                                            <span class="text-gray-600 dark:text-gray-400">{{ notification.data.customer_name }}</span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <Calendar class="w-3 h-3 text-gray-500" />
                                            <span class="text-gray-700 dark:text-gray-300 font-medium">Date:</span>
                                            <span class="text-gray-600 dark:text-gray-400">{{ notification.data.reservation_date }}</span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <Clock class="w-3 h-3 text-gray-500" />
                                            <span class="text-gray-700 dark:text-gray-300 font-medium">Time:</span>
                                            <span class="text-gray-600 dark:text-gray-400">{{ notification.data.reservation_time }}</span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-gray-700 dark:text-gray-300 font-medium">Guests:</span>
                                            <span class="text-gray-600 dark:text-gray-400">{{ notification.data.number_of_guest }}</span>
                                        </div>
                                        <div v-if="notification.data.customer_phone" class="flex items-center space-x-2 col-span-2">
                                            <Phone class="w-3 h-3 text-gray-500" />
                                            <span class="text-gray-700 dark:text-gray-300 font-medium">Phone:</span>
                                            <span class="text-gray-600 dark:text-gray-400">{{ notification.data.customer_phone }}</span>
                                        </div>
                                        <div v-if="notification.data.customer_email" class="flex items-center space-x-2 col-span-2">
                                            <Mail class="w-3 h-3 text-gray-500" />
                                            <span class="text-gray-700 dark:text-gray-300 font-medium">Email:</span>
                                            <span class="text-gray-600 dark:text-gray-400">{{ notification.data.customer_email }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                <button
                    @click="fetchNotifications"
                    class="w-full text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium"
                >
                    Refresh notifications
                </button>
            </div>
        </div>
    </div>
</template> 