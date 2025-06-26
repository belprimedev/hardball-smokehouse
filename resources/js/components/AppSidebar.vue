<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { Folder, LayoutGrid, Moon, Sun, Calendar } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import { ref, onMounted } from 'vue';

// Extend NavItem to include children
interface ExtendedNavItem extends NavItem {
    children?: NavItem[];
}

// Add dark mode state
const isDarkMode = ref(false);

// Add toggle function
const toggleDarkMode = () => {
    isDarkMode.value = !isDarkMode.value;
    document.documentElement.classList.toggle('dark', isDarkMode.value);
    localStorage.setItem('darkMode', isDarkMode.value ? 'true' : 'false');
};

// Initialize dark mode on mount
onMounted(() => {
    const savedDarkMode = localStorage.getItem('darkMode');
    if (savedDarkMode === 'true' || (!savedDarkMode && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        isDarkMode.value = true;
        document.documentElement.classList.add('dark');
    }
});

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
                    <SidebarMenuButton @click="toggleDarkMode" class="hover:bg-gray-100 dark:hover:bg-gray-800">
                        <component :is="isDarkMode ? Sun : Moon" 
                                 class="h-4 w-4" />
                        <span class="text-gray-900 dark:text-white">{{ isDarkMode ? 'Light Mode' : 'Dark Mode' }}</span>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
