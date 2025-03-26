<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import MainLayout from '@/layouts/MainLayout.vue';

</script>

<template>
<MainLayout>
        <Head title="Welcome">
            <link rel="preconnect" href="https://rsms.me/" />
            <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
        </Head>
        <div
            class="flex min-h-screen flex-col items-center bg-[#FDFDFC] text-[#1b1b18] dark:bg-[#0a0a0a] lg:justify-center">
            <!-- <header class="not-has-[nav]:hidden mb-6 w-full max-w-[335px] text-sm lg:max-w-4xl">
                <nav class="flex items-center justify-end gap-4">
                    <Link v-if="$page.props.auth.user" :href="route('dashboard')"
                        class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]">
                    Dashboard
                    </Link>
                    <template v-else>
                        <Link :href="route('login')"
                            class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]">
                        Log in
                        </Link>
                        <Link :href="route('register')"
                            class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]">
                        Register
                        </Link>
                    </template>
                </nav>
            </header> -->
            <div class="duration-750 starting:opacity-0 flex w-full items-center justify-center opacity-100 transition-opacity lg:grow">
                <div class="relative w-full">
            <!-- Background image div -->
            <div class="absolute inset-0 z-0">
                <div class="w-full h-screen"
                    style="background-image: linear-gradient(rgba(0, 0, 0, .7), rgba(0, 0, 0, .1)), url('/img/bg1.jpg');background-size:100% 100%;background-repeat: no-repeat;">
                </div>
            </div>

            <!-- Content that goes over the background -->
            <div class="relative z-10 pt-20"> <!-- Add padding-top to push content below navbar -->
                <div class="relative min-h-screen flex flex-col selection:bg-[#FF2D20] selection:text-white">
                    <div class="relative w-full">
                        <!-- ========== HEADER ========== -->




                        <!-- ========== END HEADER ========== -->
                        <!-- Hero -->
                        <div
                            class="relative overflow-hidden before:absolute before:top-0 before:start-1/2 before:bg-[url('https://preline.co/assets/svg/examples/polygon-bg-element.svg')] dark:before:bg-[url('https://preline.co/assets/svg/examples-dark/polygon-bg-element.svg')] before:bg-no-repeat before:bg-top before:bg-cover before:size-full before:-z-[1] before:transform before:-translate-x-1/2">

                        </div>
                        <!-- End Hero -->

                        <!-- Hero -->
                        <div class="overflow-hidden">
                            <div class="py- mx-auto">
                                <div class="relative mx-auto max-w-4xl grid space-y-4 sm:space-y-4">
                                    <!-- Title -->
                                    <div class="max-w-[85rem] mx-auto px-4 sm:px-6 lg:px-8 pt-32 pb-24">
                                        <!-- Announcement Banner -->
                                        <div class="flex justify-center">
                                            <p v-motion-slide-visible-top :delay="600" :duration="800"
                                                class="inline-flex items-center gap-x-2 bg-white border border-gray-200 text-2xl text-green-500 rubik p-1 px-6 rounded-full transition hover:border-gray-300 dark:bg-neutral-800 dark:border-neutral-700 dark:hover:border-neutral-600 dark:text-neutral-200">
                                                HARDBALL

                                            </p>
                                        </div>
                                        <!-- End Announcement Banner -->

                                        <!-- Title -->
                                        <div class="max-w-2xl text-center mx-auto">
                                            <h1 v-motion-slide-visible-top :delay="300" :duration="800"
                                                class="pr-3 pt-5 font-black great-vibes bg-clip-text bg-gradient-to-tl from-green-600 to-yellow-600 text-transparent text-4xl md:text-5xl lg:text-[70pt] dark:text-neutral-200">
                                                Caribbean </h1>
                                            <p v-motion-slide-visible-right :delay="200" :duration="600"
                                                class="text-6xl text-white rubik">Smokehouse</p>

                                        </div>
                                        <!-- End Title -->

                                        <div class="mt-5 max-w-3xl text-center mx-auto">
                                            <p v-motion-slide-visible-bottom :delay="300" :duration="800"
                                                class="text-4xl text-yellow-400 great-vibes font-bold dark:text-neutral-400">
                                                Come for the food, <span class="font-serif text-red-700">Stay</span> for
                                                the <span class="font-serif text-green-700">vibes</span>!</p>
                                        </div>

                                        <!-- ======================================================== -->




                                        <!-- Buttons -->
                                        <div v-motion-slide-visible-bottom :delay="600" :duration="800"
                                            class="mt-8 gap-3 flex justify-center">
                                            <a class="inline-flex justify-center items-center gap-x-3 text-center bg-gradient-to-tl from-green-600 to-yellow-600 hover:from-green-600 hover:to-yellow-600 border border-transparent text-white text-sm font-medium rounded-md focus:outline-none focus:ring-1 focus:ring-gray-600 py-3 px-4 dark:focus:ring-offset-gray-800"
                                                href="#">
                                                Get started
                                                <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg"
                                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path d="m9 18 6-6-6-6" />
                                                </svg>
                                            </a>

                                        </div>
                                        <!-- End Buttons -->

                                        <!-- <div class="mt-5 flex justify-center items-center gap-x-1 sm:gap-x-3">
                                <span class="text-sm text-gray-600 dark:text-neutral-400">Package Manager:</span>
                                <span class="text-sm font-bold text-gray-900 dark:text-white">npm</span>
                                <svg class="size-5 text-gray-300 dark:text-neutral-600" width="16" height="16"
                                    viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path d="M6 13L10 3" stroke="currentColor" stroke-linecap="round" />
                                </svg>
                                <a class="inline-flex items-center gap-x-1.5 text-sm text-blue-600 decoration-2 hover:underline font-medium"
                                    href="#">
                                    Installation Guide
                                    <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m9 18 6-6-6-6" />
                                    </svg>
                                </a>
                            </div> -->
                                    </div>


                                    <!-- End SVG Element -->
                                </div>
                            </div>
                        </div>
                        <div class="text-gray-700 body-font">
                            <div class="max-w-2xl px-10">

                                <div class="flex flex-wrap -m-4 text-center">
                                    <div v-motion-slide-visible-right :delay="200" :duration="800"
                                        class=" px-1 md:w-1/4 sm:w-1/2 w-full">
                                        <div
                                            class="cs-border-change border border-green-300 px-2 py-2 rounded-lg transform transition duration-500 hover:scale-110">
                                            <svg fill="none" stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-width="2"
                                                class="text-green-500 w-12 h-12 mb-3 inline-block" viewBox="0 0 24 24">
                                                <path d="M8 17l4 4 4-4m-4-5v9"></path>
                                                <path d="M20.88 18.09A5 5 0 0018 9h-1.26A8 8 0 103 16.29"></path>
                                            </svg>
                                            <h2 class="title-font font-medium text-3xl text-white">2.7K</h2>
                                            <p class="leading-relaxed">Downloads</p>
                                        </div>
                                    </div>
                                    <div v-motion-slide-visible-right :delay="400" :duration="800"
                                        class="px-1 md:w-1/4 sm:w-1/2 w-full">
                                        <div
                                            class="cs-border-change border border-green-400 px-2 py-2 rounded-lg transform transition duration-500 hover:scale-110">
                                            <svg fill="none" stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-width="2"
                                                class="text-green-500 w-12 h-12 mb-3 inline-block" viewBox="0 0 24 24">
                                                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"></path>
                                                <circle cx="9" cy="7" r="4"></circle>
                                                <path d="M23 21v-2a4 4 0 00-3-3.87m-4-12a4 4 0 010 7.75"></path>
                                            </svg>
                                            <h2 class="title-font font-medium text-3xl text-white">1.3K</h2>
                                            <p class="leading-relaxed">Users</p>
                                        </div>
                                    </div>
                                    <div v-motion-slide-visible-right :delay="600" :duration="800"
                                        class="px-1 md:w-1/4 sm:w-1/2 w-full">
                                        <div
                                            class="cs-border-change border border-green-300 px-2 py-2 rounded-lg transform transition duration-500 hover:scale-110">
                                            <svg fill="none" stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-width="2"
                                                class="text-green-500 w-12 h-12 mb-3 inline-block" viewBox="0 0 24 24">
                                                <path d="M3 18v-6a9 9 0 0118 0v6"></path>
                                                <path
                                                    d="M21 19a2 2 0 01-2 2h-1a2 2 0 01-2-2v-3a2 2 0 012-2h3zM3 19a2 2 0 002 2h1a2 2 0 002-2v-3a2 2 0 00-2-2H3z">
                                                </path>
                                            </svg>
                                            <h2 class="title-font font-medium text-3xl text-white">74</h2>
                                            <p class="leading-relaxed">Files</p>
                                        </div>
                                    </div>
                                    <div v-motion-slide-visible-right :delay="800" :duration="800"
                                        class="px-1 md:w-1/4 sm:w-1/2 w-full">
                                        <div
                                            class="cs-border-change border border-green-300 px-2 py-2 rounded-lg transform transition duration-500 hover:scale-110">
                                            <svg fill="none" stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-width="2"
                                                class="text-green-500 w-12 h-12 mb-3 inline-block" viewBox="0 0 24 24">
                                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                            </svg>
                                            <h2 class="title-font font-medium text-3xl text-white">46</h2>
                                            <p class="leading-relaxed">Places</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Hero -->
                    </div>
                </div>
            </div>

        </div>
            </div>
            <div class="h-14.5 hidden lg:block"></div>
        </div>
   </MainLayout>
</template>
