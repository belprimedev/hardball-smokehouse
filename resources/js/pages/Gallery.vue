<script setup lang="ts">
import MainLayout from '@/layouts/MainLayout.vue';
import { ref, computed, onMounted } from 'vue';

// Type definitions
interface GalleryItem {
    id: number;
    image: string;
    title: string;
    category: string;
    description: string;
}

interface Category {
    id: string;
    name: string;
    count: number;
}

// Gallery data with all available images
const galleryItems = ref<GalleryItem[]>([
    // Store/Interior Images
    { id: 1, image: '/img/gallery/store1.JPG', title: 'Restaurant Interior', category: 'interior', description: 'Our elegant dining space' },
    { id: 2, image: '/img/gallery/store2.JPG', title: 'Bar Area', category: 'interior', description: 'Cozy bar with premium spirits' },
    { id: 3, image: '/img/gallery/store4.JPG', title: 'Dining Room', category: 'interior', description: 'Sophisticated atmosphere' },
    { id: 4, image: '/img/gallery/store5.JPG', title: 'Private Dining', category: 'interior', description: 'Intimate dining experience' },
    { id: 5, image: '/img/gallery/store6.JPG', title: 'Restaurant View', category: 'interior', description: 'Warm and inviting ambiance' },
    { id: 6, image: '/img/gallery/store7.JPG', title: 'Main Hall', category: 'interior', description: 'Spacious and elegant' },
    { id: 7, image: '/img/gallery/store8.jpg', title: 'Entrance', category: 'interior', description: 'Welcoming atmosphere' },
    { id: 8, image: '/img/gallery/landscape-bar.jpg', title: 'Bar Landscape', category: 'interior', description: 'Professional bar setup' },
    
    // Food Images
    { id: 9, image: '/img/gallery/food.jpg', title: 'Signature Dish', category: 'food', description: 'Chef\'s special creation' },
    { id: 10, image: '/img/gallery/food1.jpg', title: 'Gourmet Plate', category: 'food', description: 'Artistically presented cuisine' },
    { id: 11, image: '/img/gallery/food2.jpg', title: 'Delicious Meal', category: 'food', description: 'Fresh and flavorful' },
    
    // Drink Images
    { id: 12, image: '/img/gallery/drink1.jpg', title: 'Craft Cocktail', category: 'drinks', description: 'Handcrafted beverages' },
    { id: 13, image: '/img/gallery/drink2.jpg', title: 'Premium Spirits', category: 'drinks', description: 'Fine selection of drinks' },
    { id: 14, image: '/img/gallery/drink4.jpg', title: 'Signature Drink', category: 'drinks', description: 'Unique cocktail creation' },
    
    // Event Images
    { id: 15, image: '/img/gallery/event1.jpg', title: 'Private Events', category: 'events', description: 'Perfect for celebrations' },
    { id: 16, image: '/img/gallery/event2.jpeg', title: 'Corporate Events', category: 'events', description: 'Professional gatherings' },
    { id: 17, image: '/img/gallery/event3.jpeg', title: 'Special Occasions', category: 'events', description: 'Memorable moments' },
    
    // Portrait/People Images
    { id: 18, image: '/img/gallery/portrait1.JPG', title: 'Our Team', category: 'people', description: 'Dedicated staff' },
    { id: 19, image: '/img/gallery/portrait2.JPG', title: 'Chef\'s Corner', category: 'people', description: 'Culinary expertise' },
    { id: 20, image: '/img/gallery/portrait8.JPG', title: 'Service Excellence', category: 'people', description: 'Professional service' },
    { id: 21, image: '/img/gallery/portrait9.jpg', title: 'Team Spirit', category: 'people', description: 'Passionate team' },
    { id: 22, image: '/img/gallery/portrait10.jpeg', title: 'Customer Experience', category: 'people', description: 'Happy customers' },
    
    // Additional Images
    { id: 23, image: '/img/gallery/2023-11-21.jpg', title: 'Restaurant Scene', category: 'interior', description: 'Daily atmosphere' },
    { id: 24, image: '/img/gallery/2cb44fe2-aadb-4fc0-adca-b54462d1a05d.JPG', title: 'Dining Experience', category: 'interior', description: 'Unforgettable moments' },
    { id: 25, image: '/img/gallery/parallax.jpg', title: 'Restaurant Ambiance', category: 'interior', description: 'Perfect setting' },
]);

