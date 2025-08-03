<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
</script>

<template>
  <Head title="Custom Cursor Test" />
  
  <div class="min-h-screen bg-gray-100 dark:bg-gray-900 p-8">
    <div class="max-w-4xl mx-auto">
      <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-8">
        Custom Cursor Test Page
      </h1>
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Test Section 1: Basic Interaction -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
          <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">
            Basic Interaction Test
          </h2>
          <p class="text-gray-600 dark:text-gray-300 mb-4">
            Move your mouse around this area. You should see a small emerald circle with a dot following your cursor.
          </p>
          <div class="h-32 bg-gray-200 dark:bg-gray-700 rounded flex items-center justify-center">
            <span class="text-gray-500 dark:text-gray-400">Move mouse here</span>
          </div>
        </div>

        <!-- Test Section 2: Interactive Elements -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
          <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">
            Interactive Elements Test
          </h2>
          <p class="text-gray-600 dark:text-gray-300 mb-4">
            Hover over these elements. The cursor should scale up slightly.
          </p>
          <div class="space-y-3">
            <button class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded transition-colors">
              Hover over this button
            </button>
            <a href="#" class="text-emerald-600 hover:text-emerald-700 underline block">
              Hover over this link
            </a>
            <div class="cursor-pointer bg-gray-200 dark:bg-gray-700 p-3 rounded">
              Hover over this div
            </div>
          </div>
        </div>

        <!-- Test Section 3: Form Elements -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
          <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">
            Form Elements Test
          </h2>
          <p class="text-gray-600 dark:text-gray-300 mb-4">
            Hover over form elements. The custom cursor should disappear and show the system cursor.
          </p>
          <div class="space-y-3">
            <input 
              type="text" 
              placeholder="Hover over this input" 
              class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500"
            >
            <textarea 
              placeholder="Hover over this textarea" 
              class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500"
              rows="3"
            ></textarea>
            <select class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500">
              <option>Hover over this select</option>
              <option>Option 1</option>
              <option>Option 2</option>
            </select>
          </div>
        </div>

        <!-- Test Section 4: Click Animation -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
          <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">
            Click Animation Test
          </h2>
          <p class="text-gray-600 dark:text-gray-300 mb-4">
            Click anywhere on this area. The cursor should scale up and animate.
          </p>
          <div class="h-32 bg-gray-200 dark:bg-gray-700 rounded flex items-center justify-center cursor-pointer">
            <span class="text-gray-500 dark:text-gray-400">Click here to test animation</span>
          </div>
        </div>
      </div>

      <!-- Instructions -->
      <div class="mt-8 bg-blue-50 dark:bg-blue-900/20 p-6 rounded-lg">
        <h3 class="text-xl font-semibold text-blue-900 dark:text-blue-100 mb-3">
          Testing Instructions:
        </h3>
        <ul class="text-blue-800 dark:text-blue-200 space-y-2">
          <li>• <strong>Desktop only:</strong> The custom cursor only appears on screens wider than 768px</li>
          <li>• <strong>Mouse movement:</strong> The emerald circle should follow your mouse smoothly</li>
          <li>• <strong>Hover effects:</strong> Cursor scales up (1.25x) on interactive elements</li>
          <li>• <strong>Click animation:</strong> Cursor scales up (1.5x) and animates on click</li>
          <li>• <strong>Form elements:</strong> Custom cursor disappears, system cursor shows</li>
          <li>• <strong>Mobile:</strong> Custom cursor is hidden on mobile devices</li>
        </ul>
      </div>

      <!-- Troubleshooting -->
      <div class="mt-6 bg-yellow-50 dark:bg-yellow-900/20 p-6 rounded-lg">
        <h3 class="text-xl font-semibold text-yellow-900 dark:text-yellow-100 mb-3">
          If the custom cursor is not visible:
        </h3>
        <ul class="text-yellow-800 dark:text-yellow-200 space-y-2">
          <li>• Check that you're on a desktop device (not mobile)</li>
          <li>• Ensure your browser window is wider than 768px</li>
          <li>• Check the browser console for any JavaScript errors</li>
          <li>• Verify that both development servers are running</li>
          <li>• Try refreshing the page</li>
        </ul>
      </div>
    </div>
  </div>
</template> 