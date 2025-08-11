<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { 
    Search, 
    Mail, 
    Eye, 
    Clock,
    CheckCircle,
    XCircle
} from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

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

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Admin',
        href: route('admin.dashboard'),
    },
    {
        title: 'Contact Management',
        href: route('admin.contacts.index'),
    },
];

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
    
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-6">
            <!-- Header -->
            <div class="bg-gradient-to-r from-yellow-500 to-yellow-400 rounded-xl p-6 text-white">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">Contact Management</h1>
                        <p class="text-green-100">Manage contact form submissions from your website</p>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ stats.total }}</p>
                        </div>
                        <div class="p-3 bg-gray-100 dark:bg-gray-700 rounded-lg">
                            <Mail class="w-6 h-6 text-gray-600 dark:text-gray-400" />
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">New</p>
                            <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ stats.new }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                            <Mail class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Read</p>
                            <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ stats.read }}</p>
                        </div>
                        <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                            <Eye class="w-6 h-6 text-yellow-600 dark:text-yellow-400" />
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Replied</p>
                            <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ stats.replied }}</p>
                        </div>
                        <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                            <CheckCircle class="w-6 h-6 text-green-600 dark:text-green-400" />
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Closed</p>
                            <p class="text-3xl font-bold text-gray-600 dark:text-gray-400">{{ stats.closed }}</p>
                        </div>
                        <div class="p-3 bg-gray-100 dark:bg-gray-700 rounded-lg">
                            <XCircle class="w-6 h-6 text-gray-600 dark:text-gray-400" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Table -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Contact Submissions</h2>
                </div>

                <!-- Filters -->
                <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
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
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white"
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
                </div>

                <!-- Contacts List -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                        <thead class="bg-gray-50 dark:bg-neutral-900">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-start">
                                    <div class="flex items-center gap-x-2">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                            Contact
                                        </span>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-start">
                                    <div class="flex items-center gap-x-2">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                            Subject
                                        </span>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-start">
                                    <div class="flex items-center gap-x-2">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                            Status
                                        </span>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-start">
                                    <div class="flex items-center gap-x-2">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                            Date
                                        </span>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-end"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                            <tr v-for="contact in contacts.data" :key="contact.id" class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="size-px whitespace-nowrap">
                                    <div class="px-6 py-3">
                                        <div class="flex items-center space-x-3">
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
                                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                                    {{ contact.name }}
                                                </p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                                    {{ contact.email }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="size-px whitespace-nowrap">
                                    <div class="px-6 py-3">
                                        <p class="text-sm text-gray-900 dark:text-white truncate">
                                            {{ contact.subject || 'No subject' }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 truncate max-w-xs">
                                            {{ contact.message_preview }}
                                        </p>
                                    </div>
                                </td>
                                <td class="size-px whitespace-nowrap">
                                    <div class="px-6 py-3">
                                        <Badge :class="contact.status_color">
                                            {{ getStatusLabel(contact.status) }}
                                        </Badge>
                                    </div>
                                </td>
                                <td class="size-px whitespace-nowrap">
                                    <div class="px-6 py-3">
                                        <span class="text-sm text-gray-600 dark:text-neutral-400">
                                            {{ contact.formatted_created_at }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-5 flex justify-end h-full mx-auto items-center">
                                    <Button 
                                        @click="router.visit(route('admin.contacts.show', contact.id))"
                                        variant="outline"
                                        size="sm"
                                        class="bg-blue-500 text-white hover:bg-blue-600"
                                    >
                                        View
                                    </Button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <div v-if="contacts.data.length === 0" class="text-center py-8">
                        <Mail class="h-12 w-12 text-gray-400 mx-auto mb-4" />
                        <p class="text-gray-500 dark:text-gray-400">No contacts found</p>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="contacts.last_page > 1" class="px-6 py-4 border-t border-gray-200 dark:border-neutral-700">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700 dark:text-gray-300">
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
                            >
                                {{ link.label }}
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template> 