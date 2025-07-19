<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { Folder, LayoutGrid, Moon, Sun, Calendar } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import { useAppearance } from '@/composables/useAppearance';

// Extend NavItem to include children
interface ExtendedNavItem extends NavItem {
    children?: NavItem[];
}

// Use the same appearance composable
const { appearance, updateAppearance } = useAppearance();

// Toggle between light and dark themes
const toggleTheme = () => {
    if (appearance.value === 'dark') {
        updateAppearance('light');
    } else {
        updateAppearance('dark');
    }
};

const mainNavItems: ExtendedNavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Reservation',
        href: route('reservation.index'),
        icon: Calendar,
    },
    {
        title: 'Menu',
        href: '#',
        icon: Folder,
        children: [
            {
                title: 'Menu',
                href: route('menu-items.index'),
            },
            {
                title: 'Category',
                href: route('menu-category.index'),
            },
        ],
    },
];

const footerNavItems: NavItem[] = [
    // {
    //     title: 'Github Repo',
    //     href: 'https://github.com/laravel/vue-starter-kit',
    //     icon: Folder,
    // },
    // {
    //     title: 'Documentation',
    //     href: 'https://laravel.com/docs/starter-kits',
    //     icon: BookOpen,
    // },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset" class="bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-700">
        <SidebarHeader class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent class="bg-white dark:bg-gray-900">
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter class="bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
            <NavFooter :items="footerNavItems" />
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton @click="toggleTheme" class="hover:bg-gray-100 dark:hover:bg-gray-800">
                        <component :is="appearance === 'dark' ? Sun : Moon" 
                                 class="h-4 w-4" />
                        <span class="text-gray-900 dark:text-white">{{ appearance === 'dark' ? 'Light Mode' : 'Dark Mode' }}</span>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
