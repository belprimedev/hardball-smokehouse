<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { 
    Mail, 
    AlertTriangle, 
    Calendar, 
    RefreshCw,
    Download,
    XCircle,
    Activity
} from 'lucide-vue-next';
import AdminLayout from '@/layouts/app/AdminLayout.vue';

interface Props {
    activeTab?: string;
    failedEmails?: any[];
    recentNotifications?: any[];
    pendingJobs?: any[];
    failedJobs?: any[];
    horizonStats?: any;
    recentErrors?: any[];
    errorLogs?: string[];
    totalReservations?: number;
    todayReservations?: number;
    failedReservations?: number;
    recentReservations?: any[];
    reservationsByDate?: any[];
    systemLogs?: string[];
    telescopeEntries?: any[];
}

const props = withDefaults(defineProps<Props>(), {
    activeTab: 'overview',
});

// Debug: Log the props to console
console.log('SystemHealth props received:', {
    activeTab: props.activeTab,
    pendingJobs: props.pendingJobs?.length || 0,
    failedJobs: props.failedJobs?.length || 0,
    totalReservations: props.totalReservations || 0,
    todayReservations: props.todayReservations || 0,
    failedEmails: props.failedEmails?.length || 0,
    recentErrors: props.recentErrors?.length || 0,
    horizonStats: props.horizonStats,
});

const retryJob = (jobId: number) => {
    router.post(route('admin.system-health.retry-job', jobId));
};

const downloadLogs = () => {
    window.open(route('admin.system-health.download-logs'), '_blank');
};
</script>

<template>
    <Head title="System Health" />
    
    <AdminLayout>
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">System Health</h1>
                    <p class="text-muted-foreground">
                        Monitor system performance and troubleshoot issues
                    </p>
                </div>
                <div class="flex items-center space-x-2">
                    <Button variant="outline" @click="downloadLogs">
                        <Download class="h-4 w-4 mr-2" />
                        Download Logs
                    </Button>
                    <Button @click="router.visit(route('admin.dashboard'))">
                        Back to Dashboard
                    </Button>
                </div>
            </div>

            <!-- Debug Info -->
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded">
                <p><strong>Debug Info:</strong> Data received from server</p>
                <p>Failed Jobs: {{ failedJobs?.length || 0 }}</p>
                <p>Pending Jobs: {{ pendingJobs?.length || 0 }}</p>
                <p>Total Reservations: {{ totalReservations || 0 }}</p>
                <p>Today Reservations: {{ todayReservations || 0 }}</p>
                <p>Failed Emails: {{ failedEmails?.length || 0 }}</p>
                <p>Recent Errors: {{ recentErrors?.length || 0 }}</p>
            </div>

            <!-- System Health Cards -->
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Failed Jobs</CardTitle>
                        <XCircle class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold text-red-600">{{ failedJobs?.length || 0 }}</div>
                        <p class="text-xs text-muted-foreground">
                            {{ pendingJobs?.length || 0 }} pending jobs
                        </p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Failed Emails</CardTitle>
                        <Mail class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold text-red-600">{{ failedEmails?.length || 0 }}</div>
                        <p class="text-xs text-muted-foreground">
                            {{ recentNotifications?.length || 0 }} recent notifications
                        </p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Recent Errors</CardTitle>
                        <AlertTriangle class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold text-red-600">{{ recentErrors?.length || 0 }}</div>
                        <p class="text-xs text-muted-foreground">
                            {{ errorLogs?.length || 0 }} error log entries
                        </p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Queue Workers</CardTitle>
                        <Activity class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ horizonStats?.processes || 0 }}</div>
                        <p class="text-xs text-muted-foreground">
                            {{ horizonStats?.jobs_per_minute || 0 }} jobs/min
                        </p>
                    </CardContent>
                </Card>
            </div>

            <!-- Recent Data Lists -->
            <div class="grid gap-4 md:grid-cols-3">
                <Card>
                    <CardHeader>
                        <CardTitle>Recent Failed Jobs</CardTitle>
                        <CardDescription>Jobs that failed to process</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4 max-h-96 overflow-y-auto">
                            <div 
                                v-for="job in failedJobs" 
                                :key="job.id"
                                class="flex items-center justify-between p-3 border rounded-lg"
                            >
                                <div class="flex items-center space-x-3">
                                    <XCircle class="h-4 w-4 text-red-500" />
                                    <div>
                                        <p class="text-sm font-medium">{{ job.queue }}</p>
                                        <p class="text-xs text-muted-foreground">{{ job.exception }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <Button size="sm" @click="retryJob(job.id)">
                                        <RefreshCw class="h-3 w-3 mr-1" />
                                        Retry
                                    </Button>
                                    <div class="text-xs text-muted-foreground">
                                        {{ new Date(job.failed_at).toLocaleDateString() }}
                                    </div>
                                </div>
                            </div>
                            
                            <div v-if="!failedJobs?.length" class="text-center py-4 text-muted-foreground">
                                No failed jobs
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Failed Emails</CardTitle>
                        <CardDescription>Emails that failed to send</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4 max-h-96 overflow-y-auto">
                            <div 
                                v-for="email in failedEmails" 
                                :key="email.id"
                                class="flex items-center justify-between p-3 border rounded-lg"
                            >
                                <div class="flex items-center space-x-3">
                                    <Mail class="h-4 w-4 text-red-500" />
                                    <div>
                                        <p class="text-sm font-medium">Failed Email</p>
                                        <p class="text-xs text-muted-foreground">{{ email.queue }}</p>
                                        <p class="text-xs text-muted-foreground">{{ email.exception }}</p>
                                    </div>
                                </div>
                                <div class="text-xs text-muted-foreground">
                                    {{ new Date(email.failed_at).toLocaleDateString() }}
                                </div>
                            </div>
                            
                            <div v-if="!failedEmails?.length" class="text-center py-4 text-muted-foreground">
                                No failed emails
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Recent Reservations</CardTitle>
                        <CardDescription>Latest reservation attempts</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4 max-h-96 overflow-y-auto">
                            <div 
                                v-for="reservation in recentReservations" 
                                :key="reservation.id"
                                class="flex items-center justify-between p-3 border rounded-lg"
                            >
                                <div class="flex items-center space-x-3">
                                    <Calendar class="h-4 w-4" />
                                    <div>
                                        <p class="text-sm font-medium">{{ reservation.customer_name }}</p>
                                        <p class="text-xs text-muted-foreground">
                                            {{ reservation.reservation_date }} at {{ reservation.reservation_time }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <Badge class="bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        Active
                                    </Badge>
                                    <div class="text-xs text-muted-foreground">
                                        {{ new Date(reservation.created_at).toLocaleDateString() }}
                                    </div>
                                </div>
                            </div>
                            
                            <div v-if="!recentReservations?.length" class="text-center py-4 text-muted-foreground">
                                No recent reservations
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AdminLayout>
</template> 