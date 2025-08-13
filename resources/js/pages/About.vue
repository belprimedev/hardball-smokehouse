<script setup lang="ts">
import MainLayout from '@/layouts/MainLayout.vue';
import { ref, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';

// Restaurant information from general settings
const restaurantInfo = ref({
    business_name: 'Hardball Caribbean Smokehouse',
    address: '24 Lloyds Ave, Ipswich IP1 3HD',
    email: 'info@hardballsmokehouse.co.uk',
    phone: '+44 01473 807117',
    operation_hours: ''
});

// Parsed opening hours for display
const openingHours = ref<Array<{day: string, hours: string}>>([]);

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
            phone: settings.contact_number || '+44 01473 807117',
            operation_hours: settings.operation_hours || ''
        };
        
        // Parse opening hours for display
        parseOpeningHours();
    } catch (error) {
        console.error('Error fetching general settings:', error);
    }
};

// Function to parse opening hours from text
const parseOpeningHours = () => {
    if (!restaurantInfo.value.operation_hours) {
        openingHours.value = [];
        return;
    }
    
    const lines = restaurantInfo.value.operation_hours.split('\n').filter(line => line.trim());
    const parsed = lines.map(line => {
        const parts = line.split(':');
        if (parts.length >= 2) {
            return {
                day: parts[0].trim(),
                hours: parts.slice(1).join(':').trim()
            };
        }
        return {
            day: line.trim(),
            hours: 'Hours not specified'
        };
    });
    
    openingHours.value = parsed;
};

onMounted(() => {
    fetchGeneralSettings();
});

// Gallery images for the about page
const galleryImages = [
    '/img/gallery/store1.JPG',
    '/img/gallery/food1.jpg',
    '/img/gallery/landscape-bar.jpg',
    '/img/gallery/store2.JPG',
    '/img/gallery/drink1.jpg',
    '/img/gallery/store4.JPG'
];

// Features/Specialties
const specialties = [
    {
        icon: '🍖',
        title: 'Jamaican Jerk',
        description: 'Authentic jerk chicken and pork marinated in traditional spices, slow-smoked to perfection'
    },
    {
        icon: '🍹',
        title: 'Rum Cocktails',
        description: 'Classic Caribbean rum drinks like mojitos, piña coladas, and our signature rum punch'
    },
    {
        icon: '🎵',
        title: 'Reggae Vibes',
        description: 'Live reggae music and Caribbean beats that transport you straight to the islands'
    },
    {
        icon: '🌶️',
        title: 'Island Spices',
        description: 'Traditional Caribbean seasonings like allspice, scotch bonnet, and jerk seasoning'
    }
];

// Team/Values
const values = [
    {
        title: 'Family Recipes',
        description: 'Authentic Caribbean dishes passed down through generations of island families'
    },
    {
        title: 'Fresh Ingredients',
        description: 'Only the freshest tropical fruits, herbs, and spices from the Caribbean'
    },
    {
        title: 'Island Hospitality',
        description: 'Warm Caribbean welcome that makes every guest feel like family'
    },
    {
        title: 'Cultural Pride',
        description: 'Celebrating and sharing the rich traditions of Caribbean culture'
    }
];
</script>

