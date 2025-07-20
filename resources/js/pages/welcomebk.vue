<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import MainLayout from '@/layouts/MainLayout.vue';
import 'vue3-carousel/dist/carousel.css';
import { Carousel, Slide, Pagination } from 'vue3-carousel';
import { ref, onMounted } from 'vue';
import axios from 'axios';

interface MenuItem {
    id: number;
    name: string;
    description: string | null;
    price: number;
    image_path: string | null;
    category_id: number;
    is_featured: boolean;
    is_chef_special: boolean;
    is_available: boolean;
    is_visible: boolean;
    category?: {
        id: number;
        name: string;
        description: string | null;
    };
}

//import { Carousel, Slide, Navigation } from '...'; // Need to check the actual import paths, but in the original code, they are components, so assuming they are imported.

//import { useMotion } from '@vueuse/motion'; // Assuming useMotion is from VueUse Motion.

import { reactive } from 'vue';

// Handling the name

defineOptions({

name: 'App'

});

// motionConfig



// data properties

const settings = reactive({

itemsToShow: 2,

snapAlign: 'center' as const,

});

const breakpoints = reactive({

320: {

itemsToShow: 2,

snapAlign: 'center' as const,

},

700: {

itemsToShow: 3,

snapAlign: 'center' as const,

},

1024: {

itemsToShow: 5,

snapAlign: 'start' as const,

},

});

const featuredItems = ref<MenuItem[]>([]);

onMounted(async () => {
    try {
        const response = await axios.get<MenuItem[]>('/api/chef-special-items');
        featuredItems.value = response.data;
    } catch (error) {
        console.error('Error fetching chef special menu items:', error);
    }
});

const getImageSource = (imagePath: string | null): string => {
    if (!imagePath) return '/img/dishes2_4.png'; // Default image
    return `/storage/${imagePath}`;
};

const dessertItems = [
  {
    name: "Vanilla Cupcakes",
    image: "/img/desserts/cupcake.jpg",
    ingredients: "Butter/ Olive Oil / Salt",
    priceHalf: 43,
    priceFull: 85,
    note: "Extra Free Juice."
  },
  {
    name: "Lemon Meringue Pie",
    image: "/img/desserts/lemon.jpg",
    ingredients: "Mutton / Olive Oil / Salt",
    priceHalf: 35,
    priceFull: 71,
    note: "Extra Free Juice."
  },
  {
    name: "French Fries",
    image: "/img/desserts/fries.jpg",
    ingredients: "Mutton / Olive Oil / Salt",
    priceHalf: 14,
    priceFull: 29,
    note: "Extra Free Juice."
  },
  {
    name: "Chocolate Brownie",
    image: "/img/desserts/brownie.jpg",
    ingredients: "Mutton / Olive Oil / Salt",
    priceHalf: 35,
    priceFull: 75,
    note: "Extra Free Juice."
  }
];

const dessertImage = "/img/desserts/cake.jpg"; // The big right-side image
const dessertSize = ref<'half' | 'full'>('half');

</script>

<style>
.cta-section.style-white:before {
    position: absolute;
    left: 0;
    right: 0;
    bottom: 0;
    width: 100%;
    height: 100%;
    content: "";
    background-color: var(--white);
    top: 50%
}

.popular-dishes-wrapper.style1 {
    position: relative
}

.popular-dishes-wrapper.style1 .btn-wrapper {
    display: block;
    margin: 60px auto 0
}

.popular-dishes-wrapper.style1 .shape1 {
    position: absolute;
    bottom: -120px;
    left: 0;
    z-index: 1
}

.popular-dishes-wrapper.style1 .shape2 {
    position: absolute;
    top: -50px;
    right: 0;
    z-index: 1
}

.popular-dishes-wrapper.style2 {
    position: relative
}

.popular-dishes-wrapper.style2 .shape1 {
    position: absolute;
    bottom: 0;
    left: 0;
    z-index: 1
}

.popular-dishes-wrapper.style2 .shape2 {
    position: absolute;
    top: 0;
    right: 0;
    z-index: 1
}

