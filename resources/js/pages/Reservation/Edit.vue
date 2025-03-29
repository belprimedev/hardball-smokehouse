<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Reservation',
        href: '/reservation.index',
    },
];

const props = defineProps({
reservation: Object
});

const form = useForm({
    customer_name: props.reservation.customer_name,
    customer_phone: props.reservation.customer_phone,
    customer_email: props.reservation.customer_email,
    reservation_date: props.reservation.reservation_date,
    reservation_time: props.reservation.reservation_time,
    number_of_guest: props.reservation.number_of_guest,
    special_request: props.reservation.special_request,
});

const updateReservation = () => {
    form.put(route('reservation.update', { reservation: props.reservation.id }));
};
</script>

<template>
    <Head title="Reservation" />

    <AppLayout :breadcrumbs="breadcrumbs">

        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="bg-white p-6 rounded-xl border w-full">
                <form @submit.prevent="updateReservation">
                    <div class="-mx-3 flex flex-wrap">
                        <div class="w-full px-3 sm:w-1/2">
                        <label for="customer_name" class="mb-3 block text-base font-medium text-[#07074D]">
                            Name
                        </label>
                        <input type="text" v-model="form.customer_name" name="customer_name" id="customer_name" placeholder="James Bond" 
                            class="w-full appearance-none rounded-md border border-green-600/50 bg-white py-3 px-6 text-base font-medium text-green-800 outline-none focus:border-green-600 focus:shadow-md" />
                        </div>
                        <div class="w-full px-3 sm:w-1/2">
                            <div class="mb-5">
                                <label for="number_of_guest" class="mb-3 block text-base font-medium text-[#07074D]">
                                    Number of Guest
                                </label>
                                <input type="number" v-model="form.number_of_guest" name="number_of_guest" id="number_of_guest" placeholder="5" min="1"
                                    class="w-full rounded-md border border-green-600/50 bg-white py-3 px-6 text-base font-medium text-green-800 outline-none focus:border-green-600 focus:shadow-md" />
                            </div>
                        </div>
                    </div>
                    <div class="-mx-3 flex flex-wrap">
                        <div class="w-full px-3 sm:w-1/2">
                            <div class="mb-5">
                                <label for="customer_phone" class="mb-3 block text-base font-medium text-[#07074D]">
                                    Phone
                                </label>
                                <input type="text" v-model="form.customer_phone" name="customer_phone" id="customer_phone"
                                    class="w-full rounded-md border border-green-600/50 bg-white py-3 px-6 text-base font-medium text-green-800 outline-none focus:border-green-600 focus:shadow-md" />
                            </div>
                        </div>
                        <div class="w-full px-3 sm:w-1/2">
                            <div class="mb-5">
                                <label for="customer_email" class="mb-3 block text-base font-medium text-[#07074D]">
                                    Email
                                </label>
                                <input type="email" v-model="form.customer_email" name="customer_email" id="customer_email" placeholder="HannaMoore@gmail.com"
                                    class="w-full rounded-md border border-green-600/50 bg-white py-3 px-6 text-base font-medium text-green-800 outline-none focus:border-green-600 focus:shadow-md" />
                            </div>
                        </div>
                    </div>
                    

                    <div class="-mx-3 flex flex-wrap">
                        <div class="w-full px-3 sm:w-1/2">
                            <div class="mb-5">
                                <label for="date" class="mb-3 block text-base font-medium text-[#07074D]">
                                    Date
                                </label>
                                <input type="date" v-model="form.reservation_date" id="reservation_date" name="reservation_date"
                                    class="w-full rounded-md border border-green-600/50 bg-white py-3 px-6 text-base font-medium text-green-800 outline-none focus:border-green-600 focus:shadow-md" />
                            </div>
                        </div>
                        <div class="w-full px-3 sm:w-1/2">
                            <div class="mb-5">
                                <label for="time" class="mb-3 block text-base font-medium text-[#07074D]">
                                    Time
                                </label>
                                <input type="time" v-model="form.reservation_time" id="reservation_time" name="reservation_time"
                                    class="w-full rounded-md border border-green-600/50 bg-white py-3 px-6 text-green-800 font-medium outline-none focus:border-green-600 focus:shadow-md" />
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="mb-3 block text-base font-medium text-[#07074D]">
                            Special Request
                        </label>
                        <div class="flex items-center space-x-6">
                            <textarea v-model="form.special_request" id="special_request" name="special_request" rows="4" class="py-3 px-4 block w-full border border-green-600/50 rounded-lg text-sm focus:border-green-600 focus:shadow-md disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"></textarea>
                        </div>
                    </div>

                    <div class="py-4">
                            <button type="button" @click="router.get('/reservation')" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Cancel</button>
                            <button type="submit" class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800">Save</button>
                           </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
