<script setup lang="ts">

//import T1extLink from '@/Components/T1extLink.vue';
import { ref, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';

const showingNavigationDropdown = ref(false);

// Newsletter subscription
const footerEmail = ref('');
const footerNewsletterSubmitted = ref(false);
const footerNewsletterError = ref('');

// Restaurant information from general settings
const restaurantInfo = ref({
    business_name: 'Hardball Caribbean Smokehouse',
    address: '24 Lloyds Ave, Ipswich IP1 3HD',
    email: 'info@hardballsmokehouse.co.uk',
    phone: '+44 01473 807117'
});

// Fetch general settings
const fetchGeneralSettings = async () => {
    try {
        const response = await fetch('/api/general-settings');
        const settings = await response.json();
        
        // Update restaurant info with settings data
        restaurantInfo.value = {
            business_name: settings.business_name || 'Hardball Caribbean Smokehouse',
            address: settings.address || '24 Lloyds Ave, Ipswich IP1 3HD',
            email: settings.business_email || 'info@hardballsmokehouse.co.uk',
            phone: settings.contact_number || '+44 01473 807117'
        };
    } catch (error) {
        console.error('Error fetching general settings:', error);
    }
};

// Footer newsletter subscription
const submitFooterNewsletter = async () => {
    if (!footerEmail.value || !footerEmail.value.includes('@')) {
        footerNewsletterError.value = 'Please enter a valid email address.';
        return;
    }

    try {
        footerNewsletterError.value = '';
        const response = await axios.post('/api/newsletters/subscribe', {
            email: footerEmail.value,
            source: 'footer'
        });

        if (response.data.success) {
            footerNewsletterSubmitted.value = true;
            footerEmail.value = '';
            setTimeout(() => {
                footerNewsletterSubmitted.value = false;
            }, 5000);
        }
    } catch (error: any) {
        console.error('Footer newsletter subscription error:', error);
        if (error.response?.data?.message) {
            footerNewsletterError.value = error.response.data.message;
        } else {
            footerNewsletterError.value = 'Failed to subscribe. Please try again.';
        }
    }
};

onMounted(() => {
    fetchGeneralSettings();
});

// Scroll to top function
const scrollToTop = () => {
    if (typeof window !== 'undefined') {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
};
</script>
<style>
.rubik {
    font-family: "Rubik Mono One", monospace;
    font-weight: 400;
    font-style: normal;
}

.great-vibes {
    font-family: "Great Vibes", cursive;
    font-weight: 400;
    font-style: normal;
}

.knewave-regular {
    font-family: "Knewave", system-ui;
    font-weight: 400;
    font-style: normal;
}

body {
    margin: 0;
}
</style>

<template>
    <div class="min-h-screen flex flex-col items-center">
        <!-- Favicon Meta Tags -->
        <Head>
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
            <link href="https://fonts.googleapis.com/css2?family=Knewave&display=swap" rel="stylesheet">
            <link rel="icon" type="image/x-icon" href="/favicon.ico">
            <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
            <link rel="icon" type="image/svg+xml" href="/favicon.svg">
            <link rel="apple-touch-icon" href="/apple-touch-icon.png">
            <link rel="manifest" href="/site.webmanifest">
        </Head>
        <!-- Navigation - Make it fixed or absolute -->
        <nav
            class="fixed bg-gray-900 dark:bg-gray-900 backdrop-filter backdrop-blur-lg bg-opacity-40 firefox:bg-opacity-90 w-full z-50 top-0 start-0 border-b border-emerald-200 dark:border-gray-600">
            <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
                <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
                    <img src="img/smokehouse-logo.png" class="h-16" alt=" Logo">
                    <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white"></span>
                </a>
                <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
                    <div class="fixed bottom-4 sm:bottom-8 right-4 sm:right-8 z-50">
            <a :href="route('make-reservation')"
                class="group bg-gradient-to-r from-green-600 to-yellow-600 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-full shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex items-center gap-2 animate-pulse-slow">
            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span class="hidden sm:inline">Book a Table</span>
            <span class="sm:hidden">Book</span>
            <span
                class="absolute -top-1 sm:-top-2 -right-1 sm:-right-2 bg-red-500 text-white text-xs px-1 sm:px-2 py-0.5 sm:py-1 rounded-full animate-pulse">Now
                Open</span>
            </a>
        </div>
                    <div class="-me-2 flex items-center sm:hidden">
                        <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-700 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{
                                    hidden: showingNavigationDropdown,
                                    'inline-flex': !showingNavigationDropdown,
                                }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{
                                    hidden: !showingNavigationDropdown,
                                    'inline-flex': showingNavigationDropdown,
                                }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-sticky">
                    <ul
                        class="flex flex-col p-4 md:p-0 mt-4 font-medium border border-gray-100 rounded-lg md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-whit dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
                        <li>
                            <a :href="route('menu')"
                                :class="[
                                    'block py-2 px-3 text-gray-300 text-2xl font-black knewave-regular rounded md:bg-transparent md:p-0 md:dark:text-gray-100 md:hover:text-green-700',
                                    { 'border-b-2 pb-4 border-emerald-600': route().current('menu') }
                                ]"
                                aria-current="page">Menu</a>
                        </li>
                        <li>
                            <a :href="route('cocktail')"
                                :class="[
                                    'block py-2 px-3 text-gray-300 text-2xl font-black knewave-regular rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-green-700 md:p-0 md:dark:hover:text-green-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700',
                                    { 'border-b-2 border-yellow-500': route().current('cocktail') }
                                ]">Cocktail</a>
                        </li>
                        <li>
                            <a :href="route('gallery')"
                                :class="[
                                    'block py-2 px-3 text-gray-300 text-2xl font-black knewave-regular rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-green-700 md:p-0 md:dark:hover:text-green-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700',
                                    { 'border-b-2 border-yellow-500': route().current('gallery') }
                                ]">Gallery</a>
                        </li>
                        <li>
                            <a :href="route('about')"
                                :class="[
                                    'block py-2 px-3 text-gray-300 text-2xl font-black knewave-regular rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-green-700 md:p-0 md:dark:hover:text-green-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700',
                                    { 'border-b-2 border-yellow-500': route().current('about') }
                                ]">About</a>
                        </li>
                        <li>
                            <a :href="route('contact')"
                                :class="[
                                    'block py-2 px-3 text-gray-300 text-2xl font-black knewave-regular rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-green-700 md:p-0 md:dark:hover:text-green-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700',
                                    { 'border-b-2 border-yellow-500': route().current('contact') }
                                ]">Contact</a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- Responsive Navigation Menu -->
            <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                class="sm:hidden bg-gray-100 rounded-lg mt-2">
                <div class="pt-2 pb-3 space-y-1">
                    <a
                        class="block py-2 px-3 text-gray-900 text-xl font-black knewave-regular rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-green-700 md:p-0 md:dark:hover:text-green-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700"
                        :href="route('menu')" aria-current="page">
                        Menu
                    </a>
                    <a
                        class="block py-2 px-3 text-gray-900 text-xl font-black knewave-regular rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-green-700 md:p-0 md:dark:hover:text-green-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700"
                        :href="route('cocktail')" aria-current="page">
                        Cocktail
                    </a>

                    <a
                        class="block py-2 px-3 text-gray-900 text-xl font-black knewave-regular rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-green-700 md:p-0 md:dark:hover:text-green-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700"
                        :href="route('gallery')" aria-current="page">
                        Gallery
                    </a>

                    <a
                        class="block py-2 px-3 text-gray-900 text-xl font-black knewave-regular rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-green-700 md:p-0 md:dark:hover:text-green-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700"
                        :href="route('about')" aria-current="page">
                        About
                    </a>

                    <a
                        class="block py-2 px-3 text-gray-900 text-xl font-black knewave-regular rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-green-700 md:p-0 md:dark:hover:text-green-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700"
                        :href="route('contact')" aria-current="page">
                        Contact
                    </a>
                </div>
            </div>
        </nav>
        <!-- ========== End Navigation ========== -->

        <!-- Main content wrapper - Add padding-top to account for fixed nav -->
        <div class="relative w-full dark:bg-gray-800">
            <slot />
        </div>

        <!-- Footer -->
        <footer class="relative w-full mt-auto bg-gray-900">
            <!-- Subtle Background Design -->
            <div class="absolute inset-0 opacity-3 z-0">
                <!-- Subtle geometric shapes -->
                <div class="absolute top-4 left-4 w-24 h-24 border border-gray-600 rounded-full opacity-20"></div>
                <div class="absolute top-40 right-20 w-12 h-12 bg-gray-600 rounded-full opacity-15"></div>
                <div class="absolute bottom-20 left-1/4 w-8 h-8 border border-gray-600 transform rotate-45 opacity-20"></div>
                <div class="absolute bottom-40 right-1/3 w-10 h-10 bg-gray-600 rounded-full opacity-15"></div>
                
                <!-- Very subtle diagonal lines -->
                <div class="absolute top-0 left-0 w-full h-full">
                    <div class="absolute top-10 left-0 w-full h-px bg-gradient-to-r from-transparent via-gray-600 to-transparent transform rotate-5 opacity-10"></div>
                    <div class="absolute top-40 left-0 w-full h-px bg-gradient-to-r from-transparent via-gray-600 to-transparent transform -rotate-3 opacity-10"></div>
                    <div class="absolute bottom-20 left-0 w-full h-px bg-gradient-to-r from-transparent via-gray-600 to-transparent transform rotate-2 opacity-10"></div>
                </div>
                
                <!-- Minimal dots -->
                <div class="absolute top-1/3 left-1/4 w-1 h-1 bg-gray-600 rounded-full opacity-20"></div>
                <div class="absolute top-1/2 right-1/4 w-1 h-1 bg-gray-600 rounded-full opacity-20"></div>
                <div class="absolute bottom-1/3 left-1/3 w-1 h-1 bg-gray-600 rounded-full opacity-20"></div>
                
                <!-- Subtle curved elements -->
                <div class="absolute top-1/2 left-5 w-12 h-12 border border-gray-600 rounded-full border-t-transparent border-r-transparent transform -rotate-45 opacity-15"></div>
                <div class="absolute top-1/4 right-5 w-8 h-8 border border-gray-600 rounded-full border-b-transparent border-l-transparent transform rotate-45 opacity-15"></div>
            </div>

            <!-- Main Footer Content -->
            <div class="relative z-20 container mx-auto px-4 py-16">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    
                    <!-- Company Information Column -->
                    <div class="lg:col-span-1">
                        <!-- Logo and Company Info -->
                        <div class="mb-6">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="w-24 h-24 rounded-full flex items-center justify-center shadow-lg">
                                    <img src="/img/smokehouse-logo.png" alt="Hardball Smokehouse Logo" class="w-16 h-16 object-contain">
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-100">
                                        Hardball
                                        <span class="text-yellow-400">Smokehouse</span>
                                    </h3>
                                    <p class="text-yellow-400 text-sm font-semibold">Caribbean Cuisine</p>
                                </div>
                            </div>
                            <p class="text-gray-50 leading-relaxed">
                                Great platform for authentic Caribbean cuisine passionate about food. Find your delicious Caribbean dishes easier, passionate about food for you!
                            </p>
                        </div>

                        <!-- Social Media Links -->
                        <div class="flex space-x-4">
                            <a href="#" class="relative w-10 h-10 bg-white border border-gray-300 rounded-full flex items-center justify-center hover:bg-accent-yellow hover:text-dark-900 transition-all duration-300 group">
                                <svg class="w-5 h-5 text-gray-800" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                                <!-- Tooltip -->
                                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-1 bg-gray-900 text-white text-sm rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap z-50">
                                    Facebook
                                    <!-- Arrow -->
                                    <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900"></div>
                                </div>
                            </a>
                            <a href="https://www.instagram.com/hardball_caribbean_smokehouse/" class="relative w-10 h-10 bg-white border border-gray-300 rounded-full flex items-center justify-center hover:bg-accent-yellow hover:text-dark-900 transition-all duration-300 group">
                                <svg class="w-5 h-5 text-gray-800" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                                <!-- Tooltip -->
                                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-1 bg-gray-900 text-white text-sm rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap z-50">
                                    Instagram
                                    <!-- Arrow -->
                                    <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900"></div>
                                </div>
                            </a>
                           
                        </div>
                    </div>

                    <!-- Popular Links Column -->
                    <div class="lg:col-span-1">
                        <div class="space-y-3">
                            <a href="/menu" class="flex items-center text-gray-50 hover:text-accent-yellow transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Menu
                            </a>
                            <a href="/cocktail" class="flex items-center text-gray-50 hover:text-accent-yellow transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Cocktail
                            </a>
                            <a href="/gallery" class="flex items-center text-gray-50 hover:text-accent-yellow transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Gallery
                            </a>
                            <a :href="route('about')" class="flex items-center text-gray-50 hover:text-accent-yellow transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                                About Us
                            </a>
                            <a href="/vacancy" class="flex items-center text-gray-50 hover:text-accent-yellow transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Vacancy
                            </a>
                            <a href="/contact" class="flex items-center text-gray-50 hover:text-accent-yellow transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Contact Us
                            </a>
                        </div>
                    </div>

                    <!-- Photo Gallery Column -->
                    <div class="lg:col-span-1">
                        <div class="grid grid-cols-3 gap-2">
                            <div class="aspect-square bg-gray-200 rounded-lg overflow-hidden">
                                <img src="/img/food/burger.png" alt="Burger" class="w-full h-full object-cover hover:scale-110 transition-transform duration-300"/>
                            </div>
                            <div class="aspect-square bg-gray-200 rounded-lg overflow-hidden">
                                <img src="/img/food/fritters.jpg" alt="Fritters" class="w-full h-full object-cover hover:scale-110 transition-transform duration-300"/>
                            </div>
                            <div class="aspect-square bg-gray-200 rounded-lg overflow-hidden">
                                <img src="/img/food/portrait5.JPG" alt="Caribbean Dish" class="w-full h-full object-cover hover:scale-110 transition-transform duration-300"/>
                            </div>
                            <div class="aspect-square bg-gray-200 rounded-lg overflow-hidden">
                                <img src="/img/gallery/store4.JPG" alt="Restaurant Interior" class="w-full h-full object-cover hover:scale-110 transition-transform duration-300"/>
                            </div>
                            <div class="aspect-square bg-gray-200 rounded-lg overflow-hidden">
                                <img src="/img/gallery/event1.jpg" alt="Event" class="w-full h-full object-cover hover:scale-110 transition-transform duration-300"/>
                            </div>
                            <div class="aspect-square bg-gray-200 rounded-lg overflow-hidden">
                                <img src="/img/gallery/food1.jpg" alt="Food" class="w-full h-full object-cover hover:scale-110 transition-transform duration-300"/>
                            </div>
                        </div>
                    </div>

                    <!-- Newsletter and Payment Methods Column -->
                    <div class="lg:col-span-1">
                        <!-- Newsletter Section -->
                        <div class="mb-8">
                            <p class="text-gray-50 mb-4">Subscribe newsletter to get updates</p>
                            <div class="flex flex-col space-y-3">
                                <input 
                                    v-model="footerEmail"
                                    type="email" 
                                    placeholder="Email Address" 
                                    class="px-4 py-3 bg-gray-200 border border-gray-300 rounded-lg text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accent-yellow focus:border-transparent"
                                />
                                <button @click="submitFooterNewsletter"
                                    class="bg-yellow-400 text-dark-900 px-6 py-3 rounded-lg font-bold hover:bg-yellow-500 transition-colors">
                                    Subscribe
                                </button>
                            </div>
                            
                            <!-- Success Message -->
                            <div v-if="footerNewsletterSubmitted" class="mt-3 text-green-400 text-sm">
                                Thank you for subscribing! Check your email for updates.
                            </div>
                            
                            <!-- Error Message -->
                            <div v-if="footerNewsletterError" class="mt-3 text-red-400 text-sm">
                                {{ footerNewsletterError }}
                            </div>
                        </div>

                        <!-- Payment Methods Section -->
                        
                    </div>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="border-t border-gray-300 relative z-20">
                <div class="container mx-auto px-4 py-6">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <!-- Copyright -->
                        <div class="text-gray-50 text-sm mb-4 md:mb-0">
                            © 2025 {{ restaurantInfo.business_name }}. All Rights Reserved.
                        </div>

                        <!-- Legal Links -->
                        <div class="flex items-center space-x-4 text-sm">
                            <a :href="route('privacy')" class="text-gray-50 hover:text-accent-yellow transition-colors">Privacy Policy</a>
                            <div class="w-px h-4 bg-gray-400"></div>
                            <a :href="route('terms')" class="text-gray-50 hover:text-accent-yellow transition-colors">Terms & Conditions</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scroll to Top Button -->
            <button 
                @click="scrollToTop"
                class="fixed bottom-8 right-8 w-12 h-12 bg-yellow-400 text-dark-900 rounded-full flex items-center justify-center hover:bg-yellow-400 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-110 z-50"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                </svg>
            </button>

            
        </footer>
    </div>
</template>