<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { Folder, LayoutGrid, Moon, Sun, Calendar, Users, Shield, Briefcase, Mail, MessageSquare } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import { useAppearance } from '@/composables/useAppearance';
import { computed } from 'vue';

// Extend NavItem to include children
interface ExtendedNavItem extends NavItem {
    children?: NavItem[];
}

// Use the same appearance composable
const { appearance, updateAppearance } = useAppearance();

const page = usePage();
const auth = computed(() => page.props.auth as any);

// Check if user has admin role or manage users permission
const canManageUsers = computed(() => {
    if (!auth.value?.user) return false;
    
    // Check if user has admin role
    const hasAdminRole = auth.value.user.roles?.some((role: any) => role.name === 'admin');
    if (hasAdminRole) return true;
    
    // Check if user has manage users permission
    const hasManageUsersPermission = auth.value.user.permissions?.some((permission: any) => permission.name === 'manage users');
    return hasManageUsersPermission;
});

// Check if user is admin
const isAdmin = computed(() => {
    if (!auth.value?.user) return false;
    return auth.value.user.roles?.some((role: any) => role.name === 'admin');
});

// Check if user is admin or manager
const isAdminOrManager = computed(() => {
    if (!auth.value?.user) return false;
    return auth.value.user.roles?.some((role: any) => role.name === 'admin' || role.name === 'manager');
});

// Check specific permissions (for backward compatibility)
const canManageVacancies = computed(() => {
    if (!auth.value?.user) return false;
    return auth.value.user.permissions?.some((permission: any) => permission.name === 'manage vacancies');
});

const canManageNewsletters = computed(() => {
    if (!auth.value?.user) return false;
    return auth.value.user.permissions?.some((permission: any) => permission.name === 'manage newsletters');
});

const canManageContacts = computed(() => {
    if (!auth.value?.user) return false;
    return auth.value.user.permissions?.some((permission: any) => permission.name === 'manage contacts');
});

// Check if user has any management permissions
const hasManagementPermissions = computed(() => {
    return canManageUsers.value || isAdminOrManager.value;
});

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
                        <Link :href="route('admin.dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

                    <SidebarContent class="bg-white dark:bg-gray-900">

                
                <!-- Main Navigation -->
                <NavMain :items="mainNavItems" />
            
            <!-- Visual Separator -->
            <div v-if="hasManagementPermissions" class="mx-3 my-2 border-t border-gray-200 dark:border-gray-700"></div>
            
            <!-- Administration Section -->
            <div v-if="hasManagementPermissions" class="px-2 py-0">
                <div class="text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider px-3 py-2">
                    Administration
                </div>
                <SidebarMenu>
                    <SidebarMenuItem v-if="isAdminOrManager">
                        <SidebarMenuButton as-child class="hover:bg-gray-100 dark:hover:bg-gray-800">
                            <Link :href="route('admin.vacancies.index')" class="text-gray-900 dark:text-white">
                                <Briefcase class="text-gray-700 dark:text-gray-300" />
                                <span>Vacancy Management</span>
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                    <SidebarMenuItem v-if="isAdminOrManager">
                        <SidebarMenuButton as-child class="hover:bg-gray-100 dark:hover:bg-gray-800">
                            <Link :href="route('admin.newsletters.index')" class="text-gray-900 dark:text-white">
                                <Mail class="text-gray-700 dark:text-gray-300" />
                                <span>Newsletter Management</span>
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                    <SidebarMenuItem v-if="isAdminOrManager">
                        <SidebarMenuButton as-child class="hover:bg-gray-100 dark:hover:bg-gray-800">
                            <Link :href="route('admin.contacts.index')" class="text-gray-900 dark:text-white">
                                <MessageSquare class="text-gray-700 dark:text-gray-300" />
                                <span>Contact Management</span>
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </div>

            <!-- Visual Separator -->
            <div v-if="isAdmin" class="mx-3 my-2 border-t border-gray-200 dark:border-gray-700"></div>

            <!-- System Admin Section (Admin Only) -->
            <div v-if="isAdmin" class="px-2 py-0">
                <div class="text-xs font-medium text-gray-700 bg-orange-50 dark:text-gray-300 uppercase tracking-wider px-3 py-2">
                    System Admin
                </div>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton as-child class="hover:bg-gray-100 dark:hover:bg-gray-800">
                            <Link :href="route('user-management.index')" class="text-gray-900 dark:text-white">
                                <Users class="text-gray-700 dark:text-gray-300" />
                                <span>User Management</span>
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                    <SidebarMenuItem>
                        <SidebarMenuButton as-child class="hover:bg-gray-100 dark:hover:bg-gray-800">
                            <Link :href="route('admin.dashboard')" class="text-gray-900 dark:text-white">
                                <Shield class="text-gray-700 dark:text-gray-300" />
                                <span>Admin Panel</span>
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </div>
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