// Reactive state
const currentView = ref('grid'); // grid, masonry, carousel
const selectedCategory = ref('all');
const selectedImage = ref<GalleryItem | null>(null);
const isLightboxOpen = ref(false);
const isLoading = ref(true);
const currentSlide = ref(0);

// Categories
const categories = computed<Category[]>(() => [
    { id: 'all', name: 'All Photos', count: galleryItems.value.length },
    { id: 'interior', name: 'Interior', count: galleryItems.value.filter(item => item.category === 'interior').length },
    { id: 'food', name: 'Food', count: galleryItems.value.filter(item => item.category === 'food').length },
    { id: 'drinks', name: 'Drinks', count: galleryItems.value.filter(item => item.category === 'drinks').length },
    { id: 'events', name: 'Events', count: galleryItems.value.filter(item => item.category === 'events').length },
    { id: 'people', name: 'People', count: galleryItems.value.filter(item => item.category === 'people').length },
]);

// Computed filtered items
const filteredItems = computed(() => {
    if (selectedCategory.value === 'all') {
        return galleryItems.value;
    }
    return galleryItems.value.filter(item => item.category === selectedCategory.value);
});

// Methods
const openLightbox = (image: GalleryItem) => {
    selectedImage.value = image;
    isLightboxOpen.value = true;
    document.body.style.overflow = 'hidden';
};

const closeLightbox = () => {
    isLightboxOpen.value = false;
    document.body.style.overflow = 'auto';
};

const nextImage = () => {
    if (!selectedImage.value) return;
    const currentIndex = filteredItems.value.findIndex(item => item.id === selectedImage.value!.id);
    const nextIndex = (currentIndex + 1) % filteredItems.value.length;
    selectedImage.value = filteredItems.value[nextIndex];
};

const prevImage = () => {
    if (!selectedImage.value) return;
    const currentIndex = filteredItems.value.findIndex(item => item.id === selectedImage.value!.id);
    const prevIndex = currentIndex === 0 ? filteredItems.value.length - 1 : currentIndex - 1;
    selectedImage.value = filteredItems.value[prevIndex];
};

const handleKeydown = (e: KeyboardEvent) => {
    if (!isLightboxOpen.value) return;
    
    if (e.key === 'Escape') {
        closeLightbox();
    } else if (e.key === 'ArrowRight') {
        nextImage();
    } else if (e.key === 'ArrowLeft') {
        prevImage();
    }
};

// Lifecycle
onMounted(() => {
    setTimeout(() => {
        isLoading.value = false;
    }, 1000);
    
    document.addEventListener('keydown', handleKeydown);
    
    return () => {
        document.removeEventListener('keydown', handleKeydown);
    };
});
</script>

