<script setup lang="ts">
import MainLayout from '@/layouts/MainLayout.vue';
import { ref, reactive, onMounted } from 'vue';

// Form state
const formData = reactive({
    name: '',
    email: '',
    phone: '',
    subject: '',
    message: ''
});

const isSubmitting = ref(false);
const formSubmitted = ref(false);
const generalSettings = ref<any>(null);

// Define types for contact info
interface ContactInfo {
    icon: string;
    title: string;
    content: string;
    link: string | null;
    isHours?: boolean;
    parsedHours?: Array<{ day: string; time: string }>;
}

// Contact information - will be populated from general settings
const contactInfo = ref<ContactInfo[]>([
    {
        icon: '📍',
        title: 'Address',
        content: 'Loading...',
        link: '#'
    },
    {
        icon: '📞',
        title: 'Phone',
        content: 'Loading...',
        link: '#'
    },
    {
        icon: '✉️',
        title: 'Email',
        content: 'Loading...',
        link: '#'
    },
    {
        icon: '🕒',
        title: 'Opening Hours',
        content: 'Loading...',
        link: null,
        isHours: true
    }
]);

// Parse operation hours into structured format
const parseOperationHours = (hoursText: string): Array<{ day: string; time: string }> => {
    if (!hoursText) return [];
    
    const lines = hoursText.split('\n').filter(line => line.trim());
    const hours: Array<{ day: string; time: string }> = [];
    
    lines.forEach(line => {
        const match = line.match(/([^:]+):\s*(.+)/);
        if (match) {
            const day = match[1].trim();
            const time = match[2].trim();
            hours.push({ day, time });
        }
    });
    
    return hours;
};

// Fetch general settings
const fetchGeneralSettings = async () => {
    try {
        const response = await fetch('/api/general-settings');
        const settings = await response.json();
        generalSettings.value = settings;
        
        // Parse operation hours
        const parsedHours = parseOperationHours(settings.operation_hours || '');
        
        // Update contact info with settings data
        contactInfo.value = [
            {
                icon: '📍',
                title: 'Address',
                content: settings.address || 'Address not set',
                link: `https://maps.google.com/?q=${encodeURIComponent(settings.address || '')}`
            },
            {
                icon: '📞',
                title: 'Phone',
                content: settings.contact_number || 'Phone not set',
                link: `tel:${settings.contact_number || ''}`
            },
            {
                icon: '✉️',
                title: 'Email',
                content: settings.business_email || 'Email not set',
                link: `mailto:${settings.business_email || ''}`
            },
            {
                icon: '🕒',
                title: 'Opening Hours',
                content: settings.operation_hours || 'Hours not set',
                link: null,
                isHours: true,
                parsedHours: parsedHours
            }
        ];
    } catch (error) {
        console.error('Error fetching general settings:', error);
    }
};

onMounted(() => {
    fetchGeneralSettings();
});

// Social media links
const socialLinks = [
    { name: 'Facebook', icon: '📘', url: '#' },
    { name: 'Instagram', icon: '📷', url: '#' },
    { name: 'Twitter', icon: '🐦', url: '#' },
    { name: 'TripAdvisor', icon: '⭐', url: '#' }
];

// Form validation
const errors = reactive({
    name: '',
    email: '',
    message: ''
});

const validateForm = () => {
    let isValid = true;
    
    // Reset errors
    Object.keys(errors).forEach(key => {
        errors[key as keyof typeof errors] = '';
    });
    
    // Validate name
    if (!formData.name.trim()) {
        errors.name = 'Name is required';
        isValid = false;
    }
    
    // Validate email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!formData.email.trim()) {
        errors.email = 'Email is required';
        isValid = false;
    } else if (!emailRegex.test(formData.email)) {
        errors.email = 'Please enter a valid email address';
        isValid = false;
    }
    
    // Validate message
    if (!formData.message.trim()) {
        errors.message = 'Message is required';
        isValid = false;
    }
    
    return isValid;
};

const handleSubmit = async (e: Event) => {
    e.preventDefault();
    
    if (!validateForm()) {
        return;
    }
    
    isSubmitting.value = true;
    
    // Simulate form submission
    await new Promise(resolve => setTimeout(resolve, 2000));
    
    isSubmitting.value = false;
    formSubmitted.value = true;
    
    // Reset form
    Object.keys(formData).forEach(key => {
        formData[key as keyof typeof formData] = '';
    });
    
    // Reset success message after 5 seconds
    setTimeout(() => {
        formSubmitted.value = false;
    }, 5000);
};
</script>