.popular-dishes-wrapper.style2 .btn-wrapper {
    max-width: 195px;
    margin: 0 auto
}

.popular-dishes-wrapper.style3 {
    position: relative
}

.popular-dishes-wrapper.style3 .swiper {
    overflow: visible
}

.popular-dishes-wrapper.style3 .shape1 {
    position: absolute;
    top: -60px;
    left: 0
}

.popular-dishes-wrapper.style3 .shape2 {
    position: absolute;
    top: -30px;
    right: 0
}

.popular-dishes-wrapper.style4 {
    position: relative
}

.popular-dishes-wrapper.style4 .shape1 {
    position: absolute;
    top: 0;
    left: 0;
    z-index: 1
}

.dishes-card-wrap.style1 {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 5px
}

.dishes-card.style2 {
    padding: 26px 25px;
    margin-top: 140px;
    background: linear-gradient(180deg, #ffffff4d, #fff 63.33%);
    text-align: center;
    border-radius: 16px;
    background: var(--white);
    box-shadow: 0 4px 54px #00000014;
}

.dishes-card.style2 .dishes-thumb {
    position: relative;
    margin-top: -120px;
    border-radius: 50%;
    -webkit-border-radius: 50%;
    -moz-border-radius: 50%;
    -ms-border-radius: 50%;
    -o-border-radius: 50%;
}

.dishes-thumb {
    position: relative;
    text-align: center;
}

.dishes-card.style2 .dishes-thumb .circle-shape {
    position: absolute;
    top: -7px;
    left: 50%;
    width: 100%;
    transform: translate(-50%);
    z-index: 1;
    -webkit-transform: translateX(-50%);
    -moz-transform: translateX(-50%);
    -ms-transform: translateX(-50%);
    -o-transform: translateX(-50%)
}

@keyframes cir36 {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}

.cir36 {
    animation: cir36 20s linear infinite;
    -webkit-animation: cir36 20s linear infinite;
}

.dishes-card.style2 .dishes-thumb .circle-shape {
    position: absolute;
    top: -7px;
    left: 50%;
    width: 100%;
    transform: translate(-50%);
    z-index: 1;
    -webkit-transform: translateX(-50%);
    -moz-transform: translateX(-50%);
    -ms-transform: translateX(-50%);
    -o-transform: translateX(-50%)
}

.dishes-card.style2 .dishes-content {
    margin-top: 24px
}

.dishes-card.style2 .dishes-content h3 {
    color: var(--title);
    font-family: var(--title);
    font-size: 20px;
    font-style: normal;
    font-weight: 700;
    line-height: 1;
    text-transform: capitalize;
    margin-bottom: 1px;
    transition: all .4s;
    -webkit-transition: all .4s;
    -moz-transition: all .4s;
    -ms-transition: all .4s;
    -o-transition: all .4s
}

.dishes-card.style2 .dishes-content h3:hover {
    color: var(--theme)
}

.dishes-card.style2 .dishes-content .text {
    color: var(--text);
    font-family: var(--body-font);
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: 1;
    text-transform: capitalize;
    margin-bottom: 16px;
    margin-top: 3px
}

.dishes-card.style2 .dishes-content h6 {
    color: var(--theme);
    font-family: var(--title-font);
    font-size: 18px;
    font-style: normal;
    font-weight: 700;
    line-height: 1;
    text-transform: capitalize;
    margin-bottom: 24px
}

.dishes-card.style3 {
    margin-top: 40px
}

.dishes-card.style3 .dishes-thumb {
    position: relative;
    transition: all .4s;
    -webkit-transition: all .4s;
    -moz-transition: all .4s;
    -ms-transition: all .4s;
    -o-transition: all .4s
}

.dishes-card.style3 .dishes-thumb img {
    position: relative;
    width: 100%;
    transition: all .4s;
    -webkit-transition: all .4s;
    -moz-transition: all .4s;
    -ms-transition: all .4s;
    -o-transition: all .4s
}

.title-area .title {
    color: var(--title);
    text-align: center;
    font-family: var(--title-font);
    font-size: 40px;
    font-style: normal;
    font-weight: 900;
    line-height: 50px;
    text-transform: capitalize;
    margin-bottom: 10px
}

.title-area .text {
    color: var(--text);
    text-align: center;
    font-family: var(--body-font);
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: 28px;
    margin-bottom: 45px
}
</style>

<template>
<MainLayout>
        <Head title="Welcome">
            <link rel="preconnect" href="https://rsms.me/" />
            <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
        </Head>
        <div class="flex min-h-screen flex-col items-center bg-[#FDFDFC] text-[#1b1b18] dark:bg-[#0a0a0a] lg:justify-center overflow-x-hidden">
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
                    </template>
                </nav>
            </header> -->
            <div class="duration-750 starting:opacity-0 flex w-full items-center justify-center opacity-100 transition-opacity lg:grow">
                <div class="relative w-full max-w-full">
                    <!-- Background image div -->
                    <div class="absolute inset-0 z-0">
                        <div class="w-full h-screen" style="background-image: linear-gradient(rgba(0, 0, 0, .7), rgba(0, 0, 0, .1)), url('/img/bg/bg-5.jpg');background-size: cover;background-position: center;background-repeat: no-repeat;"></div>
                    </div>

                    <!-- Content that goes over the background -->
                    <div class="relative z-10 pt-20"> <!-- Add padding-top to push content below navbar -->
                        <div class="relative min-h-screen flex flex-col selection:bg-[#FF2D20] selection:text-white">
                            <div class="relative w-full">
                                <!-- ========== HEADER ========== -->




                                <!-- ========== END HEADER ========== -->
                                <!-- Hero -->
                                <div class="relative overflow-hidden before:absolute before:top-0 before:start-1/2 before:bg-[url('https://preline.co/assets/svg/examples/polygon-bg-element.svg')] dark:before:bg-[url('https://preline.co/assets/svg/examples-dark/polygon-bg-element.svg')] before:bg-no-repeat before:bg-top before:bg-cover before:size-full before:-z-[1] before:transform before:-translate-x-1/2"></div>
                                
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
                                                        class="pr-3 pt-5 font-black great-vibes bg-clip-text bg-gradient-to-tl from-green-600 to-yellow-600 text-transparent text-3xl md:text-5xl lg:text-[70pt] dark:text-neutral-200">
                                                        Caribbean </h1>
                                                    <p v-motion-slide-visible-right :delay="200" :duration="600"
                                                        class="text-4xl md:text-6xl text-white knewave-regular">Smokehouse</p>

                                                </div>
                                                <!-- End Title -->

                                                <div class="mt-5 max-w-3xl text-center mx-auto px-4">
                                                    <p v-motion-slide-visible-bottom :delay="300" :duration="800"
                                                        class="text-2xl md:text-4xl text-yellow-400 great-vibes font-bold dark:text-neutral-400">
                                                        Come for the food, <span class="font-serif text-red-700">Stay</span> for
                                                        the <span class="font-serif text-green-700">vibes</span>!</p>
                                                </div>

                                                <!-- ======================================================== -->




                                                <!-- Buttons -->
                                                <div v-motion-slide-visible-bottom :delay="600" :duration="800"
                                                    class="mt-8 gap-3 flex justify-center px-4">
                                                    <a class="inline-flex justify-center items-center gap-x-3 text-center bg-gradient-to-tl from-green-600 to-yellow-600 hover:from-green-600 hover:to-yellow-600 border border-transparent text-white text-sm font-medium rounded-md focus:outline-none focus:ring-1 focus:ring-gray-600 py-3 px-6 md:px-4 dark:focus:ring-offset-gray-800"
                                                        href="#carousel">
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
                                        </div>
                                    </div>
                                </div>
                                <div class="text-gray-700 body-font">
                                    <div class="max-w-2xl px-4 md:px-10">

                                        <div class="flex flex-wrap -m-4 text-center">
                                            <div v-motion-slide-visible-right :delay="200" :duration="800"
                                                class="px-1 w-1/2 md:w-1/4">
                                                <div
                                                    class="cs-border-change border border-green-300 px-2 py-2 rounded-lg transform transition duration-500 hover:scale-110">
                                                    <svg fill="none" stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        class="text-green-500 w-8 h-8 md:w-12 md:h-12 mb-3 inline-block" viewBox="0 0 24 24">
                                                        <path d="M8 17l4 4 4-4m-4-5v9"></path>
                                                        <path d="M20.88 18.09A5 5 0 0018 9h-1.26A8 8 0 103 16.29"></path>
                                                    </svg>
                                                    <h2 class="title-font font-medium text-2xl md:text-3xl text-white">2.7K</h2>
                                                    <p class="leading-relaxed text-sm md:text-base">Downloads</p>
                                                </div>
                                            </div>
                                            <div v-motion-slide-visible-right :delay="400" :duration="800"
                                                class="px-1 w-1/2 md:w-1/4">
                                                <div
                                                    class="cs-border-change border border-green-400 px-2 py-2 rounded-lg transform transition duration-500 hover:scale-110">
                                                    <svg fill="none" stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        class="text-green-500 w-8 h-8 md:w-12 md:h-12 mb-3 inline-block" viewBox="0 0 24 24">
                                                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"></path>
                                                        <circle cx="9" cy="7" r="4"></circle>
                                                        <path d="M23 21v-2a4 4 0 00-3-3.87m-4-12a4 4 0 010 7.75"></path>
                                                    </svg>
                                                    <h2 class="title-font font-medium text-2xl md:text-3xl text-white">1.3K</h2>
                                                    <p class="leading-relaxed text-sm md:text-base">Users</p>
                                                </div>
                                            </div>
                                            <div v-motion-slide-visible-right :delay="600" :duration="800"
                                                class="px-1 w-1/2 md:w-1/4">
                                                <div
                                                    class="cs-border-change border border-green-300 px-2 py-2 rounded-lg transform transition duration-500 hover:scale-110">
                                                    <svg fill="none" stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        class="text-green-500 w-8 h-8 md:w-12 md:h-12 mb-3 inline-block" viewBox="0 0 24 24">
                                                        <path d="M3 18v-6a9 9 0 0118 0v6"></path>
                                                        <path
                                                            d="M21 19a2 2 0 01-2 2h-1a2 2 0 01-2-2v-3a2 2 0 012-2h3zM3 19a2 2 0 002 2h1a2 2 0 002-2v-3a2 2 0 00-2-2H3z">
                                                        </path>
                                                    </svg>
                                                    <h2 class="title-font font-medium text-2xl md:text-3xl text-white">74</h2>
                                                    <p class="leading-relaxed text-sm md:text-base">Files</p>
                                                </div>
                                            </div>
                                            <div v-motion-slide-visible-right :delay="800" :duration="800"
                                                class="px-1 w-1/2 md:w-1/4">
                                                <div
                                                    class="cs-border-change border border-green-300 px-2 py-2 rounded-lg transform transition duration-500 hover:scale-110">
                                                    <svg fill="none" stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        class="text-green-500 w-8 h-8 md:w-12 md:h-12 mb-3 inline-block" viewBox="0 0 24 24">
                                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                                    </svg>
                                                    <h2 class="title-font font-medium text-2xl md:text-3xl text-white">46</h2>
                                                    <p class="leading-relaxed text-sm md:text-base">Places</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="h-14.5 hidden lg:block"></div>
        </div>

        <!-- Card Section -->
        <div id="carousel" class="max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-12 mx-auto">
            <!-- Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-6">
                <!-- Card -->
                <a v-motion-slide-visible-bottom :delay="100" :duration="400"
                    class="group flex flex-col bg-white border shadow-sm rounded-xl hover:shadow-md transition"
                    href="#">
                    <div class="p-4 md:p-5">
                        <div class="flex items-center">
                            <svg class="mt-1 flex-shrink-0 size-28 p-4 bg-green-100 text-green-400 rounded-full dark:text-neutral-200 ease-out hover:translate-x-3 transition-all"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="green" stroke-width="1" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                            </svg>

                            <div class="grow ms-5">
                                <h1 class="group-hover:text-green-600 font-serif font-extrabold text-slate-800">
                                    Discount Voucher
                                </h1>
                                <p class="text-sm text-gray-500 dark:text-neutral-500">
                                    Get help from 40k+ Preline users
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
                <!-- End Card -->

                <!-- Card -->
                <a v-motion-slide-visible-bottom :delay="200" :duration="400"
                    class="group flex flex-col bg-white border shadow-sm rounded-xl hover:shadow-md transition"
                    href="#">
                    <div class="p-4 md:p-5">
                        <div class="flex items-center">
                            <svg class="mt-1 flex-shrink-0 size-28 rounded-full p-4 bg-green-100 text-green-400 dark:text-neutral-200 ease-out hover:translate-x-3 transition-all"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="green" stroke-width="1" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                                <path d="M12 17h.01" />
                            </svg>

                            <div class="grow ms-5">
                                <h1
                                    class="group-hover:text-green-600 font-serif text-lg font-extrabold text-slate-800">
                                    Fresh Healthy Food
                                </h1>
                                <p class="text-sm text-gray-500 dark:text-neutral-500">
                                    Just head to «Help» in the app
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
                <!-- End Card -->

                <!-- Card -->
                <a v-motion-slide-visible-bottom :delay="300" :duration="400"
                    class="group flex flex-col bg-white border shadow-sm rounded-xl hover:shadow-md transition"
                    href="#">
                    <div class="p-4 md:p-5">
                        <div class="flex items-center">
                            <svg class="mt-1 flex-shrink-0 size-28 rounded-full p-4 bg-green-100 text-green-400 dark:text-neutral-200 ease-out hover:translate-x-3 transition-all"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="green" stroke-width="1" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path
                                    d="M21.2 8.4c.5.38.8.97.8 1.6v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V10a2 2 0 0 1 .8-1.6l8-6a2 2 0 0 1 2.4 0l8 6Z" />
                                <path d="m22 10-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 10" />
                            </svg>

                            <div class="grow ms-5">
                                <h3 class="group-hover:text-green-600 font-serif font-extrabold text-slate-800">
                                    Email us
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-neutral-500">
                                    Reach us at <span
                                        class="text-blue-600 decoration-2 group-hover:underline font-medium dark:text-blue-500">info@hardballsmokehouse.co.uk</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
                <!-- End Card -->
            </div>
            <!-- End Grid -->
        </div>
        <!-- End Card Section -->

             <!-- ========== CAROUSEL ========== -->
            <section class="py-12 relative">

                <!-- <h1 class="mb-10 ml-10 lg:text-6xl great-vibes font-bold">Featured Menu</h1> -->
                <div class="title-area relative z-10 px-4">
                    <div class="flex justify-self-center text-center wow mb-7 font-bold text-orange-600 text-sm md:text-base" data-wow-delay="0.5s">
                        <img class="me-1 mx-auto w-6 md:w-auto" alt="icon" src="/img/icon/titleIcon.svg">CHEF'S SPECIAL DISHES
                        <img class="ms-1 w-6 md:w-auto" alt="icon" src="/img/icon/titleIcon.svg">
                    </div>
                    <h2 class="title wow fadeInUp text-2xl md:text-4xl" data-wow-delay="0.7s">Our Chef's Special Selection</h2>
                </div>
                <div class="absolute top-2 left-0 float-bob-y d-none d-xxl-block">
                    <img alt="shape" src="/img/shape/burger.png">
                </div>

                <Carousel :autoplay="3000" :wrap-around="true" v-bind="settings" :breakpoints="breakpoints">
                    <Slide v-for="item in featuredItems" :key="item.id" style="display: block;padding: 10px;">
                        <div class="m-2">
                            <div class="dishes-card style2 border border-orange-100" data-wow-delay="0.2s">
                                <div class="dishes-thumb mx-auto text-center">
                                    <img :src="getImageSource(item.image_path)" :alt="item.name" class="text-center mx-auto">
                                    <div class="circle-shape">
                                        <img class="cir36 mx-auto text-center" alt="shape" src="/img/shape/circleShape.png">
                                    </div>
                                </div>
                                <div class="dishes-content">
                                    <a href="/menu" data-discover="true">
                                        <h3>{{ item.name }}</h3>
                                    </a>
                                    <div class="star">
                                        <img class="text-center mx-auto" alt="icon" src="/img/icon/star2.svg">
                                    </div>
                                    <div class="text">{{ item.description || 'Delicious & Spicy' }}</div>
                                    <h6>£{{ item.price }}</h6>
                                    <a class="theme-btn style6" href="/menu" data-discover="true">
                                        Try Now <i class="bi bi-basket2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </Slide>

                    <template #addons>
                        <Pagination />
                    </template>
                </Carousel>
                <div class="absolute bottom-0 right-0 md:top-32 md:right-0 float-bob-x block md:d-none d-xxl-block">
                    <img alt="shape" class="w-32 md:w-52" src="/img/shape/pizza.png">
                </div>
            </section>
             <!-- ========== END CAROUSEL ========== -->
             <section class="relative bg-[#faf7f2] py-16">
  <div class="container mx-auto flex flex-col md:flex-row items-center gap-12">
    <!-- Left: Dessert List -->
    <div class="flex-1 w-full">
      <div class="flex items-center mb-6">
        <h2 class="text-3xl md:text-4xl font-extrabold text-[#e53935] tracking-wide mr-4">DESSERT ITEMS</h2>
        <span class="flex-1 border-t-2 border-[#e53935]"></span>
        <svg class="ml-2" width="40" height="10" viewBox="0 0 40 10" fill="none"><path d="M0 5h38m0 0l-4-4m4 4l-4 4" stroke="#e53935" stroke-width="2" stroke-linecap="round"/></svg>
      </div>
      <div class="flex gap-4 mb-8">
        <button @click="dessertSize = 'half'" :class="dessertSize === 'half' ? 'bg-gray-900 text-white' : 'bg-white text-gray-900'" class="px-6 py-2 border border-gray-300 rounded font-semibold transition">Half</button>
        <button @click="dessertSize = 'full'" :class="dessertSize === 'full' ? 'bg-gray-900 text-white' : 'bg-white text-gray-900'" class="px-6 py-2 border border-gray-300 rounded font-semibold transition">Full</button>
      </div>
      <ul>
        <li v-for="item in dessertItems" :key="item.name" class="flex items-center mb-8">
          <img :src="item.image" :alt="item.name" class="w-20 h-20 rounded-full object-cover border-4 border-white shadow-md mr-6" />
          <div class="flex-1">
            <div class="flex items-center">
              <span class="font-extrabold text-xl md:text-2xl text-gray-900 mr-2">{{ item.name }}</span>
              <span class="flex-1 border-t border-dashed border-gray-400 mx-2"></span>
              <span class="text-[#e53935] font-extrabold text-lg md:text-xl mr-2">${{ dessertSize === 'half' ? item.priceHalf.toFixed(2) : item.priceFull.toFixed(2) }}</span>
              <span class="text-[#e53935] font-extrabold text-lg md:text-xl" v-if="dessertSize === 'full'"> ${{ item.priceFull.toFixed(2) }}</span>
            </div>
            <div class="text-gray-500 text-sm italic">{{ item.ingredients }}</div>
            <div class="text-gray-400 text-xs mt-1">{{ item.note }}</div>
          </div>
        </li>
      </ul>
    </div>
    <!-- Right: Big Dessert Image -->
    <div class="flex-1 flex justify-center">
      <div class="relative">
        <img :src="dessertImage" alt="Dessert" class="w-80 h-80 object-cover rounded-full border-8 border-white shadow-lg" />
        <div class="absolute inset-0 rounded-full border-4 border-[#e53935]"></div>
      </div>
    </div>
  </div>
</section>

             <!-- ============================= PARALLAX ======================================================== -->
             <section
                class="flex flex-col w-full pb-20 h-auto md:h-[650px] bg-cover bg-fixed bg-center justify-center items-center overflow-hidden"
                style="background: linear-gradient(
                        rgba(0, 0, 0, 0.4),
                        rgba(0, 0, 0, 0.1)
                    ),
                    url('../img/bg6.jpg');
                background-size: cover;
                background-position: center;">
                <h1 class="text-white text-3xl md:text-5xl font-semibold my-10 md:my-20 mb-10">
                    Today's Special
                </h1>

                <div
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-8 mx-8 sm:mx-4 lg:mx-20 text-center lg:my-10 font-bold text-white/90">
                    <div v-for="item in featuredItems.slice(0, 4)" :key="item.id">
                        <div v-motion-slide-visible-bottom :delay="200" :duration="800"
                            class="bg-white rounded-md p-4 w-full">
                            <div class="pb-4">
                                <p class="text-xs text-left text-green-600">{{ item.category?.name }}</p>
                                <div class="flex justify-between">
                                    <p class="text-lg font-extrabold text-slate-800">{{ item.name }}</p>
                                    <p class="text-lg text-green-600 font-bold">£{{ item.price }}</p>
                                </div>
                            </div>
                            <div class="container flex justify-center">
                                <img :src="getImageSource(item.image_path)" :alt="item.name || 'Menu item'"
                                    class="img_card w-full h-48 object-cover rounded-md">
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- ============================= END PARALLAX ======================================================== -->


            <!-- ============================= MARQUEE ======================================================== -->
            <section class="relative overflow-hidden text-nowrap text-slider text-[30px] md:text-[60px] py-10 md:py-20">
                    <marquee class="marquee-inner to-left">
                        <ul class="marqee-list flex">
                            <li class="flex items-center style1 text-[#bcb8b1]">
                                <span class="text-slider"></span>
                                <div class="font-black title hover:text-green-500 hover:border-b-4 hover:leading-tight hover:border-green-500">Curry </div>
                                <img src="/img/shape/cutlery.png" alt="cutlery icon" class="w-4 h-4 md:w-8 md:h-8 mx-10 md:mx-20" />
                                <span class="font-black title tracking-tighter hover:text-red-500 hover:border-b-4 leading-tight hover:border-red-500">JERK CHICKEN</span>
                                <img src="/img/shape/cutlery.png" alt="cutlery icon" class="w-4 h-4 md:w-8 md:h-8 mx-10 md:mx-20" />
                                <span class="font-black title text-slider text-style">BURGER</span>
                                <img src="/img/shape/cutlery.png" alt="cutlery icon" class="w-4 h-4 md:w-8 md:h-8 mx-10 md:mx-20" />
                                <span class="font-black title text-slider text-style">Shrimp Pasta</span>
                                <img src="/img/shape/cutlery.png" alt="cutlery icon" class="w-4 h-4 md:w-8 md:h-8 mx-10 md:mx-20" />
                                <span class="font-black title text-slider text-style">Tasty Wings</span>
                                <img src="/img/shape/cutlery.png" alt="cutlery icon" class="w-4 h-4 md:w-8 md:h-8 mx-10 md:mx-20" />
                                <span class="font-black title text-slider text-style">ITALIANO FRENCH FRY</span>
                                <img src="/img/shape/cutlery.png" alt="cutlery icon" class="w-4 h-4 md:w-8 md:h-8 mx-10 md:mx-20" />
                                <span class="font-black title text-slider text-style">CHICKEN FRY</span>
                                <img src="/img/shape/cutlery.png" alt="cutlery icon" class="w-4 h-4 md:w-8 md:h-8 mx-10 md:mx-20" />
                                <span class="font-black title text-slider text-style">CHICKEN PATTY</span>
                                <img src="/img/shape/cutlery.png" alt="cutlery icon" class="w-4 h-4 md:w-8 md:h-8 mx-10 md:mx-20" />
                                <span class="font-black title text-slider text-style">GRILLED CHICKEN</span>
                                <span class="text-slider"></span>
                            </li>
                        </ul>
                    </marquee>
                </section>
   </MainLayout>

<!-- Dessert Items Section -->


</template>
