<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-black via-gray-900 to-black">
        <Head title="Login - Hardball Smokehouse" />
        
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_1px_1px,rgba(255,255,255,0.1)_1px,transparent_0)] bg-[length:20px_20px]"></div>
        </div>

        <!-- Main Content -->
        <div class="relative min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8">
            <div class="w-full max-w-md">
                <!-- Logo and Branding -->
                <div class="text-center mb-8">
                    <div class="flex justify-center mb-6">
                        <div class="relative">
                            <!-- Logo Background -->
                            <div class="w-24 h-24 bg-gradient-to-br from-green-500 to-yellow-500 rounded-full flex items-center justify-center shadow-2xl">
                                <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                </svg>
                            </div>
                            <!-- Decorative Elements -->
                            <div class="absolute -top-2 -right-2 w-8 h-8 bg-yellow-400 rounded-full opacity-80"></div>
                            <div class="absolute -bottom-2 -left-2 w-6 h-6 bg-green-400 rounded-full opacity-80"></div>
                        </div>
                    </div>
                    
                    <!-- Brand Text -->
                    <h1 class="text-4xl font-bold text-white mb-2">
                        <span class="text-green-400">HARDBALL</span>
                        <span class="text-yellow-400">SMOKEHOUSE</span>
                    </h1>
                    <p class="text-gray-300 text-lg font-medium">Caribbean Flavors</p>
                </div>

                <!-- Login Card -->
                <div class="bg-white/10 backdrop-blur-lg rounded-2xl border border-white/20 shadow-2xl p-8">
                    <!-- Status Message -->
                    <div v-if="status" class="mb-6 p-4 bg-green-500/20 border border-green-400/30 rounded-lg">
                        <p class="text-green-300 text-sm font-medium">{{ status }}</p>
                    </div>

                    <!-- Welcome Text -->
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-white mb-2">Welcome Back</h2>
                        <p class="text-gray-300">Sign in to your account</p>
                    </div>

                    <!-- Login Form -->
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-200 mb-2">
                                Email Address
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                    </svg>
                                </div>
                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    required
                                    autofocus
                                    class="w-full pl-10 pr-4 py-3 bg-white/10 border border-gray-600/50 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200"
                                    placeholder="Enter your email"
                                />
                            </div>
                            <InputError :message="form.errors.email" />
                        </div>

                        <!-- Password Field -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label for="password" class="block text-sm font-medium text-gray-200">
                                    Password
                                </label>
                                <TextLink 
                                    v-if="canResetPassword" 
                                    :href="route('password.request')" 
                                    class="text-sm text-green-400 hover:text-green-300 transition duration-200"
                                >
                                    Forgot password?
                                </TextLink>
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <input
                                    id="password"
                                    v-model="form.password"
                                    type="password"
                                    required
                                    class="w-full pl-10 pr-4 py-3 bg-white/10 border border-gray-600/50 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200"
                                    placeholder="Enter your password"
                                />
                            </div>
                            <InputError :message="form.errors.password" />
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center">
                            <input
                                id="remember"
                                v-model="form.remember"
                                type="checkbox"
                                class="h-4 w-4 text-green-500 focus:ring-green-500 border-gray-600 rounded bg-white/10"
                            />
                            <label for="remember" class="ml-2 block text-sm text-gray-300">
                                Remember me
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full bg-gradient-to-r from-green-500 to-yellow-500 hover:from-green-600 hover:to-yellow-600 text-white font-bold py-3 px-4 rounded-lg transition duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 focus:ring-offset-black disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                        >
                            <div class="flex items-center justify-center">
                                <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <svg v-else class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                </svg>
                                {{ form.processing ? 'Signing in...' : 'Sign In' }}
                            </div>
                        </button>
                    </form>

                    <!-- Contact Admin Message -->
                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-400">
                            Need an account? 
                            <span class="text-green-400 font-medium">Contact an administrator</span>
                        </p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="text-center mt-8">
                    <p class="text-gray-500 text-sm">
                        © 2025 Hardball Smokehouse. All rights reserved.
                    </p>
                </div>
            </div>
        </div>

        <!-- Decorative Elements -->
        <div class="fixed top-10 left-10 w-20 h-20 bg-green-500/20 rounded-full blur-xl"></div>
        <div class="fixed bottom-10 right-10 w-32 h-32 bg-yellow-500/20 rounded-full blur-xl"></div>
        <div class="fixed top-1/2 left-1/4 w-16 h-16 bg-green-400/10 rounded-full blur-lg"></div>
    </div>
</template>
