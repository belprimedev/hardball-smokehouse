<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import MainLayout from '@/layouts/MainLayout.vue';
import 'vue3-carousel/dist/carousel.css';
import { ref, onMounted, computed, watch, onUnmounted } from 'vue';
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

interface MenuCategory {
    key: string;
    label: string;
    count: number;
    display_image: string | null;
}

defineOptions({
    name: 'App'
});

// Data properties
const featuredItems = ref<MenuItem[]>([]);
const email = ref('');
const newsletterSubmitted = ref(false);
const selectedFAQ = ref<number | null>(null);

onMounted(async () => {
    try {
        const response = await axios.get<MenuItem[]>('/api/chef-special-items');
        featuredItems.value = response.data;
    } catch (error) {
        console.error('Error fetching chef special menu items:', error);
    }
});

const props = defineProps<{ dessertItems: any[]; menuItems: MenuItem[] }>();

const menuCategories = ref<MenuCategory[]>([]);
const selectedMenuCategory = ref('Starters');

// Fetch menu categories from backend
onMounted(async () => {
    try {
        const response = await axios.get('/api/menu-categories');

        // Filter to only show the 4 specific categories we want
        const desiredCategories = ['Starters', 'Jerk Dishes', 'Curry Dishes', 'Meals'];

        menuCategories.value = response.data
            .filter((category: any) => desiredCategories.includes(category.name))
            .map((category: any) => ({
                key: category.name,
                label: category.name,
                count: category.menu_items_count || 0,
                display_image: category.display_image
            }));

        // Set first category as selected if available
        if (menuCategories.value.length > 0) {
            selectedMenuCategory.value = menuCategories.value[0].key;
        }
    } catch {
        // Fallback to hardcoded data if API fails
        menuCategories.value = [
            { key: 'Starters', label: 'Starters', count: 8, display_image: null },
            { key: 'Jerk Dishes', label: 'Jerk Dishes', count: 12, display_image: null },
            { key: 'Curry Dishes', label: 'Curry Dishes', count: 10, display_image: null },
            { key: 'Meals', label: 'Meals', count: 15, display_image: null }
        ];
    }
});



// Newsletter submission
const submitNewsletter = async () => {
    if (!email.value || !email.value.includes('@')) {
        return;
    }

    try {
        const response = await axios.post('/api/newsletters/subscribe', {
            email: email.value,
            source: 'website'
        });

        if (response.data.success) {
            newsletterSubmitted.value = true;
            email.value = '';
            setTimeout(() => {
                newsletterSubmitted.value = false;
            }, 5000);
        }
    } catch (error: any) {
        console.error('Newsletter subscription error:', error);
        // Handle error response
        if (error.response?.data?.message) {
            alert(error.response.data.message);
        } else {
            alert('Failed to subscribe. Please try again.');
        }
    }
};

// FAQ toggle
const toggleFAQ = (index: number) => {
    selectedFAQ.value = selectedFAQ.value === index ? null : index;
};

// Carousel functionality
const currentSlide = ref(0);
const isDragging = ref(false);
const startX = ref(0);
const currentX = ref(0);
const isAutoPlaying = ref(true);
const dragOffset = ref(0);

// Create infinite loop by duplicating testimonials
const infiniteTestimonials = computed(() => {
    return [...testimonials, ...testimonials, ...testimonials];
});

const nextSlide = () => {
    currentSlide.value++;
    // Reset to middle set when reaching the end
    if (currentSlide.value >= testimonials.length) {
        currentSlide.value = 0;
    }
};

const prevSlide = () => {
    currentSlide.value--;
    // Reset to middle set when reaching the beginning
    if (currentSlide.value < 0) {
        currentSlide.value = testimonials.length - 1;
    }
};

const goToSlide = (index: number) => {
    currentSlide.value = index;
};

const toggleAutoPlay = () => {
    isAutoPlaying.value = !isAutoPlaying.value;
    if (isAutoPlaying.value) {
        startAutoPlay();
    } else {
        stopAutoPlay();
    }
};

const startAutoPlay = () => {
    autoPlayInterval = setInterval(() => {
        nextSlide();
    }, 2000); // Original timing
};

const stopAutoPlay = () => {
    if (autoPlayInterval) {
        clearInterval(autoPlayInterval);
    }
};

const startDrag = (event: MouseEvent | TouchEvent) => {
    isDragging.value = true;
    const clientX = 'touches' in event ? event.touches[0].clientX : event.clientX;
    startX.value = clientX;
    currentX.value = clientX;
    dragOffset.value = 0;
    
    // Pause auto-play during drag
    stopAutoPlay();
};

const onDrag = (event: MouseEvent | TouchEvent) => {
    if (!isDragging.value) return;
    const clientX = 'touches' in event ? event.touches[0].clientX : event.clientX;
    currentX.value = clientX;
    dragOffset.value = startX.value - clientX;
};

const endDrag = () => {
    if (!isDragging.value) return;
    isDragging.value = false;
    
    const diff = startX.value - currentX.value;
    const threshold = 50;
    
    if (Math.abs(diff) > threshold) {
        if (diff > 0) {
            nextSlide();
        } else {
            prevSlide();
        }
    }
    
    // Resume auto-play if it was enabled
    if (isAutoPlaying.value) {
        startAutoPlay();
    }
};

// Auto-play carousel
let autoPlayInterval: number;

// Newsletter image carousel
const currentImageIndex = ref(0);
let imageCarouselInterval: number;

const nextImage = () => {
    currentImageIndex.value = (currentImageIndex.value + 1) % 3;
};

const startImageCarousel = () => {
    imageCarouselInterval = setInterval(() => {
        nextImage();
    }, 3000); // Change image every 3 seconds
};

const stopImageCarousel = () => {
    if (imageCarouselInterval) {
        clearInterval(imageCarouselInterval);
    }
};

onMounted(() => {
    // Start auto-play
    startAutoPlay();
    // Start image carousel
    startImageCarousel();
});

onUnmounted(() => {
    // Clean up auto-play
    stopAutoPlay();
    // Clean up image carousel
    stopImageCarousel();
});

// Featured menu items (top 6)
const featuredMenuItems = computed(() => {
    return props.menuItems?.slice(0, 6) || [];
});

