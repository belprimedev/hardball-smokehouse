<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import MainLayout from '@/layouts/MainLayout.vue';
import { ref } from 'vue';

const showSuccess = ref(false);
const form = useForm({
    customer_name: '',
    customer_email: '',
    customer_phone: '',
    reservation_date: '',
    reservation_time: '',
    number_of_guest: 1,
    special_request: '',
});

const submitForm = () => {
    form.post(route('reservation.store'), {
        onSuccess: () => {
            form.reset();
            showSuccess.value = true;
            // Scroll to the success message
            window.scrollTo({
                top: document.querySelector('.success-message').offsetTop - 100,
                behavior: 'smooth'
            });
            setTimeout(() => {
                showSuccess.value = false;
            }, 5000); // Hide after 5 seconds
        },
    });
};
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
                        <div class="mx-auto py-10">
                            <div class="relative mx-auto max-w-4xl grid space-y-5 sm:space-y-10">
                                <!-- Title -->
                                <div class="max-w-[85rem] mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-10">
                                    <!-- Title -->
                                    <div class="max-w-5xl pt-20 text-center mx-auto">
                                        <h1 class="font-bold knewave-regular font-mono bg-clip-text bg-gradient-to-tl from-green-400 to-yellow-400 text-transparent"
                                            style="font-size: 5rem">
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
        <main class="mt-6">
            <section id="starters" aria-labelledby="starters-title"
                class=" max-w-7xl mx-auto text-center pb-14">
                <div class="title-area relative z-10 py-20">
                    <div class="flex justify-self-center text-center wow mb-7 font-bold text-orange-600"
                        data-wow-delay="0.5s">
                        <img class="me-1 mx-auto" alt="icon" src="img/icon/titleIcon.svg">POPULAR
                        DISHES<img class="ms-1" alt="icon" src="img/icon/titleIcon.svg"></div>
                    <p class="text-4xl font-sans rubik tracking-tighter" data-wow-delay="0.7s" style="word-spacing: -15px;">What our customers are saying!!</p>
                </div>
                <Carousel :autoplay="1000000" :wrap-around="true" v-bind="settings" :breakpoints="breakpoints">
                    <Slide v-for="item in slide" :key="item.id" style="display: block;padding: px;" class="pb-10">
                        <div class="card border-t-4 border-red-600 hover:border-t-4 hover:border-green-600">
                            <div class="grid grid-cols-6 gap-2 p-8">
                                <div class="grid col-span-2 max-h-20">
                                    <img :src="item.image" alt="Slide {{ item.id }}" class="h-auto  rounded-full" />
                                </div>
                                <div class="grid col-span-4 text-yellow-400">
                                    <p class="heading">
                                        {{ item.name }}
                                    </p>
                                    <p class="text-gray-500 text-left">It is a long established fact that a reader will
                                        be distracted lorem the readable content of a page when looking at layout the
                                        point lorem established fact that It is a long established</p>

                                </div>
                            </div>
                            <div class="flex my-auto justify-between">
                                <div>
                                    <p class="text-sm my-auto align-middle text-gray-500">Regular price:</p>
                                </div>
                                <div class="">
                                    <span class="text-xl font-extrabold text-green-600">£{{ item.price }}</span>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="m-2">
                            <div class="dishes-card style2 border border-orange-100" data-wow-delay="0.2s">
                                <div class="dishes-thumb mx-auto text-center">
                                    <img alt="thumb" class="text-center mx-auto" src="/img/dishes2_4.png">
                                    <div class="circle-shape">
                                        <img class="cir36 mx-auto text-center" alt="shape" src="/img/circleShape.png">
                                    </div>
                                </div>
                                <div class="dishes-content">
                                    <a href="/menu" data-discover="true">
                                        <h3>*{{ item.name }}</h3>
                                    </a>
                                    <div class="star">
                                        <img class="text-center mx-auto" alt="icon" src="/img/icon/star2.svg">
                                    </div>
                                    <div class="text">{{ item.details }}</div>
                                    <h6>£{{ item.price }}</h6><a class="theme-btn style6" href="/menu"
                                        data-discover="true"> Try Now <i class="bi bi-basket2"></i></a>
                                </div>
                            </div>

                        </div> -->

                    </Slide>

                
                </Carousel>
            </section>



            <!-- Parallax Background -->
            <section class="flex flex-col w-full p-24 bg-cover bg-fixed bg-center justify-center items-center"
                style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('/img/bg/store2.jpg');">
                <div
                    class="sm:pt-10 sm:pb-10 rounded-2xl overflow-hidden relative bg-opacity-0 bg-white/10 backdrop-blur-sm border-2 border-white">
                    <h2 class="ml-4 max-w-xl items-center rounded-full px-4 py-2 mb-4 text-emerald-600 ring-1 ring-inset ring-emerald-600"
                        id="starters-title"><span class="font-mono text-sm" aria-hidden="true">01</span>
                        <span class="ml-3 h-3.5 w-px bg-white"></span>
                        <span class="ml-3 text-center text-white text-3xl font-black font-serif tracking-tight">Make a
                            Reservation</span>
                    </h2>
                    <div class="max-w-5xl grid grid-cols-1 lg:grid-cols-2 mx-auto">
                        <div class="w-full p-10">
                            <form @submit.prevent="submitForm" class="text-white">
                                <div v-if="showSuccess"
                                    class="success-message mb-4 p-4 text-sm rounded-lg bg-green-100 text-green-700">
                                    Reservation created successfully!
                                </div>
                                <div class="grid grid-cols-1 w-full border-b border-gray-900/10 pb-5">

                                    <div class="sm:col-span-3 w-full">
                                        <label for="customer_name"
                                            class="block text-sm font-medium leading-6 text-gray-50">Full
                                            Name</label>
                                        <div class="mt-2">
                                            <input type="text" id="customer_name" v-model="form.customer_name" required
                                                class="block w-full rounded-md border-0 ps-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        </div>
                                    </div>

                                    <div class="sm:col-span-3 w-full">
                                        <label for="customer_phone"
                                            class="block text-sm font-medium leading-6 text-gray-50">Phone
                                            Number</label>
                                        <div class="mt-2">
                                            <input type="tel" id="customer_phone" v-model="form.customer_phone" required
                                                class="block w-full rounded-md border-0 ps-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        </div>
                                    </div>

                                    <div class="sm:col-span-4">
                                        <label for="customer_email"
                                            class="block text-sm font-medium leading-6 text-gray-50">Email</label>
                                        <div class="mt-2">
                                            <input type="email" id="customer_email" v-model="form.customer_email"
                                                class="block w-full rounded-md border-0 ps-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        </div>
                                    </div>

                                    <div class="sm:col-span-3">
                                        <label for="reservation_date"
                                            class="block text-sm font-medium leading-6 text-gray-50">Date</label>
                                        <div class="mt-2">
                                            <input type="date" id="reservation_date" v-model="form.reservation_date"
                                                required
                                                class="block w-full rounded-md border-0 ps-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        </div>
                                    </div>

                                    <div class="sm:col-span-3">
                                        <label for="reservation_time"
                                            class="block text-sm font-medium leading-6 text-gray-50">Time</label>
                                        <div class="mt-2">
                                            <select id="reservation_time" v-model="form.reservation_time" required
                                                class="block w-full rounded-md border-0 ps-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                                <option value="14:00">2:00 PM</option>
                                                <option value="17:00">5:00 PM</option>
                                                <option value="20:00">8:00 PM</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label for="number_of_guest"
                                            class="block text-sm font-medium leading-6 text-gray-50">Number
                                            of Guests</label>
                                        <div class="mt-2">
                                            <input type="number" id="number_of_guest" v-model="form.number_of_guest"
                                                required min="1"
                                                class="block w-full rounded-md border-0 ps-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
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

                                    <div class="col-span-full mt-4">
                                        <button type="submit"
                                            class="rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">
                                            Create Reservation
                                        </button>
                                    </div>

                                </div>
                            </form>
                        </div>


                        <div class="h-full w-full pr-6">
                            <div class="mb-6 max-w-3xl text-center sm:text-center md:mx-auto md:mb-12">

                                <h2
                                    class="font-heading mb-4 knewave-regular font-bold tracking-tight text-gray-50 dark:text-white text-3xl sm:text-5xl">
                                    JOIN US
                                </h2>
                                <div class="mt-5 max-w-3xl text-center mx-auto">
                                    <p v-motion-slide-visible-bottom :delay="300" :duration="800"
                                        class="text-4xl text-yellow-400 great-vibes font-bold dark:text-neutral-400">
                                        Come for the food, <span class="font-serif text-red-700">Stay</span> for
                                        the <span class="font-serif text-green-700">vibes</span>!</p>
                                </div>
                            </div>
                            <ul class="mb-6 md:mb-0">
                                <li class="flex">
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded bg-emerald-700 text-gray-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6">
                                            <path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"></path>
                                            <path
                                                d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="ml-4 mb-4">
                                        <h3 class="mb-2 text-lg font-medium leading-6 text-white dark:text-white">
                                            Our Address
                                        </h3>
                                        <p class="text-gray-300 dark:text-slate-400">24 Lloyds Ave, Ipswich IP1 3HD
                                        </p>
                                        <p class="text-gray-300 dark:text-slate-400">United Kingdom</p>
                                    </div>
                                </li>
                                <li class="flex">
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded bg-emerald-700 text-gray-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6">
                                            <path
                                                d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2">
                                            </path>
                                            <path d="M15 7a2 2 0 0 1 2 2"></path>
                                            <path d="M15 3a6 6 0 0 1 6 6"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4 mb-4">
                                        <h3 class="mb-2 text-lg font-medium leading-6 text-white dark:text-white">
                                            Contact
                                        </h3>
                                        <p class="text-gray-300 dark:text-slate-400">Mobile: 07398 951462</p>
                                        <p class="text-gray-300 dark:text-slate-400">Mail:
                                            info@hardballsmokehouse.com.uk</p>
                                    </div>
                                </li>
                                <li class="flex">
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded bg-emerald-700 text-gray-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6">
                                            <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"></path>
                                            <path d="M12 7v5l3 3"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4 mb-4">
                                        <h3 class="mb-2 text-lg font-medium leading-6 text-white dark:text-white">
                                            Working
                                            hours</h3>
                                        <p class="text-gray-300 dark:text-slate-400">Monday
                                            &nbsp;&emsp;&emsp;&nbsp;: 1:00<span class="text-slate-400 text-sm">pm</span>
                                            - 9:30<span class="text-slate-400 text-sm">pm</span></p>
                                        <p class="text-gray-300 dark:text-slate-400">Tuesday
                                            &emsp;&emsp;&nbsp;&nbsp;: 1:00<span class="text-slate-400 text-sm">pm</span>
                                            - 9:30<span class="text-slate-400 text-sm">pm</span></p>
                                        <p class="text-gray-300 dark:text-slate-400">Wednesday &emsp;: 1:00<span
                                                class="text-slate-400 text-sm">pm</span> - 10:30<span
                                                class="text-slate-400 text-sm">pm</span>
                                        </p>
                                        <p class="text-gray-300 dark:text-slate-400">Thursday &emsp;&emsp;&nbsp;:
                                            1:00<span class="text-slate-400 text-sm">pm</span> - 10:30<span
                                                class="text-slate-400 text-sm">pm</span></p>
                                        <p class="text-gray-300 dark:text-slate-400">Friday
                                            &emsp;&emsp;&emsp;&nbsp;&nbsp;: 4:30<span
                                                class="text-slate-400 text-sm">pm</span> -
                                            11:0<span class="text-slate-400 text-sm">pm</span></p>
                                        <p class="text-gray-300 dark:text-slate-400">Saturday &emsp;&emsp;&nbsp;:
                                            1:00<span class="text-slate-400 text-sm">pm</span> - 11:00<span
                                                class="text-slate-400 text-sm">pm</span></p>
                                        <p class="text-gray-300 dark:text-slate-400">Sunday &emsp;&emsp;&emsp;:
                                            1:00<span class="text-slate-400 text-sm">pm</span> - 8:30<span
                                                class="text-slate-400 text-sm">pm</span></p>
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