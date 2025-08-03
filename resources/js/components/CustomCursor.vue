<script setup lang="ts">
import { ref, onMounted, onUnmounted, nextTick } from 'vue';

const cursor = ref<HTMLElement | null>(null);
const cursorTrail = ref<HTMLElement | null>(null);
const isVisible = ref(false);
const isDesktop = ref(false);

// Mouse position state
const mouseX = ref(0);
const mouseY = ref(0);

// Animation frame ID for smooth updates
let animationFrameId: number | null = null;
let timeout: number | null = null;

// Check if device is desktop
const checkDesktop = () => {
  isDesktop.value = window.innerWidth > 768 && !('ontouchstart' in window);
};

// Update mouse position
const updateMousePosition = (e: MouseEvent) => {
  mouseX.value = e.clientX;
  mouseY.value = e.clientY;
};

// Smooth update function using requestAnimationFrame
const updateCursorPosition = () => {
  if (cursor.value && cursorTrail.value && isDesktop.value && isVisible.value) {
    const halfCursorSize = 12;
    const halfTrailSize = 40;
    const scaleMin = 0.35;
    const scaleMax = 1.0;
    
    // Calculate positions so dot is centered in circle
    const finalX = mouseX.value - halfCursorSize;
    const finalY = mouseY.value - halfCursorSize;
    const finalTrailX = mouseX.value - halfTrailSize;
    const finalTrailY = mouseY.value - halfTrailSize;
    
    // Update main cursor
    cursor.value.style.transform = `translate(${finalX}px, ${finalY}px) scale(${scaleMin})`;
    
    // Update trail with delay - ensure it's centered around the dot
    setTimeout(() => {
      if (cursorTrail.value && isVisible.value) {
        cursorTrail.value.style.transform = `translate(${finalTrailX}px, ${finalTrailY}px) scale(${scaleMin})`;
      }
    }, 100);
    
    // Clear existing timeout
    if (timeout !== null) {
      window.clearTimeout(timeout);
    }
    
    // Set timeout for scale animation
    timeout = window.setTimeout(() => {
      if (cursor.value && isVisible.value) {
        cursor.value.style.transform = `translate(${finalX}px, ${finalY}px) scale(${scaleMax})`;
      }
      if (cursorTrail.value && isVisible.value) {
        cursorTrail.value.style.transform = `translate(${finalTrailX}px, ${finalTrailY}px) scale(${scaleMax})`;
      }
    }, 250);
    
    // Ensure cursors are visible
    cursor.value.style.opacity = '1';
    cursorTrail.value.style.opacity = '1';
  }
  
  // Continue animation
  animationFrameId = requestAnimationFrame(updateCursorPosition);
};

// Handle mouse enter
const handleMouseEnter = () => {
  if (isDesktop.value) {
    isVisible.value = true;
    // Start smooth animation immediately
    if (!animationFrameId) {
      animationFrameId = requestAnimationFrame(updateCursorPosition);
    }
    // Ensure cursors are visible immediately
    if (cursor.value && cursorTrail.value) {
      cursor.value.style.opacity = '1';
      cursorTrail.value.style.opacity = '1';
    }
  }
};

// Initialize cursor immediately when component mounts
const initializeCursorImmediately = () => {
  if (isDesktop.value && cursor.value && cursorTrail.value) {
    isVisible.value = true;
    cursor.value.style.opacity = '1';
    cursorTrail.value.style.opacity = '1';
    // Start animation immediately
    if (!animationFrameId) {
      animationFrameId = requestAnimationFrame(updateCursorPosition);
    }
  }
};

// Force cursor to appear immediately on page load
const forceCursorAppearance = () => {
  if (isDesktop.value) {
    isVisible.value = true;
    // Set initial position to center of screen
    mouseX.value = window.innerWidth / 2;
    mouseY.value = window.innerHeight / 2;
    
    if (cursor.value && cursorTrail.value) {
      cursor.value.style.opacity = '1';
      cursorTrail.value.style.opacity = '1';
      // Start animation immediately
      if (!animationFrameId) {
        animationFrameId = requestAnimationFrame(updateCursorPosition);
      }
    }
  }
};

