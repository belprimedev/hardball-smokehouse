<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import MainLayout from '@/layouts/MainLayout.vue';
import { ref } from 'vue';
import { Carousel, Slide } from 'vue3-carousel';
import 'vue3-carousel/dist/carousel.css';

const showSuccess = ref(false);
const isCheckingAvailability = ref(false);
const availabilityStatus = ref<{ available: boolean; current_count: number; max_capacity: number } | null>(null);
const reservationSettings = ref<any[]>([]);
const availableTimeSlots = ref<string[]>([]);

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

const form = useForm({
    customer_name: '',
    customer_email: '',
    customer_phone: '',
    reservation_date: '',
    reservation_time: '',
    number_of_guest: 1,
    special_request: '',
});

// Real humanized testimonials
const testimonials = ref([
    {
        id: 1,
        name: 'Sarah Mitchell',
        role: 'Local Food Blogger',
        image: '/img/gallery/portrait1.JPG',
        rating: 5,
        review: 'Absolutely incredible experience! The smoked brisket was melt-in-your-mouth tender, and the atmosphere was perfect for our anniversary dinner. The staff went above and beyond to make our evening special. We\'ll definitely be back!',
        date: '2 weeks ago',
        visitType: 'Anniversary Dinner'
    },
    {
        id: 2,
        name: 'James Thompson',
        role: 'Business Executive',
        image: '/img/gallery/portrait2.JPG',
        rating: 5,
        review: 'Booked a table for our team lunch and it was outstanding. The ribs were perfectly smoked, and the sides were just as impressive. Great service, reasonable prices, and the best BBQ I\'ve had outside of Texas!',
        date: '1 week ago',
        visitType: 'Business Lunch'
    },
    {
        id: 3,
        name: 'Emma Rodriguez',
        role: 'Food Enthusiast',
        image: '/img/gallery/portrait8.JPG',
        rating: 5,
        review: 'My husband and I came here for date night and it exceeded all expectations. The craft cocktails were inventive, the food was authentic Southern BBQ, and the live music created such a warm atmosphere. Highly recommend!',
        date: '3 days ago',
        visitType: 'Date Night'
    },
    {
        id: 4,
        name: 'David Chen',
        role: 'Local Resident',
        image: '/img/gallery/portrait9.jpg',
        rating: 5,
        review: 'Been coming here since they opened and it never disappoints. The pulled pork sandwich is my go-to, but everything on the menu is fantastic. The staff remembers our names and always makes us feel welcome.',
        date: '5 days ago',
        visitType: 'Regular Customer'
    },
    {
        id: 5,
        name: 'Lisa Anderson',
        role: 'Event Planner',
        image: '/img/gallery/portrait10.jpeg',
        rating: 5,
        review: 'Organized a corporate event here for 30 people and it was flawless. The private dining area was perfect, the catering was exceptional, and the service was impeccable. Everyone raved about the food!',
        date: '1 month ago',
        visitType: 'Corporate Event'
    }
]);



const submitForm = () => {
    form.post(route('reservation.store.public'), {
        onSuccess: () => {
            form.reset();
            showSuccess.value = true;
            // Scroll to the success message
            const successElement = document.querySelector('.success-message');
            if (successElement) {
                window.scrollTo({
                    top: (successElement as HTMLElement).offsetTop - 100,
                    behavior: 'smooth'
                });
            }
            setTimeout(() => {
                showSuccess.value = false;
            }, 5000); // Hide after 5 seconds
        },
        onError: (errors) => {
            // Handle validation errors
        },
    });
};

// Function to check availability for a specific date and time
const checkAvailability = async (date: string, time: string) => {
    if (!date || !time) {
        availabilityStatus.value = null;
        return;
    }
    
    isCheckingAvailability.value = true;
    
    try {
        const response = await fetch(`/api/reservations/check-availability?date=${date}&time=${time}`);
        const data = await response.json();
        availabilityStatus.value = data;
    } catch (error) {
        console.error('Error checking availability:', error);
        availabilityStatus.value = { available: true, current_count: 0, max_capacity: 20 };
    } finally {
        isCheckingAvailability.value = false;
    }
};

