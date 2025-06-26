<script setup lang="ts">
import UserInfo from '@/components/UserInfo.vue';
import { DropdownMenuGroup, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator } from '@/components/ui/dropdown-menu';
import type { User } from '@/types';
import { Link } from '@inertiajs/vue3';
import { LogOut, Settings } from 'lucide-vue-next';

interface Props {
    user: User;
}

defineProps<Props>();
</script>

<template>
    <DropdownMenuLabel class="p-0 font-normal">
        <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
            <UserInfo :user="user" :show-email="true" />
        </div>
    </DropdownMenuLabel>
    <DropdownMenuSeparator class="bg-gray-200 dark:bg-gray-700" />
    <DropdownMenuGroup>
        <DropdownMenuItem :as-child="true" class="hover:bg-gray-100 dark:hover:bg-gray-700">
            <Link class="block w-full text-gray-900 dark:text-white" :href="route('profile.edit')" as="button">
                <Settings class="mr-2 h-4 w-4 text-gray-500 dark:text-gray-400" />
                Settings
            </Link>
        </DropdownMenuItem>
    </DropdownMenuGroup>
    <DropdownMenuSeparator class="bg-gray-200 dark:bg-gray-700" />
    <DropdownMenuItem :as-child="true" class="hover:bg-gray-100 dark:hover:bg-gray-700">
        <Link class="block w-full text-gray-900 dark:text-white" method="post" :href="route('logout')" as="button">
            <LogOut class="mr-2 h-4 w-4 text-gray-500 dark:text-gray-400" />
            Log out
        </Link>
    </DropdownMenuItem>
</template>
