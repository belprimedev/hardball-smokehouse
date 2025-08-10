<script setup lang="ts">
interface Props {
    variant?: 'default' | 'destructive';
    class?: string;
}

const props = withDefaults(defineProps<Props>(), {
    variant: 'default',
});
</script>

<template>
    <div
        :class="[
            'relative w-full rounded-lg border p-4 [&>svg~*]:pl-7 [&>svg+div]:translate-y-[-3px] [&>svg]:absolute [&>svg]:left-4 [&>svg]:top-4 [&>svg]:text-foreground',
            props.variant === 'destructive' && 'border-destructive/50 text-destructive dark:border-destructive [&>svg]:text-destructive',
            props.class
        ]"
        role="alert"
    >
        <slot />
    </div>
</template> 