<script setup lang="ts">
import { SidebarGroup, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem, type SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { DropdownMenu, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { ChevronsUpDown, ChevronRight } from 'lucide-vue-next';

// Extend NavItem to include children
interface ExtendedNavItem extends NavItem {
    children?: NavItem[];
}

defineProps<{
    items: ExtendedNavItem[];
}>();

const page = usePage<SharedData>();
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel class="text-gray-700 dark:text-gray-300">Platform</SidebarGroupLabel>
        <SidebarMenu>
            <SidebarMenuItem v-for="item in items" :key="item.title">
                <template v-if="item.children" >
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <SidebarMenuButton size="lg" class="data-[state=open]:bg-sidebar-accent data-[state=open]:text-sidebar-accent-foreground hover:bg-gray-100 dark:hover:bg-gray-800">
                                <component :is="item.icon" class="text-gray-700 dark:text-gray-300" />
                                <span class="text-gray-900 dark:text-white">{{ item.title }}</span>
                                <ChevronsUpDown class="ml-auto size-4 text-gray-500 dark:text-gray-400" />
                            </SidebarMenuButton>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent 
                            class="w-[--radix-dropdown-menu-trigger-width] min-w-56 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700" 
                            align="end" 
                            side="bottom"
                            :side-offset="0"
                        >
                            <SidebarMenuItem v-for="child in item.children" :key="child.title">
                                <SidebarMenuButton as-child class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <Link :href="child.href" class="text-gray-900 dark:text-white">
                                        <ChevronRight class="h-4 w-4 mr-2 text-gray-500 dark:text-gray-400" />
                                        {{ child.title }}
                                    </Link>
                                </SidebarMenuButton>
                            </SidebarMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                </template>
                <template v-else>
                    <SidebarMenuButton as-child :is-active="item.href === page.url" class="hover:bg-gray-100 dark:hover:bg-gray-800">
                        <Link :href="item.href" class="text-gray-900 dark:text-white">
                            <component :is="item.icon" class="text-gray-700 dark:text-gray-300" />
                            <span>{{ item.title }}</span>
                        </Link>
                    </SidebarMenuButton>
                </template>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
