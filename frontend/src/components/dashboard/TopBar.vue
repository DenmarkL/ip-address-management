<template>
    <nav class="flex items-center justify-between px-6 py-2 bg-white border-b shadow-sm dark:bg-gray-900 dark:text-white">
      <!-- Left Section (Logo + Menu) -->
      <div class="flex items-center space-x-4 flex-1">
        <!-- Hamburger Menu -->
        <Button @click="toggleSidebar" icon="pi pi-bars" class="p-button-text p-button-lg text-gray-600 hover:text-gray-800 dark:hover:text-gray-300"/>
        <!-- Logo -->
        <div class="flex items-center space-x-2">
          <i class="pi pi-compass"></i>
          <span class="text-lg font-semibold text-gray-800 dark:text-white">IP Management</span>
        </div>
      </div>
  
      <!-- Right Section (Icons + Profile) -->
      <div class="relative">
        <!-- Theme Toggle -->
        <Button @click="toggleTheme" :icon="isDarkMode ? 'pi pi-sun' : 'pi pi-moon'" class="p-button-text p-button-lg text-gray-600 hover:text-gray-800 dark:hover:text-gray-300" />
  
        <!-- Profile Avatar -->
        <Avatar icon="pi pi-user" class="bg-green-500 text-white w-8 h-8 cursor-pointer" shape="circle" @click="toggleDropdown"/>
  
        <!-- Dropdown Menu -->
        <div v-if="dropdownOpen" ref="dropdownRef" class="absolute right-0 mt-2 w-40 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-md z-50">
          <ul class="py-2">
            <li>
              <button @click="logout" class="w-full text-left px-4 py-2 text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                <i class="pi pi-sign-out mr-2"></i> Logout
              </button>
            </li>
          </ul>
        </div>
      </div>
  
      <!-- Toast Notifications -->
      <Toast />
    </nav>
  </template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { useToast } from "primevue/usetoast";
import Button from 'primevue/button';
import Avatar from 'primevue/avatar';
import Toast from 'primevue/toast';
import { inject } from "vue";
import { useRouter } from 'vue-router';

const router = useRouter();
const dropdownOpen = ref(false);
const dropdownRef = ref(null);
const toggleSidebar = inject("toggleSidebar");
const isDarkMode = ref(localStorage.getItem("theme") === "dark");
const toast = useToast();
const inputRef = ref(null);

const toggleTheme = () => {
  isDarkMode.value = !isDarkMode.value;

  if (isDarkMode.value) {
    document.documentElement.classList.add("dark");
    document.body.style.backgroundColor = "#1a202c"; // Dark mode background
    localStorage.setItem("theme", "dark");
  } else {
    document.documentElement.classList.remove("dark");
    document.body.style.backgroundColor = "white"; // Light mode background
    localStorage.setItem("theme", "light");
  }

  toast.add({
    severity: "info",
    summary: "Theme Changed",
    detail: isDarkMode.value ? "Dark Mode" : "Light Mode",
    life: 2000,
  });
};

// Toggle dropdown visibility
const toggleDropdown = () => {
  dropdownOpen.value = !dropdownOpen.value;
};

// Logout function
const logout = () => {
  localStorage.removeItem('token'); // Remove token
  router.push('/'); // Redirect to login
};

// Apply stored theme on load
onMounted(() => {
  if (isDarkMode.value) {
    document.documentElement.classList.add("dark");
    document.body.style.backgroundColor = "#1a202c"; 
  } else {
    document.documentElement.classList.remove("dark");
    document.body.style.backgroundColor = "white";
  }

  setTimeout(() => {
    if (inputRef.value && document.activeElement === document.body) {
      inputRef.value.focus();
    }
  }, 100);
});

</script>


<style scoped>
/* Dark mode support */
.dark .p-button-text {
  color: white;
}
</style>
