<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Plus, Trash2 } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: route('admin.dashboard') },
    { title: 'Event Management', href: route('admin.events.index') },
    { title: 'Add Event', href: route('admin.events.create') },
];

type TitleSegment = { text: string; color: string | null };
type ContentBlock = { type: 'paragraph' | 'heading' | 'feature'; text?: string; title?: string; description?: string; icon?: string | null };

const form = useForm({
    title_primary: '',
    title_secondary: '',
    title_suffix: 'at Hardball!',
    title_segments: [] as TitleSegment[],
    description: '',
    image_path: '' as string,
    image: null as File | null,
    features: [] as { title: string; description: string }[],
    content_blocks: [] as ContentBlock[],
    cta_text: 'Reserve Your Spot',
    cta_link: '',
    status: 'draft',
    show_on_homepage: false,
    sort_order: 0,
    starts_at: '' as string,
    ends_at: '' as string,
});

const addFeature = () => {
    form.features = [...form.features, { title: '', description: '' }];
};
const removeFeature = (index: number) => {
    form.features = form.features.filter((_, i) => i !== index);
};

const addTitleSegment = () => {
    form.title_segments = [...form.title_segments, { text: '', color: null }];
};
const removeTitleSegment = (index: number) => {
    form.title_segments = form.title_segments.filter((_, i) => i !== index);
};

const addContentBlock = (type: ContentBlock['type'] = 'paragraph') => {
    const block: ContentBlock = type === 'feature' ? { type: 'feature', title: '', description: '', icon: 'plus' } : { type, text: '' };
    form.content_blocks = [...form.content_blocks, block];
};
const removeContentBlock = (index: number) => {
    form.content_blocks = form.content_blocks.filter((_, i) => i !== index);
};

const onFileChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    form.image = target.files?.[0] ?? null;
};

const submit = () => {
    const options: { onSuccess?: () => void; forceFormData?: boolean } = {
        onSuccess: () => form.reset(),
    };
    if (form.image) {
        options.forceFormData = true;
    }
    form.post(route('admin.events.store'), options);
};
</script>

