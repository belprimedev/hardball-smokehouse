<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import MainLayout from '@/layouts/MainLayout.vue';
import 'vue3-carousel/dist/carousel.css';
import { ref, onMounted, computed, watch } from 'vue';
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
    side_note?: string;
}

//import { Carousel, Slide, Navigation } from '...'; // Need to check the actual import paths, but in the original code, they are components, so assuming they are imported.

//import { useMotion } from '@vueuse/motion'; // Assuming useMotion is from VueUse Motion.

// Handling the name

defineOptions({

    name: 'App'

});

// motionConfig



// data properties

const featuredItems = ref<MenuItem[]>([]);

onMounted(async () => {
    try {
        const response = await axios.get<MenuItem[]>('/api/chef-special-items');
        featuredItems.value = response.data;
    } catch (error) {
        console.error('Error fetching chef special menu items:', error);
    }
});

const props = defineProps<{ dessertItems: any[]; menuItems: MenuItem[] }>();

const menuCategories = [
    { key: 'Starters', label: 'Starters' },
    { key: 'Jerk Dishes', label: 'Jerk Dishes' },
    { key: 'Curry Dishes', label: 'Curry Dishes' },
    { key: 'Meals', label: 'Meals' }
];
const selectedMenuCategory = ref('Starters');

const groupedMenuItems = computed(() => {
    if (!props.menuItems) return {};
    
    const groups: Record<string, any[]> = {};
    
    props.menuItems.forEach((item: any) => {
        if (!item.category) {
            return;
        }
        
        const cat = item.category.name;
        if (!groups[cat]) {
            groups[cat] = [];
        }
        groups[cat].push(item);
    });
    
    return groups;
});



watch(selectedMenuCategory, (val) => {
    console.log('Selected category:', val);
    console.log('Available categories:', Object.keys(groupedMenuItems.value));
    console.log('Items for category:', groupedMenuItems.value[val]);
    console.log('All menu items:', props.menuItems);
});

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

.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #23a04f;
    border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #1d8a42;
}

        @keyframes marquee {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-50%);
            }
        }

        @keyframes marquee-reverse {
            0% {
                transform: translateX(-50%);
            }
            100% {
                transform: translateX(0);
            }
        }

        .animate-marquee {
            animation: marquee 40s linear infinite;
        }

        .animate-marquee-reverse {
            animation: marquee-reverse 40s linear infinite;
        }

        .marquee-container {
            mask-image: linear-gradient(to right, transparent, black 20%, black 80%, transparent);
            -webkit-mask-image: linear-gradient(to right, transparent, black 20%, black 80%, transparent);
        }

        @keyframes blob {
            0% {
                transform: translate(0px, 0px) scale(1);
            }
            33% {
                transform: translate(30px, -50px) scale(1.1);
            }
            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }
            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }

        .animate-blob {
            animation: blob 7s infinite;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }

@keyframes slide-right {
    from { transform: translateX(-100%); }
    to { transform: translateX(100%); }
}

@keyframes slide-left {
    from { transform: translateX(100%); }
    to { transform: translateX(-100%); }
}

.animate-slide-right {
    animation: slide-right 3s linear infinite;
}

