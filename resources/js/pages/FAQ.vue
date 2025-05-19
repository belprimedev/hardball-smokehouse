<script setup lang="ts">
import MainLayout from '@/layouts/MainLayout.vue';
import { ref } from 'vue';

const faqs = ref([
    {
        question: 'What are your opening hours?',
        answer: 'We are open from 11:00 AM to 10:00 PM, Monday through Sunday.'
    },
    {
        question: 'Do you take reservations?',
        answer: 'Yes, we accept reservations. You can make a reservation through our website or by calling us directly.'
    },
    {
        question: 'Is there parking available?',
        answer: 'Yes, we have parking available for our customers.'
    },
    {
        question: 'Do you offer takeout or delivery?',
        answer: 'Yes, we offer both takeout and delivery services. You can place your order through our website or by calling us.'
    },
    {
        question: 'Do you accommodate dietary restrictions?',
        answer: 'Yes, we offer vegetarian and gluten-free options. Please inform our staff about any dietary restrictions when ordering.'
    }
]);

const openFaqs = ref<number[]>([]);

const toggleFaq = (index: number) => {
    const position = openFaqs.value.indexOf(index);
    if (position === -1) {
        openFaqs.value.push(index);
    } else {
        openFaqs.value.splice(position, 1);
    }
};
</script>

<template>
    <MainLayout>
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-8">Frequently Asked Questions</h1>
                    <p class="text-xl text-gray-600 dark:text-gray-300">Find answers to common questions about our restaurant</p>
                </div>
                
                <div class="mt-12 space-y-4">
                    <div v-for="(faq, index) in faqs" :key="index" 
                         class="bg-white dark:bg-gray-800 rounded-lg shadow-lg">
                        <button @click="toggleFaq(index)"
                                class="w-full px-6 py-4 text-left focus:outline-none">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ faq.question }}
                                </h3>
                                <svg class="w-6 h-6 text-gray-500 dark:text-gray-400" 
                                     :class="{ 'transform rotate-180': openFaqs.includes(index) }"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </button>
                        <div v-if="openFaqs.includes(index)" 
                             class="px-6 pb-4 text-gray-600 dark:text-gray-300">
                            {{ faq.answer }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template> 