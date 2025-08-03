<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Alert, AlertDescription } from '@/components/ui/alert';
import { 
    Calendar, 
    Users, 
    AlertTriangle, 
    Clock, 
    HardDrive, 
    Activity,
    XCircle
} from 'lucide-vue-next';
import AdminLayout from '@/layouts/app/AdminLayout.vue';

interface Props {
    stats: {
        todayReservations: number;
        failedJobs: number;
        pendingJobs: number;
        recentErrors: any[];
        systemInfo: {
            disk_usage: any;
            queue_workers: any;
            last_cron_run: any;
        };
        userStats: {
            total_users: number;
            active_users: number;
            suspended_users: number;
            recent_logins: number;
        };
        reservationStats: {
            total_reservations: number;
            today_reservations: number;
            this_week_reservations: number;
            this_month_reservations: number;
        };
    };
    recentNotifications: any[];
}

const props = defineProps<Props>();

const getStatusColor = (status: string) => {
    switch (status) {
        case 'success':
            return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
        case 'warning':
            return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200';
        case 'error':
            return 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
        default:
            return 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
    }
};

const getSystemHealthStatus = () => {
    const hasErrors = props.stats.recentErrors.length > 0;
    const hasFailedJobs = props.stats.failedJobs > 0;
    const hasPendingJobs = props.stats.pendingJobs > 10;
    
    if (hasErrors || hasFailedJobs) {
        return { status: 'error', message: 'System has issues that need attention' };
    } else if (hasPendingJobs) {
        return { status: 'warning', message: 'High job queue, monitor performance' };
    } else {
        return { status: 'success', message: 'System is running smoothly' };
    }
};

const systemHealth = getSystemHealthStatus();
</script>

<template>
    <Head title="Admin Dashboard" />
    
    <AdminLayout>
        <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold tracking-tight">Admin Dashboard</h1>
                <p class="text-muted-foreground">
                    System monitoring and administration overview
                </p>
            </div>
            <div class="flex items-center space-x-2">
                <Button variant="outline" @click="router.visit(route('admin.system-health'))">
                    System Health
                </Button>
                <Button @click="router.visit(route('user-management.index'))">
                    Manage Users
                </Button>
            </div>
        </div>

        <!-- System Health Alert -->
        <Alert :class="getStatusColor(systemHealth.status)">
            <AlertTriangle class="h-4 w-4" />
            <AlertDescription>
                {{ systemHealth.message }}
            </AlertDescription>
        </Alert>

        <!-- Quick Stats -->
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            <Card>
                <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                    <CardTitle class="text-sm font-medium">Today's Reservations</CardTitle>
                    <Calendar class="h-4 w-4 text-muted-foreground" />
                </CardHeader>
                <CardContent>
                    <div class="text-2xl font-bold">{{ stats.todayReservations }}</div>
                    <p class="text-xs text-muted-foreground">
                        {{ stats.reservationStats.today_reservations }} total today
                    </p>
                </CardContent>
            </Card>

            <Card>
                <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                    <CardTitle class="text-sm font-medium">Failed Jobs</CardTitle>
                    <XCircle class="h-4 w-4 text-muted-foreground" />
                </CardHeader>
                <CardContent>
                    <div class="text-2xl font-bold text-red-600">{{ stats.failedJobs }}</div>
                    <p class="text-xs text-muted-foreground">
                        {{ stats.pendingJobs }} pending jobs
                    </p>
                </CardContent>
            </Card>

            <Card>
                <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                    <CardTitle class="text-sm font-medium">Active Users</CardTitle>
                    <Users class="h-4 w-4 text-muted-foreground" />
                </CardHeader>
                <CardContent>
                    <div class="text-2xl font-bold">{{ stats.userStats.active_users }}</div>
                    <p class="text-xs text-muted-foreground">
                        {{ stats.userStats.total_users }} total users
                    </p>
                </CardContent>
            </Card>

            <Card>
                <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                    <CardTitle class="text-sm font-medium">System Status</CardTitle>
                    <Activity class="h-4 w-4 text-muted-foreground" />
                </CardHeader>
                <CardContent>
                    <div class="text-2xl font-bold">
                        <Badge :class="getStatusColor(systemHealth.status)">
                            {{ systemHealth.status }}
                        </Badge>
                    </div>
                    <p class="text-xs text-muted-foreground">
                        {{ stats.recentErrors.length }} recent errors
                    </p>
                </CardContent>
            </Card>
        </div>

        <!-- System Information -->
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <!-- Disk Usage -->
            <Card v-if="stats.systemInfo.disk_usage">
                <CardHeader>
                    <CardTitle class="flex items-center space-x-2">
                        <HardDrive class="h-4 w-4" />
                        <span>Disk Usage</span>
                    </CardTitle>
                    <CardDescription>Storage space utilization</CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span>Used:</span>
                            <span>{{ stats.systemInfo.disk_usage.used }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span>Free:</span>
                            <span>{{ stats.systemInfo.disk_usage.free }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div 
                                class="bg-blue-600 h-2 rounded-full" 
                                :style="{ width: stats.systemInfo.disk_usage.percentage + '%' }"
                            ></div>
                        </div>
                        <p class="text-xs text-muted-foreground">
                            {{ stats.systemInfo.disk_usage.percentage }}% used
                        </p>
                    </div>
                </CardContent>
            </Card>

            <!-- Queue Workers -->
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center space-x-2">
                        <Activity class="h-4 w-4" />
                        <span>Queue Workers</span>
                    </CardTitle>
                    <CardDescription>Job processing status</CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span>Running:</span>
                            <span>{{ stats.systemInfo.queue_workers.running }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span>Jobs/min:</span>
                            <span>{{ stats.systemInfo.queue_workers.jobs_per_minute }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span>Throughput:</span>
                            <span>{{ stats.systemInfo.queue_workers.throughput }}</span>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Recent Activity -->
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center space-x-2">
                        <Clock class="h-4 w-4" />
                        <span>Recent Activity</span>
                    </CardTitle>
                    <CardDescription>System activity overview</CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span>Last Cron:</span>
                            <span>{{ stats.systemInfo.last_cron_run.last_run }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span>Status:</span>
                            <Badge :class="getStatusColor(stats.systemInfo.last_cron_run.status)">
                                {{ stats.systemInfo.last_cron_run.status }}
                            </Badge>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>

        <!-- Recent Notifications -->
        <Card>
            <CardHeader>
                <CardTitle>Recent Notifications</CardTitle>
                <CardDescription>Latest system notifications</CardDescription>
            </CardHeader>
            <CardContent>
                <div class="space-y-4">
                    <div 
                        v-for="notification in recentNotifications.slice(0, 5)" 
                        :key="notification.id"
                        class="flex items-center justify-between p-3 border rounded-lg"
                    >
                        <div class="flex items-center space-x-3">
                            <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                            <div>
                                <p class="text-sm font-medium">{{ notification.data?.title || 'System Notification' }}</p>
                                <p class="text-xs text-muted-foreground">{{ notification.data?.message || 'No message' }}</p>
                            </div>
                        </div>
                        <div class="text-xs text-muted-foreground">
                            {{ new Date(notification.created_at).toLocaleDateString() }}
                        </div>
                    </div>
                    
                    <div v-if="recentNotifications.length === 0" class="text-center py-4 text-muted-foreground">
                        No recent notifications
                    </div>
                </div>
            </CardContent>
        </Card>
    </div>
    </AdminLayout>
</template> 