// Handle mouse leave
const handleMouseLeave = () => {
  isVisible.value = false;
  // Hide cursors
  if (cursor.value) {
    cursor.value.style.opacity = '0';
  }
  if (cursorTrail.value) {
    cursorTrail.value.style.opacity = '0';
  }
  // Stop animation
  if (animationFrameId) {
    cancelAnimationFrame(animationFrameId);
    animationFrameId = null;
  }
  // Clear timeout
  if (timeout !== null) {
    window.clearTimeout(timeout);
    timeout = null;
  }
};

// Handle form element interactions
const handleFormElementEnter = () => {
  isVisible.value = false;
  if (cursor.value) {
    cursor.value.style.opacity = '0';
  }
  if (cursorTrail.value) {
    cursorTrail.value.style.opacity = '0';
  }
};

const handleFormElementLeave = () => {
  if (isDesktop.value) {
    isVisible.value = true;
    // Ensure cursors are visible
    if (cursor.value && cursorTrail.value) {
      cursor.value.style.opacity = '1';
      cursorTrail.value.style.opacity = '1';
    }
  }
};

// Handle click animation
const handleClick = () => {
  if (cursor.value && cursorTrail.value && isDesktop.value) {
    cursor.value.classList.add('scale-150');
    cursorTrail.value.classList.add('scale-150');
    setTimeout(() => {
      cursor.value?.classList.remove('scale-150');
      cursorTrail.value?.classList.remove('scale-150');
    }, 150);
  }
};

// Handle hover over interactive elements
const handleInteractiveHover = () => {
  if (cursor.value && cursorTrail.value && isDesktop.value) {
    cursor.value.classList.add('scale-125');
    cursorTrail.value.classList.add('scale-125');
  }
};

const handleInteractiveLeave = () => {
  if (cursor.value && cursorTrail.value && isDesktop.value) {
    cursor.value.classList.remove('scale-125');
    cursorTrail.value.classList.remove('scale-125');
  }
};

// Force cursor visibility check
const forceCursorVisibility = () => {
  if (isDesktop.value && isVisible.value && cursor.value && cursorTrail.value) {
    cursor.value.style.opacity = '1';
    cursorTrail.value.style.opacity = '1';
  }
};

// Setup event listeners
const setupEventListeners = () => {
  // Mouse movement
  document.addEventListener('mousemove', updateMousePosition);
  document.addEventListener('mouseenter', handleMouseEnter);
  document.addEventListener('mouseleave', handleMouseLeave);
  document.addEventListener('click', handleClick);
  
  // Page load and navigation events
  window.addEventListener('load', initializeCursorImmediately);
  document.addEventListener('DOMContentLoaded', initializeCursorImmediately);
  
  // Inertia.js navigation events (if using Inertia)
  if ((window as any).Inertia) {
    window.addEventListener('inertia:start', () => {
      isVisible.value = false;
    });
    window.addEventListener('inertia:finish', () => {
      setTimeout(initializeCursorImmediately, 100);
    });
  }

  // Form elements
  const formElements = document.querySelectorAll('input, textarea, select, button, a, [role="button"], [type="submit"], [type="button"], label');
  formElements.forEach(element => {
    element.addEventListener('mouseenter', handleFormElementEnter);
    element.addEventListener('mouseleave', handleFormElementLeave);
  });

  // Interactive elements
  const interactiveElements = document.querySelectorAll('a, button, [role="button"], .cursor-pointer, [data-interactive]');
  interactiveElements.forEach(element => {
    element.addEventListener('mouseenter', handleInteractiveHover);
    element.addEventListener('mouseleave', handleInteractiveLeave);
  });

  // Handle scroll events
  window.addEventListener('scroll', () => {
    if (cursor.value) {
      cursor.value.style.opacity = '0';
    }
    if (cursorTrail.value) {
      cursorTrail.value.style.opacity = '0';
    }
    // Restore visibility after scroll
    setTimeout(forceCursorVisibility, 100);
  });

  // Handle page visibility changes
  document.addEventListener('visibilitychange', () => {
    if (document.visibilityState === 'visible') {
      setTimeout(forceCursorVisibility, 50);
    }
  });

  // Handle focus events
  window.addEventListener('focus', forceCursorVisibility);
  window.addEventListener('blur', () => {
    if (cursor.value) cursor.value.style.opacity = '0';
    if (cursorTrail.value) cursorTrail.value.style.opacity = '0';
  });

  // Handle dynamic content
  const observer = new MutationObserver(() => {
    const newFormElements = document.querySelectorAll('input, textarea, select, button, a, [role="button"], [type="submit"], [type="button"], label');
    const newInteractiveElements = document.querySelectorAll('a, button, [role="button"], .cursor-pointer, [data-interactive]');
    
    newFormElements.forEach(element => {
      element.addEventListener('mouseenter', handleFormElementEnter);
      element.addEventListener('mouseleave', handleFormElementLeave);
    });
    
    newInteractiveElements.forEach(element => {
      element.addEventListener('mouseenter', handleInteractiveHover);
      element.addEventListener('mouseleave', handleInteractiveLeave);
    });
  });

  observer.observe(document.body, {
    childList: true,
    subtree: true
  });
};