<template>
    <MainLayout>
        <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100">
            <!-- Hero Section -->
            <div class="relative h-64 sm:h-80 md:h-96 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-black/50 to-transparent z-10"></div>
                <img src="/img/bg/reservation.jpg" alt="Contact Hero" class="w-full h-full object-cover">
                <div class="absolute inset-0 flex items-center justify-center z-20">
                    <div class="text-center px-4">
                        <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold text-white mb-3 sm:mb-4 tracking-wider">Contact Us</h1>
                        <p class="text-base sm:text-lg md:text-xl text-gray-100 max-w-2xl mx-auto">Get in touch with us for reservations, events, or any questions about our restaurant</p>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
                <!-- Success Message -->
                <div v-if="formSubmitted" class="mb-6 sm:mb-8 bg-green-600 text-white p-4 rounded-lg text-center animate-fadeIn">
                    <h3 class="text-base sm:text-lg font-semibold">Thank you for your message!</h3>
                    <p class="text-sm sm:text-base">We'll get back to you as soon as possible.</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 sm:gap-12">
                    <!-- Contact Form -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-gray-200 shadow-xl">
                            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-6">Send us a message</h2>
                            <form @submit="handleSubmit" class="space-y-4 sm:space-y-6">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                        <input 
                                            type="text" 
                                            id="name" 
                                            v-model="formData.name"
                                            :class="[
                                                'w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg border-2 transition-all duration-200 focus:outline-none focus:ring-2 text-sm sm:text-base',
                                                errors.name 
                                                    ? 'border-red-500 bg-red-50 text-gray-900' 
                                                    : 'border-gray-300 bg-white text-gray-900 focus:border-blue-500 focus:ring-blue-500/20'
                                            ]"
                                            placeholder="Your full name"
                                        >
                                        <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
                                    </div>
                                    
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                        <input 
                                            type="email" 
                                            id="email" 
                                            v-model="formData.email"
                                            :class="[
                                                'w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg border-2 transition-all duration-200 focus:outline-none focus:ring-2 text-sm sm:text-base',
                                                errors.email 
                                                    ? 'border-red-500 bg-red-50 text-gray-900' 
                                                    : 'border-gray-300 bg-white text-gray-900 focus:border-blue-500 focus:ring-blue-500/20'
                                            ]"
                                            placeholder="your.email@example.com"
                                        >
                                        <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email }}</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                        <input 
                                            type="tel" 
                                            id="phone" 
                                            v-model="formData.phone"
                                            class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg border-2 border-gray-300 bg-white text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all duration-200 text-sm sm:text-base"
                                            placeholder="+44 123 456 7890"
                                        >
                                    </div>
                                    
                                    <div>
                                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                                        <select 
                                            id="subject" 
                                            v-model="formData.subject"
                                            class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg border-2 border-gray-300 bg-white text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all duration-200 text-sm sm:text-base"
                                        >
                                            <option value="">Select a subject</option>
                                            <option value="reservation">Reservation</option>
                                            <option value="event">Private Event</option>
                                            <option value="feedback">Feedback</option>
                                            <option value="general">General Inquiry</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message *</label>
                                    <textarea 
                                        id="message" 
                                        v-model="formData.message"
                                        rows="4"
                                        :class="[
                                            'w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg border-2 transition-all duration-200 focus:outline-none focus:ring-2 resize-none text-sm sm:text-base',
                                            errors.message 
                                                ? 'border-red-500 bg-red-50 text-gray-900' 
                                                : 'border-gray-300 bg-white text-gray-900 focus:border-blue-500 focus:ring-blue-500/20'
                                        ]"
                                        placeholder="Tell us how we can help you..."
                                    ></textarea>
                                    <p v-if="errors.message" class="mt-1 text-sm text-red-600">{{ errors.message }}</p>
                                </div>

                                <button 
                                    type="submit" 
                                    :disabled="isSubmitting"
                                    class="w-full bg-gradient-to-r from-emerald-600 to-green-600 text-white py-3 sm:py-4 px-4 sm:px-6 rounded-lg font-semibold text-base sm:text-lg hover:from-emerald-700 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:ring-offset-white transition-all duration-200 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                                >
                                    <span v-if="isSubmitting" class="flex items-center justify-center">
                                        <svg class="animate-spin -ml-1 mr-3 h-4 w-4 sm:h-5 sm:w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Sending...
                                    </span>
                                    <span v-else>Send Message</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="space-y-6 sm:space-y-8">
                        <!-- Contact Details -->
                        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-gray-200 shadow-xl">
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-6">Get in Touch</h2>
                            <div class="space-y-4 sm:space-y-6">
                                <div 
                                    v-for="info in contactInfo" 
                                    :key="info.title"
                                    class="flex items-start space-x-3 sm:space-x-4 group"
                                >
                                    <div class="text-xl sm:text-2xl group-hover:scale-110 transition-transform duration-200">
                                        {{ info.icon }}
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-1">{{ info.title }}</h3>
                                        
                                        <!-- Special display for opening hours -->
                                        <div v-if="info.isHours && info.parsedHours && info.parsedHours.length > 0" class="space-y-2">
                                            <div class="grid grid-cols-1 gap-2">
                                                <div 
                                                    v-for="(hour, index) in info.parsedHours" 
                                                    :key="index"
                                                    class="flex justify-between items-center py-2 px-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200"
                                                >
                                                    <span class="font-medium text-gray-900 text-sm">{{ hour.day }}</span>
                                                    <span class="text-emerald-600 dark:text-emerald-400 font-semibold text-sm">{{ hour.time }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Fallback for non-parsed hours or when parsing fails -->
                                        <div v-else-if="info.isHours" class="bg-gray-50 rounded-lg p-3">
                                            <p class="text-gray-600 text-sm whitespace-pre-line">{{ info.content }}</p>
                                        </div>
                                        
                                        <!-- Regular content for other items -->
                                        <div v-else>
                                            <a 
                                                v-if="info.link" 
                                                :href="info.link" 
                                                target="_blank"
                                                class="text-gray-600 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors duration-200 text-sm sm:text-base"
                                            >
                                                {{ info.content }}
                                            </a>
                                            <p v-else class="text-gray-600 text-sm sm:text-base">{{ info.content }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Social Media -->
                        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-gray-200 shadow-xl">
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-6">Follow Us</h2>
                            <div class="grid grid-cols-2 gap-3 sm:gap-4">
                                <a 
                                    v-for="social in socialLinks" 
                                    :key="social.name"
                                    :href="social.url"
                                    class="flex items-center space-x-2 sm:space-x-3 p-3 sm:p-4 rounded-lg bg-gray-50 hover:bg-gray-100 transition-all duration-200 group"
                                >
                                    <span class="text-xl sm:text-2xl group-hover:scale-110 transition-transform duration-200">
                                        {{ social.icon }}
                                    </span>
                                    <span class="text-gray-900 font-medium text-sm sm:text-base">{{ social.name }}</span>
                                </a>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 sm:p-8 border border-blue-200 shadow-xl">
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-6">Quick Actions</h2>
                            <div class="space-y-3 sm:space-y-4">
                                <a 
                                    href="/online-reservation" 
                                    class="block w-full bg-emerald-600 text-white py-2 sm:py-3 px-3 sm:px-4 rounded-lg text-center font-semibold hover:bg-emerald-700 transition-colors duration-200 text-sm sm:text-base"
                                >
                                    Make a Reservation
                                </a>
                                <a 
                                    href="/menu" 
                                    class="block w-full bg-gray-600 text-white py-2 sm:py-3 px-3 sm:px-4 rounded-lg text-center font-semibold hover:bg-gray-700 transition-colors duration-200 text-sm sm:text-base"
                                >
                                    View Our Menu
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Map Section -->
                <div class="mt-12 sm:mt-16">
                    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-gray-200 shadow-xl">
                        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-6 text-center">Find Us</h2>
                        <div class="aspect-video rounded-lg overflow-hidden">
                            <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2420.1234567890123!2d1.1234567890123456!3d52.12345678901234!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNTLCsDA3JzM0LjQiTiAxwrAwNyczNC40IkU!5e0!3m2!1sen!2suk!4v1234567890123"
                                width="100%" 
                                height="100%" 
                                style="border:0;" 
                                allowfullscreen
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade"
                                class="rounded-lg"
                            ></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<style scoped>
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fadeIn {
    animation: fadeIn 0.5s ease-out;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #1f2937;
}

::-webkit-scrollbar-thumb {
    background: #3b82f6;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #2563eb;
}

/* Input focus styles */
input:focus, textarea:focus, select:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Backdrop blur fallback */
@supports not (backdrop-filter: blur(10px)) {
    .backdrop-blur-sm {
        background-color: rgba(17, 24, 39, 0.95);
    }
}
</style> 