.animate-slide-left {
    animation: slide-left 3s linear infinite;
}
</style>
<template>
    <MainLayout>
        <!-- Sticky Book Table Button -->
        <div class="fixed bottom-4 sm:bottom-8 right-4 sm:right-8 z-50">
            <Link :href="route('make-reservation')" class="group bg-gradient-to-r from-green-600 to-yellow-600 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-full shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex items-center gap-2">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="hidden sm:inline">Book a Table</span>
                <span class="sm:hidden">Book</span>
                <span class="absolute -top-1 sm:-top-2 -right-1 sm:-right-2 bg-red-500 text-white text-xs px-1 sm:px-2 py-0.5 sm:py-1 rounded-full animate-pulse">Now Open</span>
            </Link>
        </div>

        <Head title="Welcome">
            <link rel="preconnect" href="https://rsms.me/" />
            <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
        </Head>
        <div class="flex min-h-screen flex-col items-center bg-[#FDFDFC] text-[#1b1b18] dark:bg-[#0a0a0a] lg:justify-center overflow-x-hidden">
            <div class="duration-750 starting:opacity-0 flex w-full items-center justify-center opacity-100 transition-opacity lg:grow">
                <div class="relative w-full max-w-full">
                    <!-- Video Background -->
                    <div class="absolute inset-0 z-0">
                        <div class="relative w-full h-screen overflow-hidden">
                            <!-- Video Background -->
                            <video 
                                class="absolute inset-0 w-full h-full object-cover"
                                autoplay 
                                loop 
                                muted 
                                playsinline
                                poster="/img/bg/bg-5.jpg">
                                <source src="/videos/hero-bg.mp4" type="video/mp4">
                            </video>
                            <!-- Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/50 to-black/30"></div>
                        </div>
                    </div>

                   

                    <!-- Content that goes over the background -->
                    <div class="relative z-10 pt-20">
                        <div class="relative min-h-screen flex flex-col selection:bg-[#FF2D20] selection:text-white">
                            <div class="relative w-full">
                                <!-- Hero Content -->
                                <div class="max-w-[85rem] mx-auto px-4 sm:px-6 lg:px-8 pt-20 sm:pt-24 md:pt-32 pb-16 sm:pb-20 md:pb-24">
                                    <!-- Announcement Banner -->
                                    <div class="flex justify-center">
                                        <p v-motion-slide-visible-top :delay="600" :duration="800"
                                            class="inline-flex items-center gap-x-2 bg-white/90 backdrop-blur-sm border border-gray-200 text-lg sm:text-xl md:text-2xl text-green-500 rubik p-1 px-4 sm:px-6 rounded-full transition hover:border-gray-300 dark:bg-neutral-800 dark:border-neutral-700 dark:hover:border-neutral-600 dark:text-neutral-200">
                                            <span class="relative flex h-2 w-2 sm:h-3 sm:w-3">
                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                                <span class="relative inline-flex rounded-full h-2 w-2 sm:h-3 sm:w-3 bg-green-500"></span>
                                            </span>
                                            HARDBALL
                                        </p>
                                    </div>

                                    <!-- Title -->
                                    <div class="max-w-2xl text-center mx-auto">
                                        <h1 v-motion-slide-visible-top :delay="300" :duration="800"
                                            class="pr-3 pt-3 sm:pt-5 font-black great-vibes bg-clip-text bg-gradient-to-tl from-green-600 to-yellow-600 text-transparent text-2xl sm:text-3xl md:text-5xl lg:text-6xl xl:text-[70pt] dark:text-neutral-200">
                                            Caribbean
                                        </h1>
                                        <p v-motion-slide-visible-right :delay="200" :duration="600"
                                            class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl xl:text-7xl text-white knewave-regular">
                                            Smokehouse
                                        </p>
                                    </div>

                                    <div class="mt-3 sm:mt-5 max-w-3xl text-center mx-auto px-4">
                                        <p v-motion-slide-visible-bottom :delay="300" :duration="800"
                                            class="text-lg sm:text-xl md:text-2xl lg:text-3xl xl:text-4xl text-yellow-400 great-vibes font-bold dark:text-neutral-400">
                                            Come for the food, <span class="font-serif text-red-700">Stay</span> for
                                            the <span class="font-serif text-green-700">vibes</span>!
                                        </p>
                                    </div>

                                    <!-- Quick Info -->
                                    <div class="mt-6 sm:mt-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4 max-w-3xl mx-auto">
                                        <div v-motion-slide-visible-bottom :delay="400" :duration="800"
                                            class="bg-white/10 backdrop-blur-sm rounded-xl p-3 sm:p-4 text-center">
                                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-yellow-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <p class="text-white font-semibold text-sm sm:text-base">Open Daily</p>
                                            <p class="text-gray-300 text-xs sm:text-sm">12:00pm</p>
                                        </div>
                                        <div v-motion-slide-visible-bottom :delay="500" :duration="800"
                                            class="bg-white/10 backdrop-blur-sm rounded-xl p-3 sm:p-4 text-center">
                                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-yellow-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <p class="text-white font-semibold text-sm sm:text-base">Location</p>
                                            <p class="text-gray-300 text-xs sm:text-sm">Ipswich, UK</p>
                                        </div>
                                        <div v-motion-slide-visible-bottom :delay="600" :duration="800"
                                            class="bg-white/10 backdrop-blur-sm rounded-xl p-3 sm:p-4 text-center sm:col-span-2 md:col-span-1">
                                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-yellow-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            <p class="text-white font-semibold text-sm sm:text-base">Contact</p>
                                            <p class="text-gray-300 text-xs sm:text-sm">+44 123 456 7890</p>
                                        </div>
                                    </div>

                                    <!-- Buttons -->
                                    <div v-motion-slide-visible-bottom :delay="600" :duration="800"
                                        class="mt-6 sm:mt-8 gap-2 sm:gap-3 flex flex-col sm:flex-row justify-center px-4">
                                        <a class="inline-flex justify-center items-center gap-x-2 sm:gap-x-3 text-center bg-gradient-to-tl from-green-600 to-yellow-600 hover:from-green-600 hover:to-yellow-600 border border-transparent text-white text-sm font-medium rounded-md focus:outline-none focus:ring-1 focus:ring-gray-600 py-2 sm:py-3 px-4 sm:px-6 dark:focus:ring-offset-gray-800"
                                            href="#carousel">
                                            Explore Menu
                                            <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="m9 18 6-6-6-6" />
                                            </svg>
                                        </a>
                                        <a class="inline-flex justify-center items-center gap-x-2 sm:gap-x-3 text-center bg-white/10 backdrop-blur-sm hover:bg-white/20 border border-white/20 text-white text-sm font-medium rounded-md focus:outline-none focus:ring-1 focus:ring-white/20 py-2 sm:py-3 px-4 sm:px-6"
                                            href="#events">
                                            View Events
                                            <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="M5 12h14M12 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="h-14.5x hidden lg:block"></div>
        </div>

        <!-- Card Section -->
        <section id="carousel" class="relative max-w-full px-4 sm:px-6 lg:px-8 lg:pb-32 lg:pt-10 mx-auto bg-gradient-to-b from-white to-green-50" v-motion-slide-visible-bottom :delay="200" :duration="400">
            <!-- Decorative elements -->
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute left-0 top-1/2 transform -translate-y-1/2">
                    <svg class="w-24 h-24 text-green-100 opacity-50" viewBox="0 0 100 100" fill="currentColor">
                        <path d="M95,50c0,24.9-20.1,45-45,45S5,74.9,5,50S25.1,5,50,5S95,25.1,95,50z"/>
                    </svg>
                </div>
                <div class="absolute right-0 bottom-0">
                    <svg class="w-32 h-32 text-yellow-100 opacity-50" viewBox="0 0 100 100" fill="currentColor">
                        <path d="M95,50c0,24.9-20.1,45-45,45S5,74.9,5,50S25.1,5,50,5S95,25.1,95,50z"/>
                    </svg>
                </div>
            </div>
           
            <!-- Section Header -->
            <div class="relative text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Experience Caribbean Excellence</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Discover what makes us Ipswich's premier destination for authentic Caribbean cuisine</p>
            </div>

            <!-- Grid -->
            <div class="relative grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                <!-- Voucher Card -->
                <div v-motion-slide-visible-bottom :delay="200" :duration="400"
                    class="group bg-white rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center mb-6">
                            <div class="flex-shrink-0 bg-gradient-to-br from-green-400 to-green-600 p-4 rounded-xl">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-xl font-bold text-gray-900 group-hover:text-green-600 transition-colors">Special Offers</h3>
                                <p class="text-gray-600">Exclusive deals for our valued customers</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="#" class="inline-flex items-center text-green-600 font-semibold hover:text-green-700">
                                View Offers
                                <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Fresh Food Card -->
                <div v-motion-slide-visible-bottom :delay="300" :duration="400"
                    class="group bg-white rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center mb-6">
                            <div class="flex-shrink-0 bg-gradient-to-br from-yellow-400 to-yellow-600 p-4 rounded-xl">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-xl font-bold text-gray-900 group-hover:text-yellow-600 transition-colors">Fresh Ingredients</h3>
                                <p class="text-gray-600">Quality Caribbean ingredients, locally sourced</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="#" class="inline-flex items-center text-yellow-600 font-semibold hover:text-yellow-700">
                                Learn More
                                <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Contact Card -->
                <div v-motion-slide-visible-bottom :delay="400" :duration="400"
                    class="group bg-white rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center mb-6">
                            <div class="flex-shrink-0 bg-gradient-to-br from-red-400 to-red-600 p-4 rounded-xl">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-xl font-bold text-gray-900 group-hover:text-red-600 transition-colors">Get in Touch</h3>
                                <p class="text-gray-600">Book your table or event with us</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="mailto:info@hardballsmokehouse.co.uk" class="inline-flex items-center text-red-600 font-semibold hover:text-red-700">
                                Contact Us
                                <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Card Section -->

        
        <!-- ============================= MENU & DISHES ======================================================== -->
        <section class="relative w-full min-h-[700px] flex flex-col md:flex-row bg-gradient-to-r from-[#23a04f]/90 to-[#23a04f]/80" v-motion-slide-visible-bottom :delay="200" :duration="400">
            <!-- Remove the green background from the card, make it white/transparent for contrast -->
            <div class="absolute inset-0 bg-gradient-to-br from-white via-[#e8f5e9] to-[#c8e6c9]">
                <div class="absolute inset-0 opacity-10" style="background-image: url('/img/shape/wave-pattern.svg'); background-size: 100px;"></div>
            </div>
            <!-- Image Section -->
            <div class="w-full md:w-1/3 flex flex-col items-center justify-center p-4 sm:p-8 z-10 relative shadow-2xl">
                <div class="relative w-full h-full max-w-md flex flex-col items-center md:items-start">
                    <div class="relative group my-auto items-center w-full flex justify-center">
                        <div class="absolute -inset-2 sm:-inset-4 bg-gradient-to-r from-[#23a04f] to-[#f9de47] rounded-xl blur-xl opacity-20 group-hover:opacity-40 transition-opacity duration-500"></div>
                        <img
                            src="/img/food/portrait5.JPG"
                            alt="Menu Dish"
                            class="rounded-xl shadow-lg w-full max-w-xs sm:max-w-full transform hover:scale-105 transition-transform duration-500"
                        />
                    </div>
                </div>
            </div>
            <!-- Menu Content Section -->
            <div class="w-full md:w-2/3 flex items-center justify-center min-h-[400px] md:min-h-[600px]">
                <div class="relative w-full p-0 sm:p-6 md:p-8 z-10 bg-white/90 shadow-xl mx-0 sm:mx-6 md:mx-0 border border-[#23a04f]/20">
                    <div class="mb-1 sm:mb-2 text-[#f9de47] font-bold uppercase tracking-wider flex items-center gap-2 text-xs sm:text-sm">
                        FOOD ITEMS
                        <span class="w-6 sm:w-8 h-0.5 bg-[#f9de47] inline-block"></span>
                    </div>
                    <h2 class="text-xl sm:text-2xl md:text-3xl font-extrabold mb-3 sm:mb-6 text-[#0c4149]">Starters & Main Dishes</h2>
                    <!-- Category Tabs -->
                    <div class="flex flex-wrap gap-2 mb-4 sm:mb-6">
                        <button v-for="cat in menuCategories" :key="cat.key" 
                            @click="selectedMenuCategory = cat.key"
                            :class="[
                                selectedMenuCategory === cat.key 
                                    ? 'bg-[#f9de47] text-[#0c4149] shadow-lg scale-105' 
                                    : 'bg-[#23a04f]/10 text-[#0c4149] hover:bg-[#23a04f]/20',
                                'px-3 py-1 sm:px-4 sm:py-2 rounded-full font-semibold text-xs sm:text-base transition-all duration-300 hover:shadow-md hover:scale-105'
                            ]">
                            {{ cat.label }}
                        </button>
                    </div>
                    <!-- Menu List -->
                    <div class="space-y-3 sm:space-y-4 max-h-[320px] sm:max-h-[400px] overflow-y-auto pr-2 sm:pr-4 custom-scrollbar">
                        <div v-if="groupedMenuItems[selectedMenuCategory] && groupedMenuItems[selectedMenuCategory].length > 0">
                            <div v-for="item in groupedMenuItems[selectedMenuCategory]" :key="item.id"
                                class="group bg-[#23a04f]/5 rounded-xl p-3 sm:p-4 hover:bg-[#23a04f]/10 transition-all duration-300 border border-[#23a04f]/10">
                                <div class="flex flex-col md:flex-row md:items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2">
                                            <span class="font-extrabold text-base sm:text-xl md:text-2xl text-[#0c4149] group-hover:text-[#f9de47] transition-colors">
                                                {{ item.name }}
                                            </span>
                                            <span v-if="item.is_chef_special" 
                                                class="px-2 py-1 bg-[#f9de47] text-[#0c4149] text-xs font-bold rounded-full animate-pulse">
                                                Chef's Special
                                            </span>
                                        </div>
                                        <div class="text-[#0c4149]/90 text-xs sm:text-sm italic mt-1">{{ item.description }}</div>
                                        <div class="text-[#0c4149]/70 text-xs mt-1" v-if="item.side_note">{{ item.side_note }}</div>
                                    </div>
                                    <div class="flex gap-2 sm:gap-4 mt-2 md:mt-0 md:ml-8">
                                        <span class="text-[#f9de47] font-extrabold text-base sm:text-lg md:text-xl">
                                            ${{ Number(item.price || 0).toFixed(2) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else class="text-center py-8">
                            <p class="text-[#0c4149]/70 text-sm">No items found for this category.</p>
                            <p class="text-[#0c4149]/50 text-xs mt-2">Available categories: {{ Object.keys(groupedMenuItems).join(', ') }}</p>
                        </div>
                    </div>
                    <!-- View Full Menu Link -->
                    <div class="mt-5 sm:mt-8 text-center">
                        <Link :href="route('menu')" 
                            class="inline-flex items-center gap-2 px-4 sm:px-6 py-2 sm:py-3 bg-[#f9de47] text-[#0c4149] font-bold rounded-full hover:bg-white transition-all duration-300 transform hover:scale-105 hover:shadow-lg group text-sm sm:text-base">
                            View Full Menu
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </Link>
                    </div>
                </div>
            </div>
        </section>
        <!-- ============================= END MENU & DISHES ======================================================== -->

        

        <!-- ============================= EVENTS ======================================================== -->
        <div id="events" class="relative max-w-full overflow-hidden" v-motion-slide-visible-bottom :delay="200" :duration="400">
            <!-- Background Pattern -->
            <div class="absolute inset-0 bg-[#0c4149] opacity-95">
                <!-- Base Pattern -->
                <div class="absolute inset-0" style="background-image: url('/img/shape/pattern-light.svg'); background-repeat: repeat; opacity: 0.2;"></div>
                
                <!-- Diagonal Lines Pattern -->
                <div class="absolute inset-0 opacity-15" 
                    style="background-image: repeating-linear-gradient(45deg, #f9de47 0, #f9de47 2px, transparent 0, transparent 50%),
                            repeating-linear-gradient(-45deg, #23a04f 0, #23a04f 2px, transparent 0, transparent 50%);
                    background-size: 40px 40px;">
                </div>

                <!-- Dots Pattern -->
                <div class="absolute inset-0 opacity-15" 
                    style="background-image: radial-gradient(#f9de47 2px, transparent 2px);
                    background-size: 30px 30px;">
                </div>

                <!-- Wave Pattern -->
                <div class="absolute inset-0 opacity-10"
                    style="background-image: url('/img/shape/wave-pattern.svg');
                    background-size: 100px;">
                </div>

                <!-- Gradient Overlay -->
                <div class="absolute inset-0 bg-gradient-to-b from-[#13292c]/90 via-[#001b1f]/80 to-[#052c32]/80"></div>
            </div>

            <!-- Content Container -->
            <div class="relative container mx-auto px-4 py-24">
                <!-- Section Header -->
                <div class="text-center mb-16">
                    <h2 class="text-4xl md:text-5xl font-bold text-white mb-4">Experience the Vibe</h2>
                    <p class="text-xl text-green-300 max-w-2xl mx-auto">Join us for live music, special events, and the best Caribbean atmosphere in Ipswich</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Left Column: Featured Event -->
                    <div class="relative group">
                        <div class="relative overflow-hidden rounded-3xl">
                            <img src="/img/event/dawn-penn.jpg" alt="Dawn Penn Event" 
                                 class="w-full h-[600px] object-cove transform group-hover:scale-110 transition-transform duration-700" style="background-position: top;" />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-8">
                                <span class="inline-block px-4 py-2 bg-red-500 text-white rounded-full text-sm font-bold mb-4">FEATURED EVENT</span>
                                <h3 class="text-3xl font-bold text-white mb-2">Dawn Penn Live</h3>
                                <p class="text-gray-300 mb-4">Experience the legendary voice behind "No, No, No" live at Hardball</p>
                                <!-- <button class="bg-white text-[#0c4149] px-6 py-3 rounded-full font-bold hover:bg-green-500 hover:text-white transition-colors">
                                    Book Now
                                </button> -->
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Stacked Cards -->
                    <div class="space-y-8">
                        <!-- Cocktail Card -->
                        <Link :href="route('cocktail')" 
                              class="block relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#c02523] to-[#e53935] group">
                            <div class="relative p-8">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="text-2xl font-bold text-white mb-2">SIGNATURE<br/>COCKTAILS</h3>
                                        <p class="text-red-100 mb-4">Discover our unique Caribbean-inspired cocktails</p>
                                        <button class="inline-flex items-center px-6 py-3 bg-white text-red-600 rounded-full font-bold group-hover:bg-red-100 transition-colors">
                                            View Menu
                                            <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <img src="/img/beverages/cocktail1.png" alt="Signature Cocktails" 
                                         class="absolute bottom-0 right-0 w-48 h-48 object-contain transform group-hover:scale-110 transition-transform duration-500"/>
                                </div>
                            </div>
                        </Link>

                        <!-- Gallery Card -->
                        <Link :href="route('gallery')" 
                              class="block relative overflow-hidden rounded-3xl bg-[#ffd600] group">
                            <div class="relative p-8">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="text-2xl font-bold text-[#0b2341] mb-2">EXPLORE OUR<br/>GALLERY</h3>
                                        <p class="text-[#0b2341]/80 mb-4">Take a visual journey through our Caribbean experience</p>
                                        <button class="inline-flex items-center px-6 py-3 bg-[#0c534d] text-white rounded-full font-bold group-hover:bg-[#23395d] transition-colors">
                                            View Gallery
                                            <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="absolute right-0 bottom-0 grid grid-cols-2 gap-1 transform translate-x-8 translate-y-8 rotate-12 group-hover:rotate-6 transition-transform duration-500">
                                        <img src="/img/gallery/store4.JPG" alt="Gallery Preview" class="w-24 h-24 object-cover rounded-lg shadow-lg"/>
                                        <img src="/img/gallery/store8.jpg" alt="Gallery Preview" class="w-24 h-24 object-cover rounded-lg shadow-lg"/>
                                        <img src="/img/gallery/event1.jpg" alt="Gallery Preview" class="w-24 h-24 object-cover rounded-lg shadow-lg"/>
                                        <img src="/img/gallery/food1.jpg" alt="Gallery Preview" class="w-24 h-24 object-cover rounded-lg shadow-lg"/>
                                    </div>
                                </div>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
        <!-- ============================= END EVENTS ======================================================== -->


        <!-- ============================= Dessert ======================================================== -->
        <section class="relative bg-gradient-to-b from-white to-green-50 py-28 overflow-hidden" v-motion-slide-visible-bottom :delay="200" :duration="400">
            <!-- Decorative Elements -->
            <div class="absolute inset-0 overflow-hidden">
                <!-- Floating Elements -->
                <div class="absolute top-1/4 left-10 w-32 h-32 bg-yellow-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
                <div class="absolute top-1/3 right-10 w-32 h-32 bg-green-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
                <div class="absolute bottom-1/4 left-1/2 w-32 h-32 bg-red-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>
                
                <!-- Caribbean Pattern -->
                <div class="absolute inset-0 opacity-5" style="background-image: url('/img/shape/caribbean-pattern.svg'); background-repeat: repeat;"></div>
            </div>

            <div class="container mx-auto flex flex-col md:flex-row items-center gap-12 relative z-10">
                <!-- Left: Dessert List -->
                <div class="flex-1 w-full">
                    <div class="flex items-center mb-6">
                        <h2 class="text-3xl md:text-4xl font-extrabold text-[#ffd600] tracking-wide mr-4">DESSERT ITEMS</h2>
                        <span class="flex-1 border-t-2 border-[#ffd600]"></span>
                        <svg class="ml-2" width="40" height="10" viewBox="0 0 40 10" fill="none">
                            <path d="M0 5h38m0 0l-4-4m4 4l-4 4" stroke="#ffd600" stroke-width="2" stroke-linecap="round" />
                        </svg>
                    </div>

                    <!-- Dessert Cards -->
                    <div class="space-y-6">
                        <div v-for="(item, index) in props.dessertItems" :key="item.id" 
                            v-motion-slide-visible-bottom 
                            :delay="200 * index" 
                            :duration="800"
                            class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                            <div class="p-6 flex items-center gap-6">
                                <!-- Dessert Image -->
                                <div class="relative">
                                    <div class="absolute -inset-2 bg-gradient-to-r from-yellow-400 to-red-400 rounded-full blur opacity-20 group-hover:opacity-40 transition-opacity"></div>
                                    <img :src="item.image_path ? '/storage/' + item.image_path : '/img/desserts/default.jpg'" 
                                        :alt="item.name" 
                                        class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-md relative z-10 transform group-hover:scale-110 transition-transform duration-300" />
                                </div>

                                <!-- Dessert Info -->
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-extrabold text-xl md:text-2xl text-gray-900 group-hover:text-[#ffd600] transition-colors">
                                                {{ item.name }}
                                            </h3>
                                            <p class="text-gray-500 text-sm italic mt-1">{{ item.description }}</p>
                                            <p v-if="item.note" class="text-gray-400 text-xs mt-1">{{ item.note }}</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-[#ffd600] font-extrabold text-lg md:text-xl">
                                                ${{ Number(item.price || 0).toFixed(2) }}
                                            </span>
                                            <!-- Popular Badge -->
                                            <div v-if="index === 0" class="mt-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732l-3.354 1.935-1.18 4.455a1 1 0 01-1.933 0L9.854 12.8 6.5 10.866a1 1 0 010-1.732l3.354-1.935 1.18-4.455A1 1 0 0112 2z" clip-rule="evenodd" />
                                                    </svg>
                                                    Most Popular
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Big Dessert Image -->
                <div class="flex-1 flex justify-center relative">
                    <!-- Parallax Container -->
                    <div class="relative w-96 h-96">
                        <!-- Decorative Elements -->
                        <div class="absolute inset-0 bg-gradient-to-r from-yellow-400 to-red-400 rounded-full blur-3xl opacity-20 animate-pulse"></div>
                        
                        <!-- Main Image Container -->
                        <div class="relative w-full h-full">
                            <!-- Rotating Border -->
                            <div class="absolute inset-0 rounded-full border-8 border-[#ffd600] animate-spin-slow"></div>
                            
                            <!-- Main Image -->
                            <img
                                src="/img/food/White-Chocolate-Cheesecake.png"
                                alt="Dessert"
                                class="w-full h-full object-cover rounded-full border-8 border-white shadow-lg relative z-10 transform hover:scale-105 transition-transform duration-500"
                            />
                            
                            <!-- Floating Elements -->
                            <div class="absolute -top-4 -right-4 w-16 h-16 bg-yellow-400 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
                            <div class="absolute -bottom-4 -left-4 w-16 h-16 bg-red-400 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- ============================= END DESSERT ======================================================== -->
         
         <!-- Marquee as transition element -->
        <div class="relative overflow-hidden py-16 bg-[#0c4149]" v-motion-slide-visible-bottom :delay="200" :duration="400">
            <!-- Background Elements -->
            <div class="absolute inset-0">
                <!-- Dark gradient overlay -->
                <div class="absolute inset-0 bg-gradient-to-b from-black/30 via-transparent to-black/30"></div>
                
                <!-- Geometric Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <div class="h-full w-full" 
                        style="background-image: repeating-linear-gradient(45deg, #f9de47 0, #f9de47 1px, transparent 0, transparent 50%),
                                repeating-linear-gradient(-45deg, #23a04f 0, #23a04f 1px, transparent 0, transparent 50%);
                        background-size: 40px 40px;"></div>
                </div>

                <!-- Animated lines -->
                <div class="absolute inset-0">
                    <div class="absolute top-0 left-0 w-full h-[2px] bg-gradient-to-r from-transparent via-[#f9de47]/30 to-transparent animate-slide-right"></div>
                    <div class="absolute bottom-0 left-0 w-full h-[2px] bg-gradient-to-r from-transparent via-[#f9de47]/30 to-transparent animate-slide-left"></div>
                </div>

                <!-- Radial Gradient Overlay -->
                <div class="absolute inset-0 bg-[#0c4149] opacity-30"
                    style="background-image: radial-gradient(circle at center, transparent 0%, #0c4149 70%);"></div>
            </div>

            <!-- Content -->
            <div class="relative z-10">
                <!-- Top Marquee -->
                <div class="relative marquee-container mb-8">
                    <div class="flex space-x-4 animate-marquee whitespace-nowrap">
                        <div v-for="(item, index) in ['Curry', 'Jerk Chicken', 'Burger', 'Shrimp Pasta', 'Tasty Wings', 'French Fry', 'Chicken Fry', 'Chicken Patty', 'Grilled Chicken']" 
                            :key="index" 
                            class="group inline-flex items-center">
                            <span class="text-4xl md:text-6xl font-black text-white/90 hover:text-[#f9de47] transition-colors duration-300 cursor-pointer transform hover:scale-105">
                                {{ item }}
                            </span>
                            <img src="/img/shape/cutlery.png" alt="cutlery icon"
                                class="w-8 h-8 md:w-12 md:h-12 mx-8 md:mx-12 opacity-40 group-hover:opacity-100 transition-all duration-300 transform rotate-12 group-hover:rotate-45 filter brightness-0 invert" />
                        </div>
                    </div>
                </div>

                <!-- Bottom Marquee (Reverse Direction) -->
                <div class="relative marquee-container">
                    <div class="flex space-x-4 animate-marquee-reverse whitespace-nowrap">
                        <div v-for="(item, index) in ['Caribbean Style', 'Island Flavors', 'Spicy Hot', 'Fresh Ingredients', 'Home Made', 'Traditional', 'Authentic', 'Family Recipe']" 
                            :key="index" 
                            class="group inline-flex items-center">
                            <span class="text-3xl md:text-5xl font-black text-[#f9de47]/80 hover:text-white transition-colors duration-300 cursor-pointer transform hover:scale-105">
                                {{ item }}
                            </span>
                            <img src="/img/icon/palm-tree.webp" alt="palm tree icon"
                                class="w-8 h-8 md:w-12 md:h-12 mx-8 md:mx-12 opacity-40 group-hover:opacity-100 transition-all duration-300 transform -rotate-12 group-hover:-rotate-45 filter brightness-0 invert" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Social Proof Section -->
        <section class="relative py-16 bg-white" v-motion-slide-visible-bottom :delay="200" :duration="400">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-5">
                <div class="h-full w-full" style="background-image: url('/img/shape/caribbean-pattern.svg'); background-repeat: repeat;"></div>
            </div>

            <div class="container mx-auto px-4">
                <!-- Trust Indicators -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-16">
                    <div class="text-center">
                        <div class="text-4xl font-bold text-[#23a04f] mb-2">4.9</div>
                        <div class="flex justify-center mb-2">
                            <svg v-for="i in 5" :key="i" class="w-5 h-5 text-[#f9de47]" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        </div>
                        <div class="text-gray-800">Google Reviews</div>
                        <div class="text-gray-500 text-sm">(200+ reviews)</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-[#23a04f] mb-2">12K+</div>
                        <div class="text-gray-800">Happy Customers</div>
                        <div class="text-gray-500 text-sm">Monthly</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-[#23a04f] mb-2">96%</div>
                        <div class="text-gray-800">Return Rate</div>
                        <div class="text-gray-500 text-sm">Customer Loyalty</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-[#23a04f] mb-2">#1</div>
                        <div class="text-gray-800">Caribbean Restaurant</div>
                        <div class="text-gray-500 text-sm">in Ipswich</div>
                    </div>
                </div>

                <!-- Testimonials -->
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">What Our Customers Say</h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">Real feedback from our valued customers who have experienced the authentic taste of Caribbean cuisine at Hardball.</p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <div v-for="(testimonial, index) in [
                        {
                            name: 'Sarah Thompson',
                            role: 'Food Blogger',
                            image: '/img/icon/palm-tree.webp',
                            text: 'The jerk chicken here is absolutely incredible! The flavors are authentic and the atmosphere is perfect. A must-visit spot in Ipswich!',
                            rating: 5
                        },
                        {
                            name: 'Michael Chen',
                            role: 'Local Guide',
                            image: '/img/icon/palm-tree.webp',
                            text: 'Best Caribbean food I\'ve had outside of Jamaica. The staff is friendly and the portions are generous. Their curry goat is outstanding!',
                            rating: 5
                        },
                        {
                            name: 'Emma Williams',
                            role: 'Regular Customer',
                            image: '/img/icon/palm-tree.webp',
                            text: 'Love the vibrant atmosphere and amazing cocktails. The plantain chips are addictive and the service is always top-notch!',
                            rating: 5
                        }
                    ]" :key="index"
                    class="bg-gray-50 p-6 rounded-xl hover:bg-gray-100 transition-all duration-300 group border border-gray-100 hover:border-[#23a04f]/20 shadow-sm hover:shadow-md">
                        <div class="flex items-center mb-4">
                            <img :src="testimonial.image" :alt="testimonial.name" class="w-12 h-12 rounded-full object-cover mr-4 ring-2 ring-green-600" />
                            <div>
                                <div class="font-semibold text-gray-900 group-hover:text-[#23a04f] transition-colors">{{ testimonial.name }}</div>
                                <div class="text-gray-500 text-sm">{{ testimonial.role }}</div>
                            </div>
                        </div>
                        <div class="flex mb-3">
                            <svg v-for="i in testimonial.rating" :key="i" class="w-5 h-5 text-[#f9de47]" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        </div>
                        <p class="text-gray-600 italic">{{ testimonial.text }}</p>
                    </div>
                </div>

                <!-- Trust Badges -->
                <div class="mt-16 flex flex-wrap justify-center items-center gap-8">
                    <img src="/img/icon/TripAdvisor-Logo-SVG_001.svg" alt="TripAdvisor Choice" class="h-16 opacity-80 hover:opacity-100 transition-opacity" />
                    <img src="/img/icon/Google_Review.png" alt="Google Reviews" class="h-16 opacity-80 hover:opacity-100 transition-opacity" />
                    <img src="/img/icon/Just-Eat-Logo.svg" alt="Just Eat" class="h-16 opacity-80 hover:opacity-100 transition-opacity" />
                    <img src="/img/icon/Deliveroo-Logo.svg" alt="Deliveroo" class="h-16 opacity-80 hover:opacity-100 transition-opacity" />
                </div>
            </div>
        </section>
    </MainLayout>

    <!-- Dessert Items Section -->



</template>