// Cleanup event listeners
const cleanupEventListeners = () => {
  document.removeEventListener('mousemove', updateMousePosition);
  document.removeEventListener('mouseenter', handleMouseEnter);
  document.removeEventListener('mouseleave', handleMouseLeave);
  document.removeEventListener('click', handleClick);
  
  // Stop animation
  if (animationFrameId) {
    cancelAnimationFrame(animationFrameId);
    animationFrameId = null;
  }
  
  // Clear timeout
  if (timeout !== null) {
    window.clearTimeout(timeout);
    timeout = null;
  }
};

onMounted(() => {
  checkDesktop();
  setupEventListeners();
  
  // Initialize cursor immediately
  initializeCursorImmediately();
  forceCursorAppearance();
  
  // Recheck on resize
  window.addEventListener('resize', () => {
    checkDesktop();
    if (isDesktop.value) {
      initializeCursorImmediately();
      forceCursorAppearance();
    }
  });

  // Force visibility immediately and after delays
  setTimeout(forceCursorVisibility, 50);
  setTimeout(forceCursorVisibility, 200);
  setTimeout(forceCursorVisibility, 500);
  
  // Additional immediate checks
  nextTick(() => {
    initializeCursorImmediately();
    forceCursorAppearance();
  });
  
  // Force appearance after a longer delay as backup
  setTimeout(forceCursorAppearance, 1000);
});

onUnmounted(() => {
  cleanupEventListeners();
  window.removeEventListener('resize', checkDesktop);
});
</script>

<template>
  <div v-if="isDesktop" class="custom-cursor-container">
    <!-- Main cursor -->
    <span
      ref="cursor"
      class="custom-cursor-main"
      :style="{ opacity: isVisible ? '1' : '0' }"
    ></span>
    
    <!-- Cursor trail -->
    <span
      ref="cursorTrail"
      class="custom-cursor-trail"
      :style="{ opacity: isVisible ? '1' : '0' }"
    ></span>
  </div>
</template>

<style scoped>
.custom-cursor-container {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  pointer-events: none;
  z-index: 999999;
}

.custom-cursor-main {
  background-color: #039739;
  width: 24px;
  height: 24px;
  transform-origin: center;
  top: 0;
  left: 0;
  position: fixed;
  mix-blend-mode: exclusion;
  pointer-events: none;
  will-change: transform;
  transition: transform linear 0.125s, opacity 0.125s ease-in 0.125s;
  border-radius: 100%;
  opacity: 0;
  z-index: 999999;
}

.custom-cursor-trail {
  width: 80px;
  height: 80px;
  border-radius: 100%;
  border: 4px solid #eacc08;
  top: 0px;
  left: 0px;
  position: fixed;
  mix-blend-mode: exclusion;
  pointer-events: none;
  will-change: transform;
  transition: transform linear 0.125s, opacity 0.125s ease-in 0.125s;
  border-radius: 100%;
  opacity: 0;
  z-index: 999999;
}

/* Hide system cursor on desktop */
@media (min-width: 769px) {
  :global(body) {
    cursor: none !important;
  }
  
  :global(input, textarea, select, button, a, [role="button"], [type="submit"], [type="button"], label) {
    cursor: auto !important;
  }
}

/* Ensure cursor is visible on mobile */
@media (max-width: 768px) {
  :global(body) {
    cursor: auto !important;
  }
}
</style> 