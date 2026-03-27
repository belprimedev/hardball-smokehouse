<script setup lang="ts">
import { cn } from '@/lib/utils';
import { ToastProvider, type ToastProviderProps } from 'radix-vue';

const props = defineProps<ToastProviderProps>();
</script>

<template>
    <ToastProvider v-bind="props">
        <slot />
    </ToastProvider>
</template>