// Function to fetch reservation settings
const fetchReservationSettings = async () => {
    try {
        const response = await fetch('/api/reservation-settings');
        const data = await response.json();
        reservationSettings.value = data;
    } catch (error) {
        console.error('Error fetching reservation settings:', error);
    }
};

// Function to fetch general settings
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

// Function to get day of week from date string
const getDayOfWeek = (dateString: string): string => {
    const date = new Date(dateString);
    const days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
    return days[date.getDay()];
};

// Function to generate time slots based on selected date
const generateTimeSlots = (date: string) => {
    if (!date || !reservationSettings.value.length) {
        availableTimeSlots.value = [];
        return;
    }

    const dayOfWeek = getDayOfWeek(date);
    const daySettings = reservationSettings.value.find(setting => setting.day_of_week === dayOfWeek);

    if (!daySettings || !daySettings.is_open) {
        availableTimeSlots.value = [];
        return;
    }

    const openingTime = new Date(`2000-01-01T${daySettings.opening_time}`);
    const closingTime = new Date(`2000-01-01T${daySettings.closing_time}`);

    // Generate 1-hour slots from opening to closing time
    const timeSlots: string[] = [];
    for (let hour = openingTime.getHours(); hour < closingTime.getHours(); hour++) {
        const timeString = `${hour.toString().padStart(2, '0')}:00`;
        timeSlots.push(timeString);
    }

    availableTimeSlots.value = timeSlots;
};

// Function to format time for display (e.g., "13:00" -> "1:00 PM")
const formatTimeForDisplay = (timeString: string): string => {
    const [hours, minutes] = timeString.split(':');
    const hour = parseInt(hours);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const displayHour = hour === 0 ? 12 : hour > 12 ? hour - 12 : hour;
    return `${displayHour}:${minutes} ${ampm}`;
};

// Watch for changes in date and time to check availability
import { watch, onMounted } from 'vue';

onMounted(() => {
    fetchReservationSettings();
    fetchGeneralSettings();
});

watch([() => form.reservation_date, () => form.reservation_time], ([date, time]) => {
    if (date) {
        generateTimeSlots(date);
    }
    if (date && time) {
        checkAvailability(date, time);
    } else {
        availabilityStatus.value = null;
    }
});
</script>
<style>
body {
    background-color: rgb(239, 254, 255);
}

.card {
    position: relative;
    background-color: #FFF;
    display: flex;
    flex-direction: column;
    justify-content: end;
    padding: 10px;
    margin: 10px;
    gap: 12px;
    border-radius: 8px;
    cursor: pointer;
}

