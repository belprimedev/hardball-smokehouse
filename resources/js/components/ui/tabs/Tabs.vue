<script setup lang="ts">
import { provide, toRef } from 'vue';

interface Props {
    defaultValue?: string;
    value?: string;
    class?: string;
}

const props = withDefaults(defineProps<Props>(), {
    defaultValue: '',
});

const emit = defineEmits<{
    'update:value': [value: string];
}>();

const value = toRef(props, 'value');
const defaultValue = toRef(props, 'defaultValue');

provide('tabs', {
    value,
    defaultValue,
    onValueChange: (newValue: string) => {
        emit('update:value', newValue);
    },
});
</script>

<template>
    <div :class="['w-full', props.class]">
        <slot />
    </div>
</template> 