<template>
    <MainLayout>
        <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100">
            <!-- Hero Section -->
            <div class="relative h-64 sm:h-80 md:h-96 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-black/50 to-transparent z-10"></div>
                <img src="/img/gallery/parallax.jpg" alt="Gallery Hero" class="w-full h-full object-cover">
                <div class="absolute inset-0 flex items-center justify-center z-20">
                    <div class="text-center px-4">
                        <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold text-white mb-3 sm:mb-4 tracking-wider">Photo Gallery</h1>
                        <p class="text-base sm:text-lg md:text-xl text-gray-100 max-w-2xl mx-auto">Experience our restaurant through stunning visuals - from our elegant interiors to our exquisite cuisine</p>
                    </div>
                </div>
            </div>

            <!-- Controls Section -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
                <!-- View Toggle -->
                <div class="flex flex-col sm:flex-row justify-between items-center mb-6 sm:mb-8 gap-4">
                    <div class="flex space-x-2 bg-white rounded-lg p-1 shadow-lg border border-gray-200">
                        <button 
                            @click="currentView = 'grid'"
                            :class="[
                                'px-3 sm:px-4 py-2 rounded-md text-xs sm:text-sm font-medium transition-all duration-200',
                                currentView === 'grid' 
                                    ? 'bg-blue-600 text-white shadow-lg' 
                                    : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100'
                            ]"
                        >
                            Grid
                        </button>
                        <button 
                            @click="currentView = 'masonry'"
                            :class="[
                                'px-3 sm:px-4 py-2 rounded-md text-xs sm:text-sm font-medium transition-all duration-200',
                                currentView === 'masonry' 
                                    ? 'bg-blue-600 text-white shadow-lg' 
                                    : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100'
                            ]"
                        >
                            Masonry
                        </button>
                        <button 
                            @click="currentView = 'carousel'"
                            :class="[
                                'px-3 sm:px-4 py-2 rounded-md text-xs sm:text-sm font-medium transition-all duration-200',
                                currentView === 'carousel' 
                                    ? 'bg-blue-600 text-white shadow-lg' 
                                    : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100'
                            ]"
                        >
                            Carousel
                        </button>
                    </div>

                    <!-- Category Filter -->
                    <div class="flex flex-wrap gap-2">
                        <button 
                            v-for="category in categories" 
                            :key="category.id"
                            @click="selectedCategory = category.id"
                            :class="[
                                'px-3 sm:px-4 py-2 rounded-full text-xs sm:text-sm font-medium transition-all duration-200 border',
                                selectedCategory === category.id
                                    ? 'bg-blue-600 text-white border-blue-600 shadow-lg'
                                    : 'text-gray-600 border-gray-300 hover:text-gray-900 hover:border-gray-400 hover:bg-gray-50'
                            ]"
                        >
                            {{ category.name }} ({{ category.count }})
                        </button>
                    </div>
                </div>

                <!-- Loading State -->
                <div v-if="isLoading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
                    <div v-for="n in 8" :key="n" class="animate-pulse">
                        <div class="bg-gray-200 rounded-lg h-48 sm:h-64"></div>
                        <div class="mt-3 h-4 bg-gray-200 rounded w-3/4"></div>
                        <div class="mt-2 h-3 bg-gray-200 rounded w-1/2"></div>
                    </div>
                </div>

                <!-- Grid View -->
                <div v-else-if="currentView === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
                    <div 
                        v-for="item in filteredItems" 
                        :key="item.id"
                        @click="openLightbox(item)"
                        class="group cursor-pointer transform transition-all duration-300 hover:scale-105 hover:shadow-2xl"
                    >
                        <div class="relative overflow-hidden rounded-lg bg-white shadow-lg border border-gray-200">
                            <img 
                                :src="item.image" 
                                :alt="item.title"
                                class="w-full h-48 sm:h-56 md:h-64 object-cover transition-transform duration-500 group-hover:scale-110"
                                loading="lazy"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-3 sm:p-4 transform translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                                <h3 class="text-white text-base sm:text-lg font-semibold mb-1">{{ item.title }}</h3>
                                <p class="text-gray-200 text-xs sm:text-sm">{{ item.description }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Masonry View -->
                <div v-else-if="currentView === 'masonry'" class="columns-1 sm:columns-2 lg:columns-3 xl:columns-4 gap-4 sm:gap-6 space-y-4 sm:space-y-6">
                    <div 
                        v-for="item in filteredItems" 
                        :key="item.id"
                        @click="openLightbox(item)"
                        class="group cursor-pointer break-inside-avoid transform transition-all duration-300 hover:scale-105 hover:shadow-2xl"
                    >
                        <div class="relative overflow-hidden rounded-lg bg-white shadow-lg border border-gray-200">
                            <img 
                                :src="item.image" 
                                :alt="item.title"
                                class="w-full object-cover transition-transform duration-500 group-hover:scale-110"
                                :style="{ height: Math.random() * 150 + 200 + 'px' }"
                                loading="lazy"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-3 sm:p-4 transform translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                                <h3 class="text-white text-base sm:text-lg font-semibold mb-1">{{ item.title }}</h3>
                                <p class="text-gray-200 text-xs sm:text-sm">{{ item.description }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Carousel View -->
                <div v-else-if="currentView === 'carousel'" class="relative">
                    <div class="relative h-64 sm:h-80 md:h-96 overflow-hidden rounded-lg bg-white shadow-lg border border-gray-200">
                        <div class="flex transition-transform duration-500 ease-in-out" :style="{ transform: `translateX(-${currentSlide * 100}%)` }">
                            <div 
                                v-for="item in filteredItems" 
                                :key="item.id"
                                class="w-full flex-shrink-0 relative"
                            >
                                <img 
                                    :src="item.image" 
                                    :alt="item.title"
                                    class="w-full h-64 sm:h-80 md:h-96 object-cover"
                                >
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                                <div class="absolute bottom-0 left-0 right-0 p-4 sm:p-6 md:p-8">
                                    <h3 class="text-white text-xl sm:text-2xl md:text-3xl font-bold mb-2">{{ item.title }}</h3>
                                    <p class="text-gray-200 text-sm sm:text-base md:text-lg">{{ item.description }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Carousel Controls -->
                        <button 
                            @click="currentSlide = currentSlide === 0 ? filteredItems.length - 1 : currentSlide - 1"
                            class="absolute left-2 sm:left-4 top-1/2 transform -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white p-2 sm:p-3 rounded-full transition-all duration-200 text-sm sm:text-base"
                        >
                            ←
                        </button>
                        <button 
                            @click="currentSlide = (currentSlide + 1) % filteredItems.length"
                            class="absolute right-2 sm:right-4 top-1/2 transform -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white p-2 sm:p-3 rounded-full transition-all duration-200 text-sm sm:text-base"
                        >
                            →
                        </button>
                        
                        <!-- Carousel Indicators -->
                        <div class="absolute bottom-2 sm:bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-1 sm:space-x-2">
                            <button 
                                v-for="(item, index) in filteredItems" 
                                :key="index"
                                @click="currentSlide = index"
                                :class="[
                                    'w-2 h-2 sm:w-3 sm:h-3 rounded-full transition-all duration-200',
                                    currentSlide === index ? 'bg-white' : 'bg-white/50 hover:bg-white/75'
                                ]"
                            ></button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lightbox -->
            <div 
                v-if="isLightboxOpen" 
                @click="closeLightbox"
                class="fixed inset-0 bg-black/90 z-50 flex items-center justify-center p-2 sm:p-4"
            >
                <div @click.stop class="relative max-w-4xl max-h-full">
                    <img 
                        :src="selectedImage?.image" 
                        :alt="selectedImage?.title"
                        class="max-w-full max-h-full object-contain rounded-lg"
                    >
                    <div class="absolute bottom-0 left-0 right-0 p-4 sm:p-6 bg-gradient-to-t from-black/80 to-transparent rounded-b-lg">
                        <h3 class="text-white text-lg sm:text-xl md:text-2xl font-bold mb-2">{{ selectedImage?.title }}</h3>
                        <p class="text-gray-200 text-sm sm:text-base">{{ selectedImage?.description }}</p>
                    </div>
                    
                    <!-- Lightbox Controls -->
                    <button 
                        @click="prevImage"
                        class="absolute left-2 sm:left-4 top-1/2 transform -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white p-3 sm:p-4 rounded-full transition-all duration-200 text-sm sm:text-base"
                    >
                        ←
                    </button>
                    <button 
                        @click="nextImage"
                        class="absolute right-2 sm:right-4 top-1/2 transform -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white p-3 sm:p-4 rounded-full transition-all duration-200 text-sm sm:text-base"
                    >
                        →
                    </button>
                    <button 
                        @click="closeLightbox"
                        class="absolute top-2 sm:top-4 right-2 sm:right-4 bg-black/50 hover:bg-black/70 text-white p-2 sm:p-3 rounded-full transition-all duration-200 text-sm sm:text-base"
                    >
                        ✕
                    </button>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<style scoped>
/* Custom scrollbar for masonry */
.columns-1, .columns-2, .columns-3, .columns-4 {
    column-gap: 1.5rem;
}

.break-inside-avoid {
    break-inside: avoid;
}

/* Smooth transitions */
.transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}

/* Custom animations */
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

.animate-fadeInUp {
    animation: fadeInUp 0.6s ease-out;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .columns-1, .columns-2, .columns-3, .columns-4 {
        column-count: 1;
    }
}

@media (min-width: 768px) and (max-width: 1024px) {
    .columns-1, .columns-2, .columns-3, .columns-4 {
        column-count: 2;
    }
}

@media (min-width: 1024px) and (max-width: 1280px) {
    .columns-1, .columns-2, .columns-3, .columns-4 {
        column-count: 3;
    }
}

@media (min-width: 1280px) {
    .columns-1, .columns-2, .columns-3, .columns-4 {
        column-count: 4;
    }
}
</style> 