card::before {
    content: '';
    position: absolute;
    inset: 0;
    left: -5px;
    margin: auto;

    border-radius: 10px;
    background: linear-gradient(-45deg, #5a5701 0%, #04ee81 100%);
    z-index: -10;
    pointer-events: none;
    transition: all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    border-radius: 20px;
}

.card::after {
    content: "";
    z-index: -1;
    position: absolut;
    inset: 0;
    background: #000;
    /* background: linear-gradient(-45deg, #f7ef07 0%, #00dbde 100%); */
    transform: translate3d(0, 0, 0) scale(0.95);
    filter: blur(20px);
    padding: 10px 5px;
}

.heading {
    font-size: 20px;
    text-transform: capitalize;
    font-weight: 900;
    text-align: left;
}

.card p:not(.heading) {
    font-size: 14px;
}

.card p:last-child {

    font-weight: 600;
}

.card:hover::after {
    filter: blur(30px);
}

.card:hover::before {
    transform: rotate(-90deg) scaleX(1.34) scaleY(0.77);
}

.card1 {
    position: relative;
    background: rgb(248, 250, 250);
    width: 100%;
    height: 200px;
    align-items: center;
    justify-content: center;
    font-size: 25px;
    font-weight: bold;
    border-radius: 0px;
    cursor: pointer;
    overflow: hidden;
}

.card1::before,
.card1::after {
    position: absolute;
    content: "";
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 25px;
    font-weight: bold;
    background-color: rgb(244, 216, 8);
    border-radius: 10px;
    transition: all 0.5s;
}

.card1::before {
    top: 0;
    right: 0;
    border-radius: 0 10px 0 100%;
}

.card1::after {
    bottom: 0;
    left: 0;
    border-radius: 0 100% 0 10px;
}

.card1:hover::before,
.card1:hover:after {
    width: 100%;
    height: 100%;
    border-radius: 10px;
    transition: all 0.5s;
}

.card1:hover:after {
    content: "Smokehouse Special";
    font-family: monospace;
    font-weight: bolder;
}

/* From Uiverse.io by aadium */
.cs-border-change:hover {
    max-width: 250px;
    padding: 2px;
    margin: 10px;
    color: white;
    border-width: 2px;
    border-style: solid;
    border-image:
        linear-gradient(to bottom,
            yellow,
            rgba(0, 0, 0, 0)) 1 100%;
}



body {
    background-color: #f7f5f1;
}

.container {
    width: 100%;
    height: 200px;
    position: relative;
    overflow: hidden;
}

.img_card {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.img_card:hover {
    transform: scale(1.1);
}

.card1 {
    position: relative;
    background: rgb(248, 250, 250);
    width: 100%;
    height: 200px;
    align-items: center;
    justify-content: center;
    font-size: 25px;
    font-weight: bold;
    border-radius: 0px;
    cursor: pointer;
    overflow: hidden;
}

.card1::before,
.card1::after {
    position: absolute;
    content: "";
    width: 90%;
    height: 20%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 25px;
    font-weight: bold;
    background-color: rgb(244, 216, 8);
    border-radius: 10px;
    transition: all 0.5s;
}

.card1::before {
    top: 0;
    right: 0;
    border-radius: 0 0px 0 100%;
}

.card1::after {
    bottom: 0;
    left: 0;
    border-radius: 0 100% 0 0px;
}

.card1:hover::before,
.card1:hover:after {
    width: 98%;
    height: 98%;
    border-top-left-radius: 1px;
    border-top-right-radius: 60%;
    border-bottom-left-radius: 0px;
    border-bottom-right-radius: 0px;
    transition: all 0.5s;
}

.card1:hover:after {
    content: "Smokehouse Special";
    font-family: monospace;
    font-weight: bolder;
}

/* From Uiverse.io by aadium */
cs-border-change:hover {
    max-width: 250px;
    padding: 1px;
    margin: 10px;
    color: white;
    border-width: 2px;
    border-style: solid;
    border-image:
        linear-gradient(to bottom,
            yellow,
            rgba(0, 0, 0, 0)) 1 100%;
}

/* Update the cursor styles */
.custom-cursor {
    width: 30px;
    height: 30px;
    border: 2px solid rgba(2, 134, 37, 0.75);
    border-radius: 50%;
    position: fixed;
    pointer-events: none;
    z-index: 99999;
    transform: translate(-50%, -50%);
    transition: all 0.15s ease-out;
    background: rgba(122, 249, 164, 0.7);
}

.cursor-dot {
    width: 11px;
    height: 11px;
    background: #019340;
    border-radius: 50%;
    position: fixed;
    pointer-events: none;
    z-index: 99999;
    transform: translate(-50%, -50%);
    transition: all 0.08s ease-out;
}

/* Updated hover effects for links and clickable elements */
a:hover~.custom-cursor,
button:hover~.custom-cursor,
[role="button"]:hover~.custom-cursor {
    width: 40px;
    height: 40px;
    border-color: rgba(0, 0, 0, 0.5);
    background-color: rgba(0, 0, 0, 0.05);
    /* Add a clip-path to create pointer shape */
    clip-path: polygon(50% 0%, 100% 50%, 50% 100%, 50% 80%, 0% 50%, 50% 20%);
    transform: translate(-50%, -50%) rotate(-90deg);
    /* Rotate to point right */
}

/* Optional: Adjust dot position on hover */
a:hover~.cursor-dot,
button:hover~.cursor-dot,
[role="button"]:hover~.cursor-dot {
    opacity: 0;
    /* Hide the dot when showing pointer */
}

/* Keep these existing styles */
*,
*::before,
*::after {
    cursor: none !important;
}

a:hover,
button:hover,
[role="button"]:hover,
.cursor-pointer:hover {
    cursor: none !important;
}


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
    padding: 40px 25px;
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

        <Head title="Cocktail Menu" />


        <div class="bg-gray-700 mt-4 text-black/75 dark:bg-black dark:text-white/50" style="
                background: linear-gradient(
                        rgba(0, 0, 0, 0.9),
                        rgba(0, 0, 0, 0.4)
                    ),
                    url('../img/landscape5.jpg');
                background-size: cover;
                background-position: right;
            ">
            <div class="relative flex flex-col selection:bg-[#FF2D20] selection:text-white">
                <div class="relative w-full">
                    <!-- ========== HEADER ========== -->

                    <!-- ========== END HEADER ========== -->
                    <!-- Hero -->
                    <!-- <div
                    class="relative overflow-hidden before:absolute before:top-0 before:start-1/2 before:bg-[url('https://preline.co/assets/svg/examples/polygon-bg-element.svg')] dark:before:bg-[url('https://preline.co/assets/svg/examples-dark/polygon-bg-element.svg')] before:bg-no-repeat before:bg-top before:bg-cover before:size-full before:-z-[1] before:transform before:-translate-x-1/2">
                </div> -->
                    <!-- End Hero -->

                    <!-- Hero -->
                    <div class="overflow-hidden">
                        <div class="mx-auto py-6 sm:py-8 md:py-10">
                            <div class="relative mx-auto max-w-4xl grid space-y-3 sm:space-y-5 lg:space-y-10">
                                <!-- Title -->
                                <div class="max-w-[85rem] mx-auto px-4 sm:px-6 lg:px-8 pt-6 sm:pt-10 pb-6 sm:pb-10">
                                    <!-- Title -->
                                    <div class="max-w-5xl pt-12 sm:pt-16 md:pt-20 text-center mx-auto">
                                        <h1 class="font-bold knewave-regular font-mono bg-clip-text bg-gradient-to-tl from-green-400 to-yellow-400 text-transparent"
                                            style="font-size: clamp(2.5rem, 8vw, 5rem)">
                                            Menu
                                        </h1>
                                    </div>
                                    <!-- End Title -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Hero -->
                </div>
            </div>
        </div>
        <main class="mt-4 sm:mt-6">
            <section id="starters" aria-labelledby="starters-title"
                class="max-w-7xl mx-auto text-center pb-8 sm:pb-10 md:pb-14">
                <div class="title-area relative z-10 py-12 sm:py-16 md:py-20">
                    <div class="flex justify-self-center text-center wow mb-4 sm:mb-7 font-bold text-orange-600"
                        data-wow-delay="0.5s">
                        <img class="me-1 mx-auto w-6 h-6 sm:w-8 sm:h-8" alt="icon" src="img/icon/titleIcon.svg">CUSTOMER
                        TESTIMONIALS<img class="ms-1 w-6 h-6 sm:w-8 sm:h-8" alt="icon" src="img/icon/titleIcon.svg"></div>
                    <p class="text-2xl sm:text-3xl md:text-4xl font-sans rubik tracking-tighter px-4" data-wow-delay="0.7s" style="word-spacing: -15px;">What our customers are saying!!</p>
                </div>
                <div class="max-w-4xl mx-auto px-4">
                    <Carousel 
                        :autoplay="5000" 
                        :wrap-around="true" 
                        :items-to-show="1"
                        :snap-align="'center'"
                        class="testimonials-carousel"
                    >
                        <Slide v-for="item in testimonials" :key="item.id" class="pb-6 sm:pb-10">
                            <div class="card border-t-4 border-red-600 hover:border-t-4 hover:border-green-600 bg-white/95 backdrop-blur-sm shadow-xl mx-2 sm:mx-4">
                                <div class="grid grid-cols-1 md:grid-cols-6 gap-4 sm:gap-6 p-4 sm:p-6 md:p-8">
                                    <div class="md:col-span-2 flex flex-col items-center">
                                        <img :src="item.image" :alt="item.name" class="h-16 w-16 sm:h-20 sm:w-20 md:h-24 md:w-24 rounded-full object-cover shadow-lg border-4 border-orange-200" />
                                        <div class="mt-3 sm:mt-4 text-center">
                                            <h3 class="text-base sm:text-lg font-bold text-gray-800">{{ item.name }}</h3>
                                            <p class="text-xs sm:text-sm text-orange-600 font-semibold">{{ item.role }}</p>
                                            <div class="flex justify-center mt-2">
                                                <div class="flex space-x-1">
                                                    <svg v-for="star in item.rating" :key="star" class="w-4 h-4 sm:w-5 sm:h-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="md:col-span-4">
                                        <div class="mb-3 sm:mb-4">
                                            <span class="inline-block bg-green-100 text-green-800 text-xs px-2 sm:px-3 py-1 rounded-full font-semibold mb-2 sm:mb-3">
                                                {{ item.visitType }}
                                            </span>
                                            <p class="text-gray-700 text-left leading-relaxed italic text-sm sm:text-base md:text-lg">"{{ item.review }}"</p>
                                        </div>
                                        <div class="flex justify-between items-center text-xs sm:text-sm text-gray-500">
                                            <span>📅 {{ item.date }}</span>
                                            <span class="text-green-600 font-semibold">Verified Customer</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </Slide>
                    </Carousel>
                </div>
            </section>



            <!-- Parallax Background -->
            <section class="flex flex-col w-full p-6 sm:p-12 md:p-24 bg-cover bg-fixed bg-center justify-center items-center"
                style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('/img/bg/store2.JPG');">
                <div
                    class="sm:pt-6 sm:pb-6 md:pt-10 md:pb-10 rounded-2xl overflow-hidden relative bg-opacity-0 bg-white/10 backdrop-blur-sm border-2 border-white">
                    <h2 class="ml-2 sm:ml-4 max-w-xl items-center rounded-full px-2 sm:px-4 py-1 sm:py-2 mb-3 sm:mb-4 text-emerald-600 ring-1 ring-inset ring-emerald-600"
                        id="starters-title"><span class="font-mono text-xs sm:text-sm" aria-hidden="true">01</span>
                        <span class="ml-2 sm:ml-3 h-2 sm:h-3.5 w-px bg-white"></span>
                        <span class="ml-2 sm:ml-3 text-center text-white text-xl sm:text-2xl md:text-3xl font-black font-serif tracking-tight">Make a
                            Reservation</span>
                    </h2>
                    <div class="max-w-5xl grid grid-cols-1 lg:grid-cols-2 mx-auto">
                        <div class="w-full p-4 sm:p-6 md:p-10">
                            <form @submit.prevent="submitForm" class="text-white">
                                <div v-if="showSuccess"
                                    class="success-message mb-3 sm:mb-4 p-3 sm:p-4 text-sm rounded-lg bg-green-100 text-green-700">
                                    Reservation created successfully!
                                </div>
                                <div v-if="Object.keys(form.errors).length > 0"
                                    class="mb-3 sm:mb-4 p-3 sm:p-4 text-sm rounded-lg bg-red-100 text-red-700 border border-red-300">
                                    <p class="font-semibold">Please correct the following errors:</p>
                                    <ul class="mt-1 list-disc list-inside">
                                        <li v-for="(error, field) in form.errors" :key="field" class="text-sm">
                                            {{ error }}
                                        </li>
                                    </ul>
                                </div>
                                <div class="grid grid-cols-1 w-full border-b border-gray-900/10 pb-4 sm:pb-5">

                                    <div class="sm:col-span-3 w-full">
                                        <label for="customer_name"
                                            class="block text-sm font-medium leading-6 text-gray-50">Full
                                            Name</label>
                                        <div class="mt-2">
                                            <input type="text" id="customer_name" v-model="form.customer_name" required
                                                :class="[
                                                    'block w-full rounded-md border-0 ps-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset placeholder:text-gray-400 sm:text-sm sm:leading-6',
                                                    form.errors.customer_name 
                                                        ? 'ring-red-500 focus:ring-red-500' 
                                                        : 'ring-gray-300 focus:ring-indigo-600'
                                                ]">
                                            <div v-if="form.errors.customer_name" class="mt-1 text-sm text-red-400">
                                                {{ form.errors.customer_name }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sm:col-span-3 w-full">
                                        <label for="customer_phone"
                                            class="block text-sm font-medium leading-6 text-gray-50">Phone
                                            Number</label>
                                        <div class="mt-2">
                                            <input type="tel" id="customer_phone" v-model="form.customer_phone" required
                                                :class="[
                                                    'block w-full rounded-md border-0 ps-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset placeholder:text-gray-400 sm:text-sm sm:leading-6',
                                                    form.errors.customer_phone 
                                                        ? 'ring-red-500 focus:ring-red-500' 
                                                        : 'ring-gray-300 focus:ring-indigo-600'
                                                ]">
                                            <div v-if="form.errors.customer_phone" class="mt-1 text-sm text-red-400">
                                                {{ form.errors.customer_phone }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sm:col-span-4">
                                        <label for="customer_email"
                                            class="block text-sm font-medium leading-6 text-gray-50">Email</label>
                                        <div class="mt-2">
                                            <input type="email" id="customer_email" v-model="form.customer_email"
                                                :class="[
                                                    'block w-full rounded-md border-0 ps-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset placeholder:text-gray-400 sm:text-sm sm:leading-6',
                                                    form.errors.customer_email 
                                                        ? 'ring-red-500 focus:ring-red-500' 
                                                        : 'ring-gray-300 focus:ring-indigo-600'
                                                ]">
                                            <div v-if="form.errors.customer_email" class="mt-1 text-sm text-red-400">
                                                {{ form.errors.customer_email }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sm:col-span-3">
                                        <label for="reservation_date"
                                            class="block text-sm font-medium leading-6 text-gray-50">Date</label>
                                        <div class="mt-2">
                                            <input type="date" id="reservation_date" v-model="form.reservation_date"
                                                required
                                                :class="[
                                                    'block w-full rounded-md border-0 ps-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset sm:text-sm sm:leading-6',
                                                    form.errors.reservation_date 
                                                        ? 'ring-red-500 focus:ring-red-500' 
                                                        : 'ring-gray-300 focus:ring-indigo-600'
                                                ]">
                                            <div v-if="form.errors.reservation_date" class="mt-1 text-sm text-red-400">
                                                {{ form.errors.reservation_date }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sm:col-span-3">
                                        <label for="reservation_time"
                                            class="block text-sm font-medium leading-6 text-gray-50">Time</label>
                                        <div class="mt-2">
                                            <select id="reservation_time" v-model="form.reservation_time" required
                                                :class="[
                                                    'block w-full rounded-md border-0 ps-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset sm:text-sm sm:leading-6',
                                                    form.errors.reservation_time 
                                                        ? 'ring-red-500 focus:ring-red-500' 
                                                        : 'ring-gray-300 focus:ring-indigo-600'
                                                ]">
                                                <option value="">Select a time</option>
                                                <option v-if="availableTimeSlots.length === 0" value="" disabled>
                                                    {{ form.reservation_date ? 'Restaurant is closed on this day' : 'Please select a date first' }}
                                                </option>
                                                <option v-for="time in availableTimeSlots" :key="time" :value="time">
                                                    {{ formatTimeForDisplay(time) }}
                                                </option>
                                            </select>
                                            <div v-if="form.errors.reservation_time" class="mt-1 text-sm text-red-400">
                                                {{ form.errors.reservation_time }}
                                            </div>
                                            <!-- Availability Status -->
                                            <div v-if="availabilityStatus && form.reservation_date && form.reservation_time" class="mt-2">
                                                <div v-if="isCheckingAvailability" class="text-sm text-blue-400">
                                                    Checking availability...
                                                </div>
                                                <div v-else-if="availabilityStatus.available" class="text-sm text-green-400">
                                                    ✅ Available ({{ availabilityStatus.current_count }}/{{ availabilityStatus.max_capacity }} spots taken)
                                                </div>
                                                <div v-else class="text-sm text-red-400">
                                                    ❌ Fully booked ({{ availabilityStatus.current_count }}/{{ availabilityStatus.max_capacity }} spots taken)
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label for="number_of_guest"
                                            class="block text-sm font-medium leading-6 text-gray-50">Number
                                            of Guests</label>
                                        <div class="mt-2">
                                            <input type="number" id="number_of_guest" v-model="form.number_of_guest"
                                                required min="1"
                                                :class="[
                                                    'block w-full rounded-md border-0 ps-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset sm:text-sm sm:leading-6',
                                                    form.errors.number_of_guest 
                                                        ? 'ring-red-500 focus:ring-red-500' 
                                                        : 'ring-gray-300 focus:ring-indigo-600'
                                                ]">
                                            <div v-if="form.errors.number_of_guest" class="mt-1 text-sm text-red-400">
                                                {{ form.errors.number_of_guest }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-span-full">
                                        <label for="special_request"
                                            class="block text-sm font-medium leading-6 text-gray-50">Special
                                            Requests</label>
                                        <div class="mt-2">
                                            <textarea id="special_request" v-model="form.special_request" rows="3"
                                                class="block w-full rounded-md border-0 ps-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6"></textarea>
                                        </div>
                                    </div>

                                    <div class="col-span-full mt-3 sm:mt-4">
                                        <button type="submit"
                                            :disabled="!!(availabilityStatus && !availabilityStatus.available)"
                                            :class="[
                                                'px-3 py-2 text-sm font-semibold text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2',
                                                availabilityStatus && !availabilityStatus.available
                                                    ? 'bg-gray-400 cursor-not-allowed'
                                                    : 'bg-green-600 hover:bg-green-500 focus-visible:outline-green-600'
                                            ]">
                                            {{ availabilityStatus && !availabilityStatus.available ? 'Time Slot Full' : 'Create Reservation' }}
                                        </button>
                                    </div>

                                </div>
                            </form>
                        </div>


                        <div class="h-full w-full pr-2 sm:pr-4 md:pr-6">
                            <div class="mb-4 sm:mb-6 max-w-3xl text-center sm:text-center md:mx-auto md:mb-12">

                                <h2
                                    class="font-heading mb-3 sm:mb-4 knewave-regular font-bold tracking-tight text-gray-50 dark:text-white text-2xl sm:text-3xl md:text-5xl">
                                    JOIN US
                                </h2>
                                <div class="mt-3 sm:mt-5 max-w-3xl text-center mx-auto">
                                    <p v-motion-slide-visible-bottom :delay="300" :duration="800"
                                        class="text-2xl sm:text-3xl md:text-4xl text-yellow-400 great-vibes font-bold dark:text-neutral-400">
                                        Come for the food, <span class="font-serif text-red-700">Stay</span> for
                                        the <span class="font-serif text-green-700">vibes</span>!</p>
                                </div>
                            </div>
                            <ul class="mb-4 sm:mb-6 md:mb-0">
                                <li class="flex">
                                    <div
                                        class="flex h-8 w-8 sm:h-10 sm:w-10 items-center justify-center rounded bg-emerald-700 text-gray-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 sm:h-6 sm:w-6">
                                            <path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"></path>
                                            <path
                                                d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="ml-3 sm:ml-4 mb-3 sm:mb-4">
                                        <h3 class="mb-1 sm:mb-2 text-base sm:text-lg font-medium leading-6 text-white dark:text-white">
                                            Our Address
                                        </h3>
                                        <p class="text-gray-300 dark:text-slate-400 text-sm sm:text-base">{{ restaurantInfo.address }}
                                        </p>
                                        <p class="text-gray-300 dark:text-slate-400 text-sm sm:text-base">United Kingdom</p>
                                    </div>
                                </li>
                                <li class="flex">
                                    <div
                                        class="flex h-8 w-8 sm:h-10 sm:w-10 items-center justify-center rounded bg-emerald-700 text-gray-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 sm:h-6 sm:w-6">
                                            <path
                                                d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2">
                                            </path>
                                            <path d="M15 7a2 2 0 0 1 2 2"></path>
                                            <path d="M15 3a6 6 0 0 1 6 6"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3 sm:ml-4 mb-3 sm:mb-4">
                                        <h3 class="mb-1 sm:mb-2 text-base sm:text-lg font-medium leading-6 text-white dark:text-white">
                                            Contact
                                        </h3>
                                        <p class="text-gray-300 dark:text-slate-400 text-sm sm:text-base">Phone: {{ restaurantInfo.phone }}</p>
                                        <p class="text-gray-300 dark:text-slate-400 text-sm sm:text-base">Mail:
                                            {{ restaurantInfo.email }}</p>
                                    </div>
                                </li>
                                <li class="flex">
                                    <div
                                        class="flex h-8 w-8 sm:h-10 sm:w-10 items-center justify-center rounded bg-emerald-700 text-gray-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 sm:h-6 sm:w-6">
                                            <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"></path>
                                            <path d="M12 7v5l3 3"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3 sm:ml-4 mb-3 sm:mb-4">
                                        <h3 class="mb-1 sm:mb-2 text-base sm:text-lg font-medium leading-6 text-white dark:text-white">
                                            Working Hours</h3>
                                        <div v-if="openingHours.length > 0">
                                            <p v-for="(hour, index) in openingHours" :key="index" 
                                               class="text-gray-300 dark:text-slate-400 text-xs sm:text-sm">
                                                {{ hour.day }}: <span class="text-emerald-400 font-semibold">{{ hour.hours }}</span>
                                            </p>
                                        </div>
                                        <div v-else class="text-gray-300 dark:text-slate-400 text-xs sm:text-sm">
                                            <p>Monday - Friday: <span class="text-emerald-400 font-semibold">1:00 PM - 9:30 PM</span></p>
                                            <p>Saturday: <span class="text-emerald-400 font-semibold">1:00 PM - 11:00 PM</span></p>
                                            <p>Sunday: <span class="text-emerald-400 font-semibold">1:00 PM - 8:30 PM</span></p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>



                    </div>
                </div>
            </section>

        </main>
    </MainLayout>
