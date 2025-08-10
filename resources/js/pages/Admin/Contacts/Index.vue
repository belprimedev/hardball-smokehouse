<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { 
    Search, 
    Mail, 
    Eye, 
    Clock,
    CheckCircle,
    XCircle,
    Filter
} from 'lucide-vue-next';
import AdminLayout from '@/layouts/app/AdminLayout.vue';

interface Contact {
    id: number;
    name: string;
    email: string;
    phone: string | null;
    subject: string | null;
    message: string;
    status: 'new' | 'read' | 'replied' | 'closed';
    admin_notes: string | null;
    created_at: string;
    read_at: string | null;
    replied_at: string | null;
    message_preview: string;
    formatted_created_at: string;
    status_color: string;
}

interface Props {
    contacts: {
        data: Contact[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
        links: any[];
    };
    stats: {
        total: number;
        new: number;
        read: number;
        replied: number;
        closed: number;
    };
    filters: {
        search?: string;
        status?: string;
    };
}

const props = defineProps<Props>();

const search = ref(props.filters.search || '');
const statusFilter = ref(props.filters.status || 'all');

const getStatusIcon = (status: string) => {
    switch (status) {
        case 'new':
            return Mail;
        case 'read':
            return Eye;
        case 'replied':
            return CheckCircle;
        case 'closed':
            return XCircle;
        default:
            return Clock;
    }
};

const getStatusLabel = (status: string) => {
    switch (status) {
        case 'new':
            return 'New';
        case 'read':
            return 'Read';
        case 'replied':
            return 'Replied';
        case 'closed':
            return 'Closed';
        default:
            return status;
    }
};

const handleSearch = () => {
    router.get(route('admin.contacts.index'), {
        search: search.value,
        status: statusFilter.value
    }, {
        preserveState: true,
        replace: true
    });
};

const clearFilters = () => {
    search.value = '';
    statusFilter.value = 'all';
    router.get(route('admin.contacts.index'), {}, {
        preserveState: true,
        replace: true
    });
};
</script>

<template>
    <Head title="Contact Management" />
    
    <AdminLayout>
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">Contact Management</h1>
                    <p class="text-muted-foreground">
                        Manage contact form submissions from your website
                    </p>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-5">
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Total Contacts</CardTitle>
                        <Mail class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.total }}</div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">New</CardTitle>
                        <Mail class="h-4 w-4 text-blue-500" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold text-blue-600">{{ stats.new }}</div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Read</CardTitle>
                        <Eye class="h-4 w-4 text-yellow-500" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold text-yellow-600">{{ stats.read }}</div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Replied</CardTitle>
                        <CheckCircle class="h-4 w-4 text-green-500" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold text-green-600">{{ stats.replied }}</div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Closed</CardTitle>
                        <XCircle class="h-4 w-4 text-gray-500" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold text-gray-600">{{ stats.closed }}</div>
                    </CardContent>
                </Card>
            </div>

            <!-- Filters -->
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center space-x-2">
                        <Filter class="h-4 w-4" />
                        <span>Filters</span>
                    </CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1">
                            <div class="relative">
                                <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                                <Input
                                    v-model="search"
                                    placeholder="Search contacts..."
                                    class="pl-10"
                                    @keyup.enter="handleSearch"
                                />
                            </div>
                        </div>
                        <div class="sm:w-48">
                            <select
                                v-model="statusFilter"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                @change="handleSearch"
                            >
                                <option value="all">All Status</option>
                                <option value="new">New</option>
                                <option value="read">Read</option>
                                <option value="replied">Replied</option>
                                <option value="closed">Closed</option>
                            </select>
                        </div>
                        <Button @click="handleSearch" variant="outline">
                            Search
                        </Button>
                        <Button @click="clearFilters" variant="outline">
                            Clear
                        </Button>
                    </div>
                </CardContent>
            </Card>

            <!-- Contacts List -->
            <Card>
                <CardHeader>
                    <CardTitle>Contact Submissions</CardTitle>
                    <CardDescription>
                        Showing {{ contacts.data.length }} of {{ contacts.total }} contacts
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="space-y-4">
                        <div 
                            v-for="contact in contacts.data" 
                            :key="contact.id"
                            class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50 transition-colors"
                        >
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <component 
                                        :is="getStatusIcon(contact.status)" 
                                        class="h-5 w-5"
                                        :class="{
                                            'text-blue-500': contact.status === 'new',
                                            'text-yellow-500': contact.status === 'read',
                                            'text-green-500': contact.status === 'replied',
                                            'text-gray-500': contact.status === 'closed'
                                        }"
                                    />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center space-x-2">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ contact.name }}
                                        </p>
                                        <Badge :class="contact.status_color">
                                            {{ getStatusLabel(contact.status) }}
                                        </Badge>
                                    </div>
                                    <p class="text-sm text-gray-500 truncate">
                                        {{ contact.email }}
                                    </p>
                                    <p class="text-sm text-gray-500 truncate">
                                        {{ contact.subject || 'No subject' }}
                                    </p>
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ contact.message_preview }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="text-right">
                                    <p class="text-xs text-gray-500">
                                        {{ contact.formatted_created_at }}
                                    </p>
                                </div>
                                <Button 
                                    @click="router.visit(route('admin.contacts.show', contact.id))"
                                    variant="outline"
                                    size="sm"
                                >
                                    View
                                </Button>
                            </div>
                        </div>
                        
                        <div v-if="contacts.data.length === 0" class="text-center py-8">
                            <Mail class="h-12 w-12 text-gray-400 mx-auto mb-4" />
                            <p class="text-gray-500">No contacts found</p>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div v-if="contacts.last_page > 1" class="mt-6 flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Showing {{ (contacts.current_page - 1) * contacts.per_page + 1 }} to 
                            {{ Math.min(contacts.current_page * contacts.per_page, contacts.total) }} of 
                            {{ contacts.total }} results
                        </div>
                        <div class="flex space-x-2">
                            <Button 
                                v-for="link in contacts.links" 
                                :key="link.label"
                                @click="router.visit(link.url)"
                                :disabled="!link.url || link.active"
                                :variant="link.active ? 'default' : 'outline'"
                                size="sm"
                                v-html="link.label"
                            />
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AdminLayout>
</template> 