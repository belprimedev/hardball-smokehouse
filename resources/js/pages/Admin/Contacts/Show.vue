<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Textarea } from '@/components/ui/input';
import { 
    ArrowLeft, 
    Mail, 
    Phone, 
    User,
    MessageSquare,
    CheckCircle,
    Clock
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
    contact: Contact;
}

const props = defineProps<Props>();

const form = useForm({
    status: props.contact.status,
    admin_notes: props.contact.admin_notes || ''
});

const isUpdating = ref(false);

const getStatusIcon = (status: string) => {
    switch (status) {
        case 'new':
            return Mail;
        case 'read':
            return Clock;
        case 'replied':
            return CheckCircle;
        case 'closed':
            return CheckCircle;
        default:
            return Mail;
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

const markAsReplied = async () => {
    try {
        const response = await fetch(route('admin.contacts.mark-replied', props.contact.id), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            }
        });
        
        if (response.ok) {
            router.reload();
        }
    } catch (error) {
        console.error('Error marking as replied:', error);
    }
};

const updateContact = () => {
    isUpdating.value = true;
    form.put(route('admin.contacts.update', props.contact.id), {
        onSuccess: () => {
            isUpdating.value = false;
        },
        onError: () => {
            isUpdating.value = false;
        }
    });
};
</script>

<template>
    <Head title="Contact Details" />
    
    <AdminLayout>
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <Button 
                        @click="router.visit(route('admin.contacts.index'))"
                        variant="outline"
                        size="sm"
                    >
                        <ArrowLeft class="h-4 w-4 mr-2" />
                        Back to Contacts
                    </Button>
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight">Contact Details</h1>
                        <p class="text-muted-foreground">
                            View and manage contact form submission
                        </p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <Badge :class="contact.status_color">
                        <component :is="getStatusIcon(contact.status)" class="h-3 w-3 mr-1" />
                        {{ getStatusLabel(contact.status) }}
                    </Badge>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <!-- Contact Information -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Contact Details -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center space-x-2">
                                <User class="h-5 w-5" />
                                <span>Contact Information</span>
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <label class="text-sm font-medium text-gray-700">Name</label>
                                    <p class="text-sm text-gray-900 mt-1">{{ contact.name }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-700">Email</label>
                                    <p class="text-sm text-gray-900 mt-1">
                                        <a :href="`mailto:${contact.email}`" class="text-blue-600 hover:underline">
                                            {{ contact.email }}
                                        </a>
                                    </p>
                                </div>
                                <div v-if="contact.phone">
                                    <label class="text-sm font-medium text-gray-700">Phone</label>
                                    <p class="text-sm text-gray-900 mt-1">
                                        <a :href="`tel:${contact.phone}`" class="text-blue-600 hover:underline">
                                            {{ contact.phone }}
                                        </a>
                                    </p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-700">Subject</label>
                                    <p class="text-sm text-gray-900 mt-1">{{ contact.subject || 'No subject' }}</p>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="text-sm font-medium text-gray-700">Submitted</label>
                                    <p class="text-sm text-gray-900 mt-1">{{ contact.formatted_created_at }}</p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Message -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center space-x-2">
                                <MessageSquare class="h-5 w-5" />
                                <span>Message</span>
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ contact.message }}</p>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Admin Notes -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Admin Notes</CardTitle>
                            <CardDescription>
                                Add internal notes about this contact
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <form @submit.prevent="updateContact">
                                <div class="space-y-4">
                                    <div>
                                        <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                            Notes
                                        </label>
                                        <Textarea
                                            id="admin_notes"
                                            v-model="form.admin_notes"
                                            rows="4"
                                            placeholder="Add internal notes about this contact..."
                                            class="w-full"
                                        />
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <Button 
                                            type="submit" 
                                            :disabled="isUpdating"
                                            size="sm"
                                        >
                                            {{ isUpdating ? 'Updating...' : 'Update Notes' }}
                                        </Button>
                                    </div>
                                </div>
                            </form>
                        </CardContent>
                    </Card>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Status Management -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Status Management</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-4">
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                        Status
                                    </label>
                                    <select
                                        id="status"
                                        v-model="form.status"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    >
                                        <option value="new">New</option>
                                        <option value="read">Read</option>
                                        <option value="replied">Replied</option>
                                        <option value="closed">Closed</option>
                                    </select>
                                </div>
                                <div class="flex space-x-2">
                                    <Button 
                                        @click="updateContact"
                                        :disabled="isUpdating"
                                        size="sm"
                                        class="flex-1"
                                    >
                                        {{ isUpdating ? 'Updating...' : 'Update Status' }}
                                    </Button>
                                    <Button 
                                        @click="markAsReplied"
                                        variant="outline"
                                        size="sm"
                                        :disabled="contact.status === 'replied'"
                                    >
                                        Mark Replied
                                    </Button>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Timeline -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Timeline</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-4">
                                <div class="flex items-start space-x-3">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                                    <div>
                                        <p class="text-sm font-medium">Contact submitted</p>
                                        <p class="text-xs text-gray-500">{{ contact.formatted_created_at }}</p>
                                    </div>
                                </div>
                                
                                <div v-if="contact.read_at" class="flex items-start space-x-3">
                                    <div class="w-2 h-2 bg-yellow-500 rounded-full mt-2"></div>
                                    <div>
                                        <p class="text-sm font-medium">Marked as read</p>
                                        <p class="text-xs text-gray-500">
                                            {{ new Date(contact.read_at).toLocaleString() }}
                                        </p>
                                    </div>
                                </div>
                                
                                <div v-if="contact.replied_at" class="flex items-start space-x-3">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                    <div>
                                        <p class="text-sm font-medium">Marked as replied</p>
                                        <p class="text-xs text-gray-500">
                                            {{ new Date(contact.replied_at).toLocaleString() }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Quick Actions -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Quick Actions</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-2">
                                <Button 
                                    :href="`mailto:${contact.email}?subject=Re: ${contact.subject || 'Contact Form Submission'}`"
                                    variant="outline"
                                    size="sm"
                                    class="w-full justify-start"
                                >
                                    <Mail class="h-4 w-4 mr-2" />
                                    Reply via Email
                                </Button>
                                <Button 
                                    v-if="contact.phone"
                                    :href="`tel:${contact.phone}`"
                                    variant="outline"
                                    size="sm"
                                    class="w-full justify-start"
                                >
                                    <Phone class="h-4 w-4 mr-2" />
                                    Call Contact
                                </Button>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </AdminLayout>
</template> 