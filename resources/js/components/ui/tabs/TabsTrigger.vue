<script setup lang="ts">
import { inject, computed } from 'vue';

interface Props {
    value: string;
    class?: string;
}

const props = defineProps<Props>();

const tabsContext = inject('tabs', {
    value: computed(() => ''),
    defaultValue: computed(() => ''),
    onValueChange: () => {},
});

const isActive = computed(() => {
    return tabsContext.value.value === props.value || 
           (tabsContext.value.value === '' && tabsContext.defaultValue.value === props.value);
});

const handleClick = () => {
    tabsContext.onValueChange(props.value);
};
</script>

<template>
    <button
        :class="[
            'inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50',
            isActive
                ? 'bg-background text-foreground shadow-sm'
                : 'hover:bg-background hover:text-foreground',
            props.class
        ]"
        :data-state="isActive ? 'active' : 'inactive'"
        role="tab"
        @click="handleClick"
    >
        <slot />
    </button>
</template> 