<template>
  <AppLayout title="Create User">
    <template #header>
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
          <a
            href="/user-management"
            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg"
          >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Users
          </a>
          <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Create New User
          </h2>
        </div>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
          <div class="px-6 py-8">
            <!-- Header Info -->
            <div class="mb-8 bg-gradient-to-br from-green-300 via-emerald-400 to-teal-400 dark:from-green-600 dark:via-emerald-700 dark:to-teal-800 rounded-xl p-8 border-0 shadow-2xl relative overflow-hidden">
              <div class="absolute inset-0 bg-black/10 dark:bg-black/20"></div>
              <div class="relative flex items-center space-x-6">
                <div class="flex-shrink-0">
                  <div class="h-20 w-20 rounded-full bg-white/20 backdrop-blur-sm border-2 border-white/30 flex items-center justify-center shadow-lg">
                    <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                  </div>
                </div>
                <div>
                  <h3 class="text-2xl font-bold text-white mb-2">Create New User Account</h3>
                  <p class="text-green-100 dark:text-green-200 text-lg">Add a new user to the system with appropriate roles and permissions.</p>
                </div>
              </div>
            </div>

            <form @submit.prevent="submit" class="space-y-8">
              <!-- Name -->
              <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/30 dark:to-emerald-900/30 rounded-xl p-6 border border-green-200 dark:border-green-700 shadow-lg">
                <label for="name" class="block text-sm font-semibold text-green-800 dark:text-green-200 mb-3 flex items-center">
                  <svg class="w-5 h-5 mr-2 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                  </svg>
                  Full Name
                </label>
                <input
                  id="name"
                  v-model="form.name"
                  type="text"
                  class="mt-1 block w-full px-4 py-3 border-green-300 dark:border-green-600 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white text-sm transition duration-150 bg-white/80 dark:bg-gray-800/80"
                  :class="{ 'border-red-500 focus:ring-red-300 focus:border-red-300 dark:border-red-400': form.errors.name }"
                  placeholder="Enter full name"
                />
                <p v-if="form.errors.name" class="mt-2 text-sm text-red-400 dark:text-red-400 flex items-center">
                  <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                  </svg>
                  {{ form.errors.name }}
                </p>
              </div>

              <!-- Email -->
              <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/30 dark:to-indigo-900/30 rounded-xl p-6 border border-blue-200 dark:border-blue-700 shadow-lg">
                <label for="email" class="text-sm font-semibold text-blue-800 dark:text-blue-200 mb-3 flex items-center">
                  <svg class="w-5 h-5 mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                  </svg>
                  Email Address
                </label>
                <input
                  id="email"
                  v-model="form.email"
                  type="email"
                  class="mt-1 block w-full px-4 py-3 border-blue-300 dark:border-blue-600 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white text-sm transition duration-150 bg-white/80 dark:bg-gray-800/80"
                  :class="{ 'border-red-300 focus:ring-red-300 focus:border-red-300 dark:border-red-400': form.errors.email }"
                  placeholder="Enter email address"
                />
                <p v-if="form.errors.email" class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                  <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                  </svg>
                  {{ form.errors.email }}
                </p>
              </div>

              <!-- Password Section -->
              <div class="bg-gradient-to-br from-yellow-300 via-orange-400 to-red-300 dark:from-yellow-600 dark:via-orange-700 dark:to-red-800 rounded-xl p-8 border-0 shadow-2xl relative overflow-hidden">
                <div class="absolute inset-0 bg-black/10 dark:bg-black/20"></div>
                <div class="relative">
                  <h3 class="text-xl font-bold text-white mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-yellow-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    Password Settings
                  </h3>
                
                <!-- Password -->
                <div class="mb-6">
                  <label for="password" class="block text-sm font-semibold text-yellow-100 dark:text-yellow-200 mb-3">
                    Password
                  </label>
                  <input
                    id="password"
                    v-model="form.password"
                    type="password"
                    class="block w-full px-4 py-3 border-yellow-300 dark:border-yellow-600 rounded-lg shadow-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-300 dark:bg-gray-700 dark:text-white text-sm transition duration-150 bg-white/90 dark:bg-gray-800/90"
                    :class="{ 'border-red-300 focus:ring-red-300 focus:border-red-300 dark:border-red-400': form.errors.password }"
                    placeholder="Enter password"
                  />
                  <p v-if="form.errors.password" class="mt-2 text-sm text-red-200 dark:text-red-300">{{ form.errors.password }}</p>
                </div>

                <!-- Password Confirmation -->
                <div>
                  <label for="password_confirmation" class="block text-sm font-semibold text-yellow-100 dark:text-yellow-200 mb-3">
                    Confirm Password
                  </label>
                  <input
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="block w-full px-4 py-3 border-yellow-300 dark:border-yellow-600 rounded-lg shadow-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 dark:bg-gray-700 dark:text-white text-sm transition duration-150 bg-white/90 dark:bg-gray-800/90"
                    :class="{ 'border-red-500 focus:ring-red-500 focus:border-red-500 dark:border-red-400': form.errors.password_confirmation }"
                    placeholder="Confirm password"
                  />
                  <p v-if="form.errors.password_confirmation" class="mt-2 text-sm text-red-200 dark:text-red-300">{{ form.errors.password_confirmation }}</p>
                </div>
              </div>
              </div>

              <!-- Roles -->
              <div class="bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/30 dark:to-pink-900/30 rounded-xl p-6 border border-purple-200 dark:border-purple-700 shadow-lg">
                <label class="block text-sm font-semibold text-purple-800 dark:text-purple-200 mb-6 flex items-center">
                  <svg class="w-5 h-5 mr-2 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                  </svg>
                  User Roles
                </label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <label
                    v-for="role in roles"
                    :key="role.id"
                    class="relative flex items-start p-4 border border-purple-200 dark:border-purple-600 rounded-lg hover:bg-purple-50 dark:hover:bg-purple-600/20 cursor-pointer transition duration-150 bg-white/80 dark:bg-gray-800/80"
                    :class="{ 'bg-purple-100 border-purple-400 dark:bg-purple-900/40 dark:border-purple-500 shadow-md': form.roles.includes(role.name) }"
                  >
                    <div class="flex items-center h-5">
                      <input
                        :value="role.name"
                        v-model="form.roles"
                        type="checkbox"
                        class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-purple-300 dark:border-purple-600 rounded"
                      />
                    </div>
                    <div class="ml-3 text-sm">
                      <span class="font-semibold text-purple-900 dark:text-purple-100 capitalize">{{ role.name }}</span>
                      <p class="text-purple-600 dark:text-purple-300 text-xs mt-1">
                        {{ role.name === 'admin' ? 'Full system access' : role.name === 'manager' ? 'Manage reservations and menu' : 'View and manage reservations' }}
                      </p>
                    </div>
                  </label>
                </div>
                <p v-if="form.errors.roles" class="mt-3 text-sm text-red-600 dark:text-red-400">{{ form.errors.roles }}</p>
              </div>

              <!-- Submit Button -->
              <div class="flex justify-end space-x-4">
                <a
                  href="/user-management"
                  class="inline-flex items-center px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-150 shadow-lg"
                >
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                  </svg>
                  Cancel
                </a>
                <button
                  type="submit"
                  :disabled="form.processing"
                  class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-green-500 via-emerald-500 to-teal-600 border border-transparent rounded-xl font-bold text-white hover:from-green-600 hover:via-emerald-600 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 disabled:opacity-50 shadow-xl transform hover:scale-105"
                >
                  <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  <svg v-else class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                  </svg>
                  {{ form.processing ? 'Creating...' : 'Create User' }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'

interface Props {
  roles: Array<{ id: number; name: string }>
  permissions: Array<{ id: number; name: string }>
}

const props = defineProps<Props>()

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  roles: [] as string[],
})

const roles = props.roles

const submit = () => {
  form.post('/user-management')
}
</script> 