<template>
    <Head title="Add Event" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-6">
            <div class="bg-gradient-to-r from-green-600 to-yellow-500 rounded-xl p-6 text-white">
                <div class="flex items-center gap-4">
                    <Link
                        :href="route('admin.events.index')"
                        class="inline-flex items-center gap-2 text-green-100 hover:text-white transition-colors"
                    >
                        <ArrowLeft class="w-5 h-5" />
                        Back to Event Management
                    </Link>
                </div>
                <h1 class="text-3xl font-bold mt-4">Add Event</h1>
                <p class="text-green-100 mt-2">Create a new event for the homepage</p>
            </div>

            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <form @submit.prevent="submit" class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="title_primary" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Title (primary / green)</label>
                            <input
                                id="title_primary"
                                v-model="form.title_primary"
                                type="text"
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-800 dark:text-white"
                                placeholder="e.g. Christmas"
                            />
                            <p v-if="form.errors.title_primary" class="mt-1 text-sm text-red-600">{{ form.errors.title_primary }}</p>
                        </div>
                        <div>
                            <label for="title_secondary" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Title (secondary / yellow)</label>
                            <input
                                id="title_secondary"
                                v-model="form.title_secondary"
                                type="text"
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-800 dark:text-white"
                                placeholder="e.g. Dinner"
                            />
                            <p v-if="form.errors.title_secondary" class="mt-1 text-sm text-red-600">{{ form.errors.title_secondary }}</p>
                        </div>
                        <div>
                            <label for="title_suffix" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Title suffix</label>
                            <input
                                id="title_suffix"
                                v-model="form.title_suffix"
                                type="text"
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-800 dark:text-white"
                                placeholder="e.g. at Hardball!"
                            />
                            <p v-if="form.errors.title_suffix" class="mt-1 text-sm text-red-600">{{ form.errors.title_suffix }}</p>
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                        <textarea
                            id="description"
                            v-model="form.description"
                            rows="3"
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-800 dark:text-white"
                            placeholder="Event description shown on homepage"
                        />
                        <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">{{ form.errors.description }}</p>
                    </div>

                    <div class="space-y-3">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Image</label>
                        <div class="flex flex-col gap-2">
                            <input
                                type="file"
                                accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-green-100 file:text-green-700 dark:file:bg-green-900/30 dark:file:text-green-300"
                                @change="onFileChange"
                            />
                            <p class="text-xs text-gray-500 dark:text-gray-400">Or enter path to existing image (e.g. /img/event/photo.png)</p>
                            <input
                                id="image_path"
                                v-model="form.image_path"
                                type="text"
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-800 dark:text-white"
                                placeholder="/img/event/your-image.png"
                            />
                        </div>
                        <p v-if="form.errors.image_path" class="mt-1 text-sm text-red-600">{{ form.errors.image_path }}</p>
                        <p v-if="form.errors.image" class="mt-1 text-sm text-red-600">{{ form.errors.image }}</p>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Feature bullets</label>
                            <button type="button" @click="addFeature" class="text-sm text-green-600 dark:text-green-400 flex items-center gap-1">
                                <Plus class="w-4 h-4" /> Add
                            </button>
                        </div>
                        <div v-for="(feat, index) in form.features" :key="index" class="flex gap-2 items-start mb-2">
                            <input
                                v-model="feat.title"
                                type="text"
                                class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-800 dark:text-white"
                                placeholder="Title"
                            />
                            <input
                                v-model="feat.description"
                                type="text"
                                class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-800 dark:text-white"
                                placeholder="Description"
                            />
                            <button type="button" @click="removeFeature(index)" class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded">
                                <Trash2 class="w-4 h-4" />
                            </button>
                        </div>
                    </div>

                    <!-- Optional: Versatile title & content (overrides simple title/description/features when set) -->
                    <div class="border border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-6 space-y-6 bg-gray-50/50 dark:bg-gray-800/30">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Versatile content (optional)</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Use this for variable amounts and types of content. When set, it overrides the simple title and description/features above.</p>

                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title segments</label>
                                <button type="button" @click="addTitleSegment" class="text-sm text-green-600 dark:text-green-400 flex items-center gap-1">
                                    <Plus class="w-4 h-4" /> Add segment
                                </button>
                            </div>
                            <div v-for="(seg, index) in form.title_segments" :key="index" class="flex gap-2 items-center mb-2">
                                <input
                                    v-model="seg.text"
                                    type="text"
                                    class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-800 dark:text-white"
                                    placeholder="Segment text"
                                />
                                <select
                                    v-model="seg.color"
                                    class="w-32 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-800 dark:text-white"
                                >
                                    <option :value="null">Default</option>
                                    <option value="green">Green</option>
                                    <option value="yellow">Yellow</option>
                                    <option value="white">White</option>
                                </select>
                                <button type="button" @click="removeTitleSegment(index)" class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded">
                                    <Trash2 class="w-4 h-4" />
                                </button>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Content blocks</label>
                                <div class="flex gap-1">
                                    <button type="button" @click="addContentBlock('paragraph')" class="text-xs px-2 py-1 bg-gray-200 dark:bg-gray-700 rounded">Paragraph</button>
                                    <button type="button" @click="addContentBlock('heading')" class="text-xs px-2 py-1 bg-gray-200 dark:bg-gray-700 rounded">Heading</button>
                                    <button type="button" @click="addContentBlock('feature')" class="text-xs px-2 py-1 bg-gray-200 dark:bg-gray-700 rounded">Feature</button>
                                </div>
                            </div>
                            <div v-for="(block, index) in form.content_blocks" :key="index" class="border border-gray-200 dark:border-gray-700 rounded-lg p-3 mb-2 space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ block.type }}</span>
                                    <button type="button" @click="removeContentBlock(index)" class="p-1 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded">
                                        <Trash2 class="w-4 h-4" />
                                    </button>
                                </div>
                                <template v-if="block.type === 'paragraph' || block.type === 'heading'">
                                    <input
                                        v-model="block.text"
                                        type="text"
                                        :placeholder="block.type === 'heading' ? 'Heading text' : 'Paragraph text'"
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-800 dark:text-white"
                                    />
                                </template>
                                <template v-else-if="block.type === 'feature'">
                                    <input
                                        v-model="block.title"
                                        type="text"
                                        placeholder="Feature title"
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-800 dark:text-white mb-2"
                                    />
                                    <input
                                        v-model="block.description"
                                        type="text"
                                        placeholder="Feature description"
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-800 dark:text-white"
                                    />
                                    <select v-model="block.icon" class="mt-2 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-800 dark:text-white">
                                        <option value="plus">Plus icon (yellow)</option>
                                        <option value="flame">Flame icon (green)</option>
                                        <option value="star">Star</option>
                                    </select>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="cta_text" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">CTA button text *</label>
                            <input
                                id="cta_text"
                                v-model="form.cta_text"
                                type="text"
                                required
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-800 dark:text-white"
                                placeholder="Reserve Your Spot"
                            />
                            <p v-if="form.errors.cta_text" class="mt-1 text-sm text-red-600">{{ form.errors.cta_text }}</p>
                        </div>
                        <div>
                            <label for="cta_link" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">CTA link (optional)</label>
                            <input
                                id="cta_link"
                                v-model="form.cta_link"
                                type="text"
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-800 dark:text-white"
                                placeholder="Leave empty for reservation page"
                            />
                            <p v-if="form.errors.cta_link" class="mt-1 text-sm text-red-600">{{ form.errors.cta_link }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="starts_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Display from (schedule)</label>
                            <input
                                id="starts_at"
                                v-model="form.starts_at"
                                type="datetime-local"
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-800 dark:text-white"
                            />
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Leave empty to show immediately when published</p>
                        </div>
                        <div>
                            <label for="ends_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Display until (schedule)</label>
                            <input
                                id="ends_at"
                                v-model="form.ends_at"
                                type="datetime-local"
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-800 dark:text-white"
                            />
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Leave empty to show until you unpublish or change it</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                            <select
                                id="status"
                                v-model="form.status"
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-800 dark:text-white"
                            >
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                            </select>
                        </div>
                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sort order</label>
                            <input
                                id="sort_order"
                                v-model.number="form.sort_order"
                                type="number"
                                min="0"
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-800 dark:text-white"
                            />
                        </div>
                        <div class="flex items-end pb-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input v-model="form.show_on_homepage" type="checkbox" class="rounded border-gray-300 dark:border-gray-600" />
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Show on homepage</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <Link
                            :href="route('admin.events.index')"
                            class="px-6 py-3 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700"
                        >
                            Cancel
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:ring-2 focus:ring-green-500 disabled:opacity-50"
                        >
                            {{ form.processing ? 'Creating...' : 'Create Event' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