// Testimonials
const testimonials = [
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
    },
    {
        name: 'David Rodriguez',
        role: 'Business Owner',
        image: '/img/icon/palm-tree.webp',
        text: 'Perfect for corporate events! The catering service is professional and the food quality is consistently excellent. Highly recommend!',
        rating: 5
    },
    {
        name: 'Lisa Johnson',
        role: 'Event Planner',
        image: '/img/icon/palm-tree.webp',
        text: 'I\'ve booked Hardball for multiple events and they never disappoint. The jerk pork is a crowd favorite and the presentation is always beautiful.',
        rating: 5
    },
    {
        name: 'James Wilson',
        role: 'Local Resident',
        image: '/img/icon/palm-tree.webp',
        text: 'Been coming here for years! The oxtail stew is my favorite - tender, flavorful, and reminds me of my grandmother\'s cooking.',
        rating: 5
    },
    {
        name: 'Maria Garcia',
        role: 'Food Enthusiast',
        image: '/img/icon/palm-tree.webp',
        text: 'The curry chicken is absolutely divine! Perfect blend of spices and the rice and peas are cooked to perfection. A true taste of the Caribbean!',
        rating: 5
    },
    {
        name: 'Robert Taylor',
        role: 'Traveler',
        image: '/img/icon/palm-tree.webp',
        text: 'Found this gem while visiting Ipswich. The atmosphere is so welcoming and the food is authentic Caribbean. The rum punch is a must-try!',
        rating: 5
    }
];

// FAQ items
const faqItems = [
    {
        question: 'Do you offer vegetarian options?',
        answer: 'Yes! We have a variety of vegetarian dishes including our popular curry vegetables, plantain chips, and fresh salads.'
    },
    {
        question: 'Can I make a reservation for a large group?',
        answer: 'Absolutely! We welcome large groups and can accommodate up to 20 people. Please call us in advance to make arrangements.'
    },
    {
        question: 'Do you offer delivery services?',
        answer: 'Yes, we partner with Just Eat and Deliveroo for delivery services. You can also call us directly for takeaway orders.'
    },
    {
        question: 'What are your opening hours?',
        answer: 'We are open daily from 12:00 PM to 11:00 PM. On weekends, we stay open until midnight.'
    }
];

// Promotions
const promotions = [
    {
        title: 'SIGNATURE COCKTAILS',
        subtitle: ' _',
        description: 'Discover our unique Caribbean-inspired cocktails',
        image: '/img/gallery/portrait9.jpg',
        cta: 'View Menu',
        color: 'from-gray-800 to-green-700'
    },
    {
        title: 'EXPLORE OUR GALLERY',
        subtitle: ' _',
        description: 'Take a visual journey through our Caribbean experience',
        image: '/img/gallery/store2.JPG',
        cta: 'See Gallery',
        color: 'from-yellow-500 to-gray-800'
    }
];

watch(selectedMenuCategory, () => {
    // Category selection changed
});

onMounted(() => {
    // Scroll animation for cards
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                const target = entry.target as HTMLElement;
                target.style.transform = 'translateX(0)';
                target.style.opacity = '1';
            }
        });
    }, observerOptions);

    // Observe all cards
    const cards = document.querySelectorAll('[data-aos="fade-right"]');
    cards.forEach((card, index) => {
        const cardElement = card as HTMLElement;
        // Set initial state - slide in from right
        cardElement.style.transition = 'transform 0.8s ease-out, opacity 0.8s ease-out';
        cardElement.style.transform = 'translateX(100%)';
        cardElement.style.opacity = '0';
        
        // Add delay based on index
        setTimeout(() => {
            observer.observe(card);
        }, index * 200);
    });
});
</script>

<style scoped>
@keyframes scroll {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-33.333%);
    }
}

.animate-scroll {
    animation: scroll 1.5s linear infinite;
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
    animation: marquee-reverse 50s linear infinite;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-100%);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(100%);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.fade-in-up {
    animation: fadeInUp 0.6s ease-out;
}

.slide-in-left {
    animation: slideInLeft 0.8s ease-out;
}

.slide-in-right {
    animation: slideInRight 0.8s ease-out;
}