<template>
    <Head title="About Us - Hardball Caribbean Smokehouse" />
    
    <MainLayout>
        <!-- Hero Section -->
        <div class="relative min-h-[55vh] mt-20 bg-gradient-to-br from-gray-900 via-green-800 to-gray-800 overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-10 left-10 w-20 h-20 border-4 border-white rounded-full"></div>
                <div class="absolute top-32 right-20 w-16 h-16 border-4 border-white rounded-full"></div>
                <div class="absolute bottom-20 left-1/4 w-12 h-12 border-4 border-white rounded-full"></div>
            </div>
            
            <div class="relative z-10 container mx-auto px-4 pt-20">
                <div class="text-center text-white">
                    <h1 class="text-5xl md:text-7xl font-black mb-6 animate-pulse">
                        HARDBALL
                    </h1>
                    <h2 class="text-3xl md:text-5xl font-bold mb-4">
                        Caribbean Smokehouse
                    </h2>
                    <div class=" sm:mt-5 max-w-3xl text-center mx-auto">
                                    <p v-motion-slide-visible-bottom :delay="300" :duration="800"
                                        class="text-2xl sm:text-3xl md:text-4xl text-yellow-400 great-vibes font-bold dark:text-neutral-400">
                                        Come for the food, <span class="font-serif text-red-700">Stay</span> for
                                        the <span class="font-serif text-green-700">vibes</span>!</p>
                                </div>
                    <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm px-6 py-2 mt-3 rounded-full">
                        <span class="text-2xl">📍</span>
                        <span>24 Lloyds Ave, Ipswich IP1 3HD</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="bg-gray-50 dark:bg-gray-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
                
                <!-- Our Story Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-32">
                    <div class="space-y-6">
                        <h2 class="text-4xl font-bold knewave-regular text-yellow-400 dark:text-white">
                            Our Story
                        </h2>
                        <p class="text-lg text-gray-600 dark:text-gray-300 leading-relaxed">
                            Hardball Caribbean Smokehouse brings the vibrant flavors of Jamaica, Trinidad, and the wider Caribbean 
                            to the heart of Ipswich. Our family recipes, passed down through generations, capture the true essence 
                            of island cooking with authentic spices, slow-smoked meats, and traditional techniques.
                        </p>
                        <p class="text-lg text-gray-600 dark:text-gray-300 leading-relaxed">
                            From our signature jerk chicken marinated in secret family spices to our rum-infused cocktails 
                            and fresh tropical ingredients, every dish celebrates the rich cultural heritage of the Caribbean. 
                            We're not just serving food – we're sharing the warmth, hospitality, and soul of island life.
                        </p>
                        <div class="flex items-center gap-6 pt-4">
                            <div class="w-44 h-44 rounded-full overflow-hidden shadow-sm">
                                <img src="/img/gallery/store7.JPG" alt="Hardball Caribbean Smokehouse" 
                                     class="w-full h-full object-cover object-[center_25%]">
                            </div>
                            <div>
                                <h3 class="font-bold text-green-900 dark:text-white">Est. 2016</h3>
                                <p class="text-gray-600 dark:text-gray-300">Serving Ipswich with Caribbean excellence</p>
                            </div>
                        </div>
                    </div>
                    <div class="relative">
                        <img src="/img/gallery/store1.JPG" alt="Hardball Caribbean Smokehouse Interior" 
                             class="rounded-2xl shadow-lg w-full h-96 object-cover">
                        <div class="absolute -bottom-6 -left-6 bg-green-300 dark:bg-gray-800 p-4 rounded-lg shadow-sm">
                            <p class="text-sm  text-gray-900 font-bold dark:text-gray-300">Authentic Caribbean vibes</p>
                        </div>
                    </div>
                </div>

                <!-- Our Mission Section -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 md:p-12 mb-32 shadow-md">
                    <div class="text-center mb-12">
                        <h2 class="text-4xl font-bold knewave-regular text-yellow-400 dark:text-white mb-4">
                            Our Mission
                        </h2>
                        <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                            We're committed to bringing the authentic taste of the Caribbean to Ipswich, serving up 
                            traditional island dishes with a modern twist. Every meal is a celebration of Caribbean culture, 
                            music, and the warm hospitality that makes you feel like you're dining with family.
                        </p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        <div v-for="value in values" :key="value.title" 
                             class="text-center p-6 bg-gray-50 dark:bg-gray-700 rounded-xl">
                            <h3 class="text-xl font-bold text-yellow-900 dark:text-white mb-3">
                                {{ value.title }}
                            </h3>
                            <p class="text-gray-600 dark:text-gray-300">
                                {{ value.description }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Our Specialties Section -->
                <div class="mb-32">
                    <div class="text-center mb-16">
                        <h2 class="text-4xl font-bold text-yellow-400 knewave-regular dark:text-white mb-4">
                            What Makes Us Special
                        </h2>
                        <p class="text-xl text-gray-600 dark:text-gray-300">
                            Discover the unique flavors and experiences that set us apart
                        </p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        <div v-for="specialty in specialties" :key="specialty.title" 
                             class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm hover:shadow-lg hover:bg-yellow-100 dark:hover:bg-yellow-900/20 transition-all duration-300">
                            <div class="text-4xl mb-4">{{ specialty.icon }}</div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">
                                {{ specialty.title }}
                            </h3>
                            <p class="text-gray-600 dark:text-gray-300">
                                {{ specialty.description }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Gallery Section -->
                <div class="mb-32">
                    <div class="text-center mb-16">
                        <h2 class="text-4xl font-bold text-green-600 knewave-regular dark:text-white mb-4">
                            A Taste of Hardball
                        </h2>
                        <p class="text-xl text-gray-600 dark:text-gray-300">
                            Take a look at our vibrant atmosphere and delicious offerings
                        </p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div v-for="(image, index) in galleryImages" :key="index" 
                             class="group relative overflow-hidden rounded-xl shadow-md">
                            <img :src="image" :alt="`Hardball Caribbean Smokehouse - Image ${index + 1}`" 
                                 class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-300">
                            <div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition-colors"></div>
                        </div>
                    </div>
                </div>

                <!-- Contact & Hours Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-32">
                    <!-- Contact Information -->
                    <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-md">
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
                            Visit Us
                        </h2>
                        <div class="space-y-4">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-lg">📍</span>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 dark:text-white">Address</h3>
                                    <p class="text-gray-600 dark:text-gray-300">{{ restaurantInfo.address }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-lg">📞</span>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 dark:text-white">Phone</h3>
                                    <p class="text-gray-600 dark:text-gray-300">{{ restaurantInfo.phone }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-lg">✉️</span>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 dark:text-white">Email</h3>
                                    <p class="text-gray-600 dark:text-gray-300">{{ restaurantInfo.email }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Opening Hours -->
                    <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-md">
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
                            Opening Hours
                        </h2>
                        <div class="space-y-3">
                            <div v-for="(hours, index) in openingHours" :key="index" 
                                 class="flex justify-between items-center py-2 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                                <span class="font-semibold text-gray-900 dark:text-white">{{ hours.day }}</span>
                                <span class="text-gray-600 dark:text-gray-300">{{ hours.hours }}</span>
                            </div>
                            <div v-if="openingHours.length === 0" class="text-gray-600 dark:text-gray-300">
                                <p>Monday - Sunday: 1:00 PM - 10:00 PM</p>
                            </div>
                        </div>
                        
                        <div class="mt-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                <strong>Note:</strong> Hours may vary on holidays and special events. 
                                Please call ahead to confirm.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Call to Action -->
                <div class="mt-24">
                    <div class="bg-gradient-to-r from-green-600 to-yellow-500 p-8 rounded-2xl text-white">
                        <h2 class="text-3xl font-bold mb-4 text-center">
                            Ready to Experience Caribbean Flavors?
                        </h2>
                        <p class="text-xl mb-6 opacity-90 text-center">
                            Book your table today and join us for an unforgettable dining experience
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-start">
                            <a href="/reservation" 
                               class="bg-white text-green-600 px-8 py-3 rounded-lg font-bold hover:bg-gray-100 transition-colors">
                                Book a Table
                            </a>
                            <a href="/menu" 
                               class="border-2 border-white text-white px-8 py-3 rounded-lg font-bold hover:bg-white hover:text-green-600 transition-colors">
                                View Menu
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bottom spacing -->
        <div class="py-16 bg-gray-50 dark:bg-gray-900"></div>
    </MainLayout>
</template> 