</template>

<style scoped>
/* Carousel custom styles */
.testimonials-carousel {
    width: 100%;
}

.testimonials-carousel .carousel__slide {
    display: flex;
    justify-content: center;
    align-items: center;
}

.testimonials-carousel .carousel__track {
    display: flex;
    transition: transform 0.5s ease;
}

.testimonials-carousel .carousel__viewport {
    overflow: hidden;
}

/* Ensure carousel items are properly spaced */
.carousel__slide {
    padding: 0 1rem;
}

/* Custom carousel navigation */
.carousel__prev,
.carousel__next {
    background: rgba(0, 0, 0, 0.5);
    border: none;
    border-radius: 50%;
    color: white;
    cursor: pointer;
    font-size: 1.5rem;
    height: 3rem;
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 3rem;
    z-index: 10;
    transition: background-color 0.3s ease;
}

.carousel__prev:hover,
.carousel__next:hover {
    background: rgba(0, 0, 0, 0.7);
}

.carousel__prev {
    left: 1rem;
}

.carousel__next {
    right: 1rem;
}

/* Carousel indicators */
.carousel__pagination {
    display: flex;
    justify-content: center;
    list-style: none;
    margin: 1rem 0 0 0;
    padding: 0;
}

.carousel__pagination-button {
    background: rgba(255, 255, 255, 0.5);
    border: none;
    border-radius: 50%;
    cursor: pointer;
    height: 0.75rem;
    margin: 0 0.25rem;
    width: 0.75rem;
    transition: background-color 0.3s ease;
}

.carousel__pagination-button--active {
    background: rgba(255, 255, 255, 1);
}
</style>