.hover-lift {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

/* Custom colors */
.bg-dark-900 {
    background-color: #0A1B2A;
}

.bg-light-cream {
    background-color: #FAF5D0;
}

.text-dark-900 {
    color: #0A1B2A;
}

/* Custom fonts */
.knewave-regular {
    font-family: 'Knewave', cursive;
}

/* Responsive text sizes */
@media (max-width: 640px) {
    .text-4xl {
        font-size: 1.875rem;
    }
    .text-5xl {
        font-size: 2.25rem;
    }
}
</style>

<template>
    <MainLayout>

        <Head title="Welcome">
            <link rel="preconnect" href="https://rsms.me/" />
            <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
        </Head>

        <!-- Hero Section -->
        <section class="relative min-h-screen bg-gradient-to-br from-dark-900 via-green-900 to-dark-900 text-white overflow-hidden">
            <!-- Animated Background Pattern -->
            <div class="absolute inset-0 z-0">
                <div class="absolute inset-0 bg-[url('/img/bg/bg-5.jpg')] bg-cove bg-center bg-no-repeat op"></div>
                <div class="absolute inset-0 bg-gradient-to-br from-dark-900 via-green-900 to-gray-900"></div>
                
                <!-- Floating Caribbean Elements -->
                <div class="absolute top-20 left-10 w-24 h-24 bg-yellow-400/20 rounded-full animate-pulse animation-delay-3000"></div>
                <div class="absolute top-40 right-20 w-16 h-16 bg-accent-red/20 rounded-full animate-pulse"></div>
                <div class="absolute bottom-40 left-1/4 w-20 h-20 bg-green-400/20 rounded-full animate-bounce animation-delay-2000"></div>
                <div class="absolute bottom-20 right-1/3 w-12 h-12 bg-yellow-400/20 rounded-full animate-pulse"></div>
                
                <!-- Diagonal Lines Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-yellow-400 to-transparent transform rotate-12"></div>
                    <div class="absolute top-20 left-0 w-full h-px bg-gradient-to-r from-transparent via-accent-red to-transparent transform -rotate-6"></div>
                    <div class="absolute bottom-20 left-0 w-full h-px bg-gradient-to-r from-transparent via-green-400 to-transparent transform rotate-3"></div>
                </div>
            </div>

            <!-- Content Container -->
            <div class="relative z-10 container mx-auto px-4 py-20">
                <div class="grid grid-cols-1 lg:grid-cols-2 items-center min-h-screen gap-12">
                    
                    <!-- Left Side - Text Content -->
                    <div class="text-center lg:text-left space-y-8 animate-slide-in-left">
                        
                       

                        <!-- Main Headlines -->
                        <div class="space-y-4">
                            <h1 class="text-6xl lg:text-8xl font-black text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 via-white to-accent-red animate-pulse-slow">
                                HARDBALL
                            </h1>
                            <h2 class="text-4xl lg:text-6xl knewave-regular font-bold text-white mb-4">
                                Caribbean Smokehouse
                            </h2>
                        </div>

                        <!-- Subtitle with Enhanced Typography -->
                        <div class="space-y-4">
                            <p class="text-xl lg:text-2xl text-gray-200 leading-relaxed max-w-2xl mx-auto lg:mx-0">
                                Come for the <span class="text-yellow-400 font-bold">food</span>, 
                                <span class="text-white font-bold">Stay</span> for the 
                                <span class="text-accent-red font-bold">vibes</span>!
                            </p>
                            <p class="text-lg text-gray-300 max-w-xl mx-auto lg:mx-0">
                                Experience authentic Caribbean flavors with a modern twist. From jerk chicken to rum cocktails, every bite tells a story.
                            </p>
                        </div>

                        <!-- Enhanced Stats Grid -->
                        <div class="grid grid-cols-3 gap-6 max-w-md mx-auto lg:mx-0">
                            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20 hover:bg-white/20 transition-all duration-300">
                                <div class="text-3xl font-bold text-yellow-400">4.9</div>
                                <div class="text-sm text-gray-300">Rating</div>
                            </div>
                            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20 hover:bg-white/20 transition-all duration-300">
                                <div class="text-3xl font-bold text-yellow-400">5000+</div>
                                <div class="text-sm text-gray-300">Customers</div>
                            </div>
                            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20 hover:bg-white/20 transition-all duration-300">
                                <div class="text-3xl font-bold text-yellow-400">50+</div>
                                <div class="text-sm text-gray-300">Menu Items</div>
                            </div>
                        </div>

                        <!-- Enhanced CTA Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                            <Link :href="route('make-reservation')"
                                class="group inline-flex items-center gap-3 bg-gradient-to-r from-yellow-400 to-yellow-500 text-dark-900 px-8 py-4 rounded-full font-bold text-lg shadow-xl hover:from-yellow-300 hover:to-yellow-400 transition-all duration-300 transform hover:scale-105 hover:shadow-2xl">
                                <span>Book a Table</span>
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </Link>
                            <Link :href="route('menu')"
                                class="group inline-flex items-center gap-3 border-2 border-white text-white px-8 py-4 rounded-full font-bold text-lg backdrop-blur-sm hover:bg-white hover:text-gray-900 transition-all duration-300 transform hover:scale-105">
                                <span>Explore Menu</span>
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </Link>
                        </div>
                    </div>

                    <!-- Right Side - Enhanced Hero Visual -->
                    <div class="relative flex justify-center lg:justify-end animate-slide-in-right">
                        <div class="relative">
                            <!-- Decorative Background Elements -->
                            <div class="absolute -top-8 -right-8 w-40 h-40 bg-gradient-to-br from-yellow-400/30 to-accent-red/30 rounded-full blur-3xl animate-pulse"></div>
                            <div class="absolute -bottom-8 -left-8 w-32 h-32 bg-gradient-to-br from-green-400/30 to-yellow-400/30 rounded-full blur-2xl animate-pulse animation-delay-2000"></div>
                            
                            <!-- Main Image Container -->
                            <div class="relative">
                                <!-- Glowing Border Effect -->
                                <div class="absolute -inset-6 bg-gradient-to-r from-yellow-400 via-accent-red to-green-400 rounded-full blur-xl opacity-30 animate-pulse-slow"></div>
                                
                                <!-- Main Image with Enhanced Styling -->
                                <div class="relative z-10">
                                    <img src="/img/portrait1.JPG" alt="Caribbean Smokehouse Signature Dish"
                                        class="w-96 h-96 lg:w-[28rem] lg:h-[28rem] object-cover rounded-full border-4 border-white shadow-2xl transform hover:scale-105 transition-all duration-500" style="background-position: center;" />
                                    
                                    <!-- Floating Food Elements -->
                                    <div class="absolute -top-4 -right-4 w-16 h-16 bg-yellow-400 rounded-full flex items-center justify-center shadow-lg animate-pulse">
                                        <img src="/img/food/burger.png" alt="Burger" class="w-10 h-10 object-cover rounded-full" />
                                    </div>
                                    <div class="absolute -bottom-4 -left-4 w-14 h-14 bg-accent-red rounded-full flex items-center justify-center shadow-lg animate-pulse animation-delay-1000">
                                        <img src="/img/food/fritters.jpg" alt="Fritters" class="w-8 h-8 object-cover rounded-full" />
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Floating Rating Badge -->
                            <div class="absolute z-50 top-8 right-8 bg-white/90 backdrop-blur-sm rounded-full px-4 py-2 shadow-lg">
                                <div class="flex items-center gap-2">
                                    <div class="flex">
                                        <svg v-for="i in 5" :key="i" class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    </div>
                                    <span class="text-dark-900 font-bold">4.9</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Food Categories Section -->
        <section class="py-20 bg-green-100">
            <div class="container mx-auto px-4">
                <div class="text-center mb-16">
                    <h2 class="text-4xl lg:text-5xl font-bold text-green-600 mb-4 knewave-regular">Explore Our Menu</h2>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">Discover authentic Caribbean flavors across our
                        carefully curated menu categories</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div v-for="category in menuCategories" :key="category.key"
                        class="group relative overflow-hidden ">
                        <div class="aspect-square relative rounded-3xl overflow-hidden">
                            <!-- Display Image or Fallback -->
                            <div v-if="category.display_image" class="w-full h-full">
                                <img :src="`/storage/${category.display_image}`" :alt="category.label"
                                    class="w-full h-full object-cover rounded-3xl group-hover:scale-110 transition-transform duration-300" />
                                <div
                                    class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition-all duration-300">
                                </div>
                            </div>
                            <div v-else class="w-full h-full bg-gradient-to-br from-yellow-400 to-accent-red">
                                <div
                                    class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition-all duration-300">
                                </div>
                            </div>

                            <!-- Hover Overlay with View Items Button -->
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <button @click="selectedMenuCategory = category.key"
                                    class="bg-white text-dark-900 px-6 py-3 rounded-full font-bold hover:bg-gray-100 transition-colors transform scale-90 group-hover:scale-100 duration-300 shadow-lg">
                                    View Items →
                                </button>
                            </div>
                        </div>
                        <div class="p-6 text-center">
                            <h3 class="text-2xl text-green-600 knewave-regular font-extrabold ">{{ category.label }}</h3>
                            <p class="text-gray-600 mb-4">{{ category.count }} Items</p>
                        </div>
                    </div>
                </div>
                
                <!-- Browse Full Menu Button -->
                <div class="text-center mt-6">
                    <Link :href="route('menu')"
                        class="inline-flex items-center gap-3 ring-1 ring-gray-900  text-gray-900 px-8 py-4 rounded-xl font-bold text-lg hover:bg-green-500 transition-all duration-300 transform hover:scale-105 shadow-">
                        Browse Full Menu
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </Link>
                </div>
            </div>
        </section>

        <!-- Promotions Section -->
        <section class="py-32 bg-white relative" style="background: url('/img/bg/food_pattern.jpg') no-repeat center center fixed; background-size: cover;">
            <!-- White overlay to make background lighter -->
            <div class="absolute inset-0 bg-white/95"></div>
            
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div v-for="(promo, index) in promotions" :key="index"
                        class="group relative overflow-hidden rounded-2xl bg-gradient-to-r" :class="promo.color">
                        <div class="p-12 text-white">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h3 class="text-3xl font-bold mb-2">{{ promo.title }}</h3>
                                    <p class="text-xl mb-2">{{ promo.subtitle }}</p>
                                    <p class="text-gray-200 mb-6">{{ promo.description }}</p>
                                    <Link :href="promo.title === 'SIGNATURE COCKTAILS' ? route('cocktail') : route('contact')"
                                        class="inline-block bg-white text-dark-900 px-6 py-3 rounded-full font-bold hover:bg-gray-100 transition-colors">
                                        {{ promo.cta }}
                                    </Link>
                                </div>
                                <div class="hidden lg:block">
                                    <img :src="promo.image" :alt="promo.title"
                                        class="w-44 h-44 object-cover rounded-full transform group-hover:scale-110 transition-transform duration-300" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Menu Items -->
        <section class="py-20 mx-auto" style="background-color: rgb(9 20 32);">
            <div class="container max-w-7xl mx-auto px-4">
                <div class="flex flex-col lg:flex-row items-center justify-between mb-16">
                    <div class="text-left mb-8 lg:mb-0">
                        <h2 class="text-4xl lg:text-5xl font-bold text-white mb-4 knewave-regular">Find Your Best
                            Delicious Flavor</h2>
                        <p class="text-xl text-gray-300 max-w-2xl">
                            Scroll, select, and savor — our diverse menu brings together the best of local favorites and
                            global flavors, all made fresh and full of taste.
                        </p>
                    </div>
                    <Link :href="route('menu')"
                        class="inline-flex items-center gap-2 bg-white text-dark-900 px-6 py-3 rounded-full font-bold hover:bg-gray-100 transition-colors">
                    Browse More Dishes
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                    </Link>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 h-full">
                    <div v-for="item in featuredMenuItems" :key="item.id"
                        class="group p-5 h-full bg-dark-900/50 backdrop-blur-sm rounded-2xl overflow-hidden border border-gray-700 hover:border-yellow-400 transition-all duration-300 transform hover:-translate-y-2">
                        <div class="relative overflow-hidden rounded-2xl h-80 group">
                            <img :src="item.image_path ? '/storage/' + item.image_path : '/img/food/burger.png'"
                                :alt="item.name"
                                class="w-full h-full object-cover object-center rounded-2xl group-hover:scale-110 transition-transform duration-300" />

                            <!-- Dark overlay on hover -->
                            <div
                                class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-all duration-300 rounded-2xl">
                            </div>

                            <!-- Yellow arrow button overlay -->
                            <div
                                class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <button
                                    class="bg-yellow-400 text-dark-900 w-12 h-12 rounded-full flex items-center justify-center shadow-lg hover:bg-yellow-300 transition-colors duration-200 transform hover:scale-110">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                    </svg>
                                </button>
                            </div>

                            <div v-if="item.is_chef_special"
                                class="absolute top-4 right-4 bg-yellow-400 text-dark-900 px-3 py-1 rounded-full text-sm font-bold z-10">
                                Chef's Special
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-2xl font-bold text-yellow-400">${{ Number(item.price || 0).toFixed(2)
                                }}</span>
                                <span class="text-sm text-gray-200">{{ item.category?.name || 'Featured' }}</span>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-3">{{ item.name }}</h3>
                            <p class="text-gray-10 text-sm leading-relaxed mb-4 line-clamp-2">{{ item.description }}</p>
                            <!-- <button class="text-yellow-400 font-semibold hover:text-white transition-colors flex items-center gap-2">
                                View Details
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button> -->
                        </div>
                    </div>
                </div>
            </div>
        </section>

         <!-- Marquee Section -->
         <div class="relative overflow-hidden py-16 bg-dark-900">
            <div class="absolute inset-0">
                <div class="absolute inset-0 bg-gradient-to-b from-black/30 via-transparent to-black/30"></div>
                <div class="absolute inset-0 opacity-10">
                    <div class="h-full w-full" style="background-image: repeating-linear-gradient(45deg, #FBCB1E 0, #FBCB1E 1px, transparent 0, transparent 50%),
                                 repeating-linear-gradient(-45deg, #E34234 0, #E34234 1px, transparent 0, transparent 50%);
                         background-size: 40px 40px;"></div>
                </div>
            </div>

            <div class="relative z-10">
                <div class="marquee-container mb-8">
                    <div class="flex space-x-4 animate-marquee whitespace-nowrap">
                        <div v-for="(item, index) in ['Curry', 'Jerk Chicken', 'Burger', 'Shrimp Pasta', 'Tasty Wings', 'French Fry', 'Chicken Fry', 'Chicken Patty', 'Grilled Chicken']"
                            :key="index" class="group inline-flex items-center">
                            <span
                                class="text-4xl md:text-6xl font-black text-white/90 hover:text-yellow-400 transition-colors duration-300 cursor-pointer transform hover:scale-105">
                                {{ item }}
                            </span>
                            <img src="/img/shape/cutlery.png" alt="cutlery icon"
                                class="w-8 h-8 md:w-12 md:h-12 mx-8 md:mx-12 opacity-40 group-hover:opacity-100 transition-all duration-300 transform rotate-12 group-hover:rotate-45 filter brightness-0 invert" />
                        </div>
                        <!-- Duplicate content for seamless loop -->
                        <div v-for="(item, index) in ['Curry', 'Jerk Chicken', 'Burger', 'Shrimp Pasta', 'Tasty Wings', 'French Fry', 'Chicken Fry', 'Chicken Patty', 'Grilled Chicken']"
                            :key="`duplicate-${index}`" class="group inline-flex items-center">
                            <span
                                class="text-4xl md:text-6xl font-black text-white/90 hover:text-yellow-400 transition-colors duration-300 cursor-pointer transform hover:scale-105">
                                {{ item }}
                            </span>
                            <img src="/img/shape/cutlery.png" alt="cutlery icon"
                                class="w-8 h-8 md:w-12 md:h-12 mx-8 md:mx-12 opacity-40 group-hover:opacity-100 transition-all duration-300 transform rotate-12 group-hover:rotate-45 filter brightness-0 invert" />
                        </div>
                    </div>
                </div>

                <div class="marquee-container">
                    <div class="flex space-x-4 animate-marquee-reverse whitespace-nowrap">
                        <div v-for="(item, index) in ['Caribbean Style', 'Island Flavors', 'Spicy Hot', 'Fresh Ingredients', 'Home Made', 'Traditional', 'Authentic', 'Family Recipe']"
                            :key="index" class="group inline-flex items-center">
                            <span
                                class="text-3xl md:text-5xl font-black text-yellow-400/80 hover:text-white transition-colors duration-300 cursor-pointer transform hover:scale-105">
                                {{ item }}
                            </span>
                            <img src="/img/icon/palm-tree.webp" alt="palm tree icon"
                                class="w-8 h-8 md:w-12 md:h-12 mx-8 md:mx-12 opacity-40 group-hover:opacity-100 transition-all duration-300 transform -rotate-12 group-hover:-rotate-45 filter brightness-0 invert" />
                        </div>
                        <!-- Duplicate content for seamless loop -->
                        <div v-for="(item, index) in ['Caribbean Style', 'Island Flavors', 'Spicy Hot', 'Fresh Ingredients', 'Home Made', 'Traditional', 'Authentic', 'Family Recipe']"
                            :key="`duplicate-${index}`" class="group inline-flex items-center">
                            <span
                                class="text-3xl md:text-5xl font-black text-yellow-400/80 hover:text-white transition-colors duration-300 cursor-pointer transform hover:scale-105">
                                {{ item }}
                            </span>
                            <img src="/img/icon/palm-tree.webp" alt="palm tree icon"
                                class="w-8 h-8 md:w-12 md:h-12 mx-8 md:mx-12 opacity-40 group-hover:opacity-100 transition-all duration-300 transform -rotate-12 group-hover:-rotate-45 filter brightness-0 invert" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- About & Stats Section -->
        <section class="pb-40 pt-20 bg-light-cream relative"
            style="background: url('/img/bg/food_pattern.jpg') no-repeat center center fixed; background-size: cover;">
            <!-- White overlay to make background lighter -->
            <div class="absolute inset-0 bg-white/95"></div>


            <div class="container mx-auto px-4 relative z-10">
                <h1
                    class="text-4xl lg:text-5xl font-bold text-green-600 my-8 knewave-regular text-center max-w-2xl mx-auto">
                    More Than Just Food – It’s a Flavor Story</h1>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 mt-20 items-center">
                    <!-- Left Column - Image -->
                    <div class="relative overflow-hidden rounded-2xl h-full">
                        <!-- Background Image -->
                        <img src="/img/misc/image 1.avif" alt="Background" class="w-full h-full object-cover" />

                        <!-- Dark overlay for better text readability -->
                        <div class="absolute inset-0 bg-black/40"></div>

                        <!-- Stats Content -->
                        <div class="absolute inset-1 flex flex-co text-white p-8">
                            <div class="grid grid-cols-1 gap-1 text-left h-96 my-auto">
                                <div class="text-left">
                                    <div class="text-4xl lg:text-5xl font-bold ">50+</div>
                                    <div class="text-sm text-gray-200">Unique Menu Items</div>
                                </div>
                                <div class="text-left">
                                    <div class="text-4xl lg:text-5xl font-bold">200+</div>
                                    <div class="text-sm text-gray-200">Outlets & Growing</div>
                                </div>
                                <div class="text-left">
                                    <div class="text-4xl lg:text-5xl font-bold">1,000+</div>
                                    <div class="text-sm text-gray-200">Orders Delivered</div>
                                </div>
                                <div class="text-left">
                                    <div class="text-4xl lg:text-5xl font-bold">1,000+</div>
                                    <div class="text-sm text-gray-200">Positive Reviews</div>
                                </div>
                            </div>
                        </div>

                        <!-- Decorative food elements -->
                        <div class="absolute top-4 right-4">
                            <img src="/img/food/burger.png" alt="Food"
                                class="w-12 h-12 object-cover rounded-lg opacity-80" />
                        </div>
                        <div class="absolute bottom-4 left-4">
                            <img src="/img/food/fritters.jpg" alt="Food"
                                class="w-10 h-10 object-cover rounded-lg opacity-80" />
                        </div>
                    </div>


                    <!-- center Column - Content -->
                    <div class="bg-yellow-50 p-8 rounded-2xl">
                        <h2 class="text-4xl lg:text-5xl font-bold text-dark-900 mb-6 knewave-regular">Satisfy Your
                            Cravings</h2>
                        <p class="text-lg text-gray-700 leading-relaxed mb-8">
                            At Hardball Caribbean Smokehouse, we believe great food unites people. Our mission is to
                            serve bold meals that blend authentic Caribbean flavors with local love. From juicy jerk
                            chicken to chilled rum cocktails, every dish is crafted with care and a touch of island
                            vibes.
                        </p>

                        <!-- <Link :href="route('about')"
                            class="inline-flex items-center gap-2 border-2 border-dark-900 bg-white text-dark-900 px-6 py-3 rounded-lg font-bold hover:bg-dark-900 hover:text-white transition-colors">
                        Learn More
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                        </Link> -->

                        <!-- Customer Satisfaction Section -->
                        <div class="mt-8 bg-yellow-400 p-4 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="flex -space-x-2">
                                        <img src="/img/icon/palm-tree.webp" alt="Customer"
                                            class="w-8 h-8 rounded-full border-2 border-white" />
                                        <img src="/img/icon/palm-tree.webp" alt="Customer"
                                            class="w-8 h-8 rounded-full border-2 border-white" />
                                        <img src="/img/icon/palm-tree.webp" alt="Customer"
                                            class="w-8 h-8 rounded-full border-2 border-white" />
                                    </div>
                                    <span class="font-bold text-dark-900">5000+ Happy Customers</span>
                                </div>
                                <img src="/img/food/burger.png" alt="Food" class="w-12 h-12 object-cover rounded-lg" />
                            </div>
                        </div>


                    </div>

                    <!-- Right Column - Stats Card -->
                    <div class="relative h-full">
                        <div class="relative overflow-hidden rounded-2xl h-full">
                            <img src="/img/misc/image 2.jpg" alt="Customer enjoying Caribbean food"
                                class="w-full h-full object-cover rounded-2xl" />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent rounded-2xl">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Why Choose Us Section -->
        <section class="py-20 bg-green-100">
            <div class="container mx-auto px-4">
                <!-- <div class="text-center mb-16">
                    <h2 class="text-4xl lg:text-5xl font-bold text-dark-900 mb-4">Why Choose Us</h2>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">Discover what makes us the premier Caribbean restaurant in Ipswich</p>
                </div> -->

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="group">
                        <div
                            class="w-16 h-16 bg-yellow-400 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Fresh Ingredients</h3>
                        <p class="text-gray-600">Locally sourced, premium quality ingredients for authentic Caribbean
                            flavors</p>
                    </div>

                    <div class="group">
                        <div
                            class="w-16 h-16 bg-yellow-400 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Authentic Recipes</h3>
                        <p class="text-gray-600">Traditional Caribbean recipes passed down through generations</p>
                    </div>

                    <div class="group">
                        <div
                            class="w-16 h-16 bg-yellow-400 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-dark-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-dark-900 mb-2">Warm Atmosphere</h3>
                        <p class="text-gray-600">Vibrant Caribbean vibes with friendly, welcoming service</p>
                    </div>

                    <div class="group">
                        <div
                            class="w-16 h-16 bg-yellow-400 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-dark-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-dark-900 mb-2">Great Value</h3>
                        <p class="text-gray-600">Generous portions and competitive prices for exceptional quality</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- catering section -->
        <section class="flex gap-8 bg-gray-900 py-32 px-10 justify-center relative overflow-hidden">
            <!-- Abstract Background Design -->
            <div class="absolute inset-0 opacity-10 z-0">
                <!-- Floating geometric shapes -->
                <div class="absolute top-10 left-10 w-32 h-32 border-2 border-green-400 rounded-full animate-pulse"></div>
                <div class="absolute top-20 right-20 w-24 h-24 bg-green-400 rounded-full animate-bounce"></div>
                <div class="absolute bottom-20 left-1/4 w-16 h-16 border-2 border-green-400 transform rotate-45 animate-pulse"></div>
                <div class="absolute bottom-10 right-1/3 w-20 h-20 bg-green-400 rounded-full animate-bounce"></div>
                
                <!-- Diagonal lines -->
                <div class="absolute top-0 left-0 w-full h-full">
                    <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-green-400 to-transparent transform rotate-12"></div>
                    <div class="absolute top-20 left-0 w-full h-px bg-gradient-to-r from-transparent via-green-400 to-transparent transform -rotate-6"></div>
                    <div class="absolute bottom-20 left-0 w-full h-px bg-gradient-to-r from-transparent via-green-400 to-transparent transform rotate-3"></div>
                </div>
                
                <!-- Dots pattern -->
                <div class="absolute top-1/4 left-1/4 w-2 h-2 bg-green-400 rounded-full"></div>
                <div class="absolute top-1/3 right-1/4 w-2 h-2 bg-green-400 rounded-full"></div>
                <div class="absolute bottom-1/4 left-1/3 w-2 h-2 bg-green-400 rounded-full"></div>
                <div class="absolute bottom-1/3 right-1/3 w-2 h-2 bg-green-400 rounded-full"></div>
                
                <!-- Curved lines -->
                <div class="absolute top-1/2 left-10 w-20 h-20 border-2 border-green-400 rounded-full border-t-transparent border-r-transparent transform -rotate-45"></div>
                <div class="absolute top-1/3 right-10 w-16 h-16 border-2 border-green-400 rounded-full border-b-transparent border-l-transparent transform rotate-45"></div>
            </div>
            
            <div class="lg:flex-row items-center justify-between relative z-10">
                    <div class="text-left mb-8 lg:mb-0">
                        <h2 class="text-4xl lg:text-5xl font-bold text-green-600 mb-4 knewave-regular">Catering Cravings for Every Celebration</h2>
                        <p class="text-xl text-gray-300 max-w-2xl">
                            From intimate gatherings to grand celebrations, Hardball Caribbean Smokehouse delivers delicious food options that impress every guest.
                        </p>
                    </div>
                    <Link :href="route('contact')"
                        class="inline-flex items-center gap-2 mt-8 bg-white text-green-700 px-6 py-3 rounded-full font-bold hover:bg-gray-100 transition-colors">
                    Get Catering Quote
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                    </Link>
                </div>

            <!-- Card 1 -->
            <div class="group bg-gray-800 rounded-3xl border border-gray-300 w-80 text-center p-8 hover:border-green-400 transition-all duration-300 hover:scale-105" data-aos="fade-right" data-aos-delay="100">
                <div class="w-64 h-64 mx-auto mb-6 bg-cover bg-center bg-no-repeat border-4 border-gray-400 rounded-full bg-[url('/img/gallery/drink4.jpg')] clip-path: polygon(50% 0, 83% 12%, 100% 43%, 94% 78%, 68% 100%, 32% 100%, 6% 78%, 0% 43%, 17% 12%); group-hover:scale-110 group-hover:border-green-400 transition-all duration-300">
                </div>
                <h3 class="text-green-400 text-2xl font-bold mb-3 group-hover:text-green-300 transition-colors">Social Event</h3>
                <p class="text-gray-300 text-lg group-hover:text-gray-200 transition-colors">80+ Package Available</p>
            </div>

            <!-- Card 2 -->
            <div class="group bg-gray-800 rounded-3xl border border-gray-300 w-80 text-center p-8 hover:border-green-400  transition-all duration-300 hover:scale-105" data-aos="fade-right" data-aos-delay="300">
                <div class="w-64 h-64 mx-auto mb-6 bg-cover bg-top bg-no-repeat border-4 border-gray-400 rounded-full bg-[url('/img/gallery/store7.JPG')] clip-path: polygon(50% 0, 83% 12%, 100% 43%, 94% 78%, 68% 100%, 32% 100%, 6% 78%, 0% 43%, 17% 12%); group-hover:scale-110 group-hover:border-green-400 transition-all duration-300" style="background-position: center 25%;">
                </div>
                <h3 class="text-green-400 text-2xl font-bold mb-3 group-hover:text-green-400 transition-colors">Corporate</h3>
                <p class="text-gray-300 text-lg group-hover:text-gray-200 transition-colors">80+ Package Available</p>
            </div>

            <!-- Card 3 -->
            <div class="group bg-gray-800 rounded-3xl border border-gray-300 w-80 text-center p-8 hover:border-green-400 transition-all duration-300 hover:scale-105" data-aos="fade-right" data-aos-delay="500">
                <div class="w-64 h-64 mx-auto mb-6 bg-cover bg-center bg-no-repeat border-4 border-gray-400 rounded-full bg-[url('/img/gallery/event3.jpeg')] clip-path: polygon(50% 0, 83% 12%, 100% 43%, 94% 78%, 68% 100%, 32% 100%, 6% 78%, 0% 43%, 17% 12%); group-hover:scale-110 group-hover:border-green-400 transition-all duration-300">
                </div>
                <h3 class="text-green-400 text-2xl font-bold mb-3 group-hover:text-green-300 transition-colors">Birthday Event</h3>
                <p class="text-gray-300 text-lg group-hover:text-gray-200 transition-colors">80+ Package Available</p>
            </div>

        </section>

        <!-- Testimonials Section -->
        <section class="py-32 bg-white">
            <div class="container mx-auto px-4">
                <div class="flex flex-col lg:flex-row items-center justify-between mb-16">
                    <div class="text-left mb-8 lg:mb-0">
                        <h2 class=" w-2/3 text-4xl lg:text-5xl font-bold text-slate-900 mb-4 knewave-regular">Here's What Our Foodies Are Raving About!</h2>
                        <p class="text-xl text-gray-800 max-w-2xl">
                            We serve happiness, flavor, and unforgettable experiences. Here's what our customers say about their Hardball Caribbean Smokehouse moments.
                        </p>
                    </div>
                    <Link :href="route('menu')"
                        class="inline-flex items-center gap-2 bg-white text-dark-900 px-6 py-3 rounded-full font-bold hover:bg-gray-100 transition-colors">
                    Browse Dishes
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                    </Link>
                </div>

                <!-- Carousel Container -->
                <div class="relative overflow-hidden">
                    <!-- Pause/Play Button -->
                    <button @click="toggleAutoPlay" 
                            class="absolute top-4 right-4 z-20 bg-white/90 hover:bg-white text-gray-800 p-2 rounded-full shadow-lg transition-all duration-300">
                        <svg v-if="isAutoPlaying" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6" />
                        </svg>
                        <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </button>

                    <!-- Carousel Track -->
                    <div class="flex transition-transform duration-700 ease-out cursor-grab active:cursor-grabbing" 
                         :style="{ transform: `translateX(calc(-${(currentSlide + testimonials.length) * 33.333}% + ${dragOffset}px))` }"
                         @mousedown="startDrag"
                         @mousemove="onDrag"
                         @mouseup="endDrag"
                         @mouseleave="endDrag"
                         @touchstart="startDrag"
                         @touchmove="onDrag"
                         @touchend="endDrag">
                        <div v-for="(testimonial, index) in infiniteTestimonials" :key="index"
                            class="w-1/3 flex-shrink-0 px-4">
                            <div class="bg-white border-2 border-gray-600 backdrop-blur-sm rounded-2xl p-6 text-center hover-lif hover:bg-yellow-100 hover:border-yellow-300 relative h-full">
                                <div class="">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 310 310" class="w-10 h-10 text-gray-900">
                                        <path d="M79 142.16c-6.02 0-11.42.28-16.25.81 7.1-29.03 22.95-44.36 45.88-56.04 5.33-2.71 7.63-9.1 5.23-14.57l-6.04-13.77c-2.59-5.91-9.62-8.44-15.38-5.53-22.1 11.11-37.39 23.92-48.76 40.63C28.42 116.11 21 145.6 21 183.83v16.52c0 31.95.11 57.81 58 57.81 58 0 58-25.97 58-58s.38-58-58-58zm152 0c-6.02 0-11.42.28-16.25.81 7.1-29.03 22.95-44.36 45.88-56.04 5.33-2.71 7.63-9.1 5.23-14.57l-6.04-13.77c-2.59-5.91-9.62-8.44-15.38-5.53-22.1 11.11-37.39 23.92-48.76 40.63C180.42 116.11 173 145.6 173 183.83v16.52c0 31.95.11 57.81 58 57.81 58 0 58-25.97 58-58s.38-58-58-58z" fill="currentColor"></path>
                                    </svg>
                                </div>
                            
                                <p class="text-black mb-4  italic text-xl">{{ testimonial.text }}"</p>
                                <div>
                                    <div class="flex justify-center mb-3">
                                    <svg v-for="i in testimonial.rating" :key="i" class="w-4 h-4 text-yellow-400"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                </div>
                                    <div class="font-bold text-black text-sm">{{ testimonial.name }}</div>
                                    <div class="text-gray-900 text-xs">{{ testimonial.role }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Arrows -->
                    <button @click="prevSlide" class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white/80 hover:bg-white text-gray-800 p-3 rounded-full shadow-lg transition-all duration-300 z-10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button @click="nextSlide" class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white/80 hover:bg-white text-gray-800 p-3 rounded-full shadow-lg transition-all duration-300 z-10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <!-- Dots Indicator -->
                    <div class="flex justify-center mt-8 space-x-2">
                        <button v-for="(testimonial, index) in testimonials" :key="index"
                            @click="goToSlide(index)"
                            class="w-3 h-3 rounded-full transition-all duration-300"
                            :class="currentSlide === index ? 'bg-yellow-400' : 'bg-gray-300'">
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section class="py-20 bg-yellow-200">
            <div class="container mx-auto px-4">
                <div class="text-center mb-16">
                    <h2 class="text-4xl lg:text-5xl font-bold text-dark-900 mb-4">Frequently Asked Questions</h2>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">Everything you need to know about dining with us
                    </p>
                </div>

                <div class="max-w-3xl mx-auto">
                    <div v-for="(faq, index) in faqItems" :key="index"
                        class="bg-white rounded-2xl shadow-lg mb-4 overflow-hidden">
                        <button @click="toggleFAQ(index)"
                            class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                            <h3 class="text-lg font-semibold text-dark-900">{{ faq.question }}</h3>
                            <svg class="w-6 h-6 text-yellow-400 transform transition-transform duration-300"
                                :class="{ 'rotate-180': selectedFAQ === index }" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div v-show="selectedFAQ === index" class="px-6 pb-4 text-gray-600">
                            {{ faq.answer }}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Newsletter Section -->
        <section class="py-20 bg-light-crea relative overflow-hidden">
            <!-- Background Pattern -->
           

            <!-- Light Abstract Background Design -->
            <div class="absolute inset-0 opacity-10">
                <!-- Floating geometric shapes -->
                <div class="absolute top-10 left-10 w-32 h-32 border-2 border-yellow-400 rounded-full animate-pulse"></div>
                <div class="absolute top-20 right-20 w-24 h-24 bg-yellow-400 rounded-full animate-bounce"></div>
                <div class="absolute bottom-20 left-1/4 w-16 h-16 border-2 border-yellow-400 transform rotate-45 animate-pulse"></div>
                <div class="absolute bottom-10 right-1/3 w-20 h-20 bg-yellow-400 rounded-full animate-bounce"></div>
                
                <!-- Diagonal lines -->
                <div class="absolute top-0 left-0 w-full h-full">
                    <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-yellow-400 to-transparent transform rotate-12"></div>
                    <div class="absolute top-20 left-0 w-full h-px bg-gradient-to-r from-transparent via-yellow-400 to-transparent transform -rotate-6"></div>
                    <div class="absolute bottom-20 left-0 w-full h-px bg-gradient-to-r from-transparent via-yellow-400 to-transparent transform rotate-3"></div>
                </div>
                
                <!-- Dots pattern -->
                <div class="absolute top-1/4 left-1/4 w-2 h-2 bg-yellow-400 rounded-full"></div>
                <div class="absolute top-1/3 right-1/4 w-2 h-2 bg-yellow-400 rounded-full"></div>
                <div class="absolute bottom-1/4 left-1/3 w-2 h-2 bg-yellow-400 rounded-full"></div>
                <div class="absolute bottom-1/3 right-1/3 w-2 h-2 bg-yellow-400 rounded-full"></div>
                
                <!-- Curved lines -->
                <div class="absolute top-1/2 left-10 w-20 h-20 border-2 border-yellow-400 rounded-full border-t-transparent border-r-transparent transform -rotate-45"></div>
                <div class="absolute top-1/3 right-10 w-16 h-16 border-2 border-yellow-400 rounded-full border-b-transparent border-l-transparent transform rotate-45"></div>
            </div>

            <div class="container mx-auto px-4 relative z-10">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <!-- Left Side - Dynamic Image Carousel -->
                    <div class="relative">
                        <!-- Background Yellow Cards -->
                        <div class="absolute -top-4 -left-4 w-72 h-32 bg-yellow-400 rounded-2xl transform rotate-12 animate-pulse"></div>
                        <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-yellow-400 rounded-2xl transform -rotate-12 animate-pulse animation-delay-2000"></div>
                        
                        <!-- Main Image Container -->
                        <div class="relative z-10">
                            <div class="relative overflow-hidden rounded-2xl shadow-2xl">
                                <!-- Image Carousel -->
                                <div class="relative h-72 lg:h-[400px]">
                                    <div class="absolute inset-0 transition-opacity duration-1000"
                                         :class="{ 'opacity-100': currentImageIndex === 0, 'opacity-0': currentImageIndex !== 0 }">
                                        <img src="/img/food/burger.png" alt="Caribbean Food" 
                                             class="w-full h-full object-cover" />
                                    </div>
                                    <div class="absolute inset-0 transition-opacity duration-1000"
                                         :class="{ 'opacity-100': currentImageIndex === 1, 'opacity-0': currentImageIndex !== 1 }">
                                        <img src="/img/food/burger.png" alt="Caribbean Food" 
                                             class="w-full h-full object-cover" />
                                    </div>
                                    <div class="absolute inset-0 transition-opacity duration-1000"
                                         :class="{ 'opacity-100': currentImageIndex === 2, 'opacity-0': currentImageIndex !== 2 }">
                                        <img src="/img/food/fritters.jpg" alt="Caribbean Food" 
                                             class="w-full h-full object-cover" />
                                    </div>
                                    
                                    <!-- Overlay with person -->
                                    <div class="absolute inset-0 bg-gradient-to-r from-black/20 to-transparent">
                                        <div class="absolute bottom-4 right-4 w-16 h-16 bg-yellow-400 rounded-full flex items-center justify-center">
                                            <svg class="w-8 h-8 text-dark-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side - Signup Form -->
                    <div class="text-center lg:text-left">
                        <!-- Offer Badge -->
                        <div class="inline-block bg-yellow-400 text-dark-900 px-4 py-2 rounded-full text-sm font-semibold mb-4">
                            Subscribe now and get 10% off your first order!
                        </div>
                        
                        <!-- Main Title -->
                        <h2 class="text-4xl lg:text-5xl font-bold text-dark-900 mb-6">Sign Up for Tasty Updates</h2>
                        
                        <!-- Description -->
                        <p class="text-lg text-gray-700 mb-8 leading-relaxed">
                            Be the first to know about our newest dishes, exclusive discounts, seasonal specials, and foodie tips. Delivered fresh to your inbox — just like our meals!
                        </p>
                        
                        <!-- Email Form -->
                        <div class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto lg:mx-0">
                            <input v-model="email" type="email" placeholder="Enter your email"
                                class="flex-1 px-6 py-4 rounded-lg border border-gray-300 text-dark-900 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400" />
                            <button @click="submitNewsletter"
                                class="bg-yellow-400 text-dark-900 px-8 py-4 rounded-lg font-bold hover:bg-yellow-500 transition-colors flex items-center justify-center gap-2">
                                Subscribe
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Success Message -->
                        <div v-if="newsletterSubmitted" class="mt-4 text-green-600 font-semibold">
                            Thank you for subscribing! Check your email for a special discount.
                        </div>
                    </div>
                </div>
            </div>
        </section>

       
    </MainLayout>
</template>
