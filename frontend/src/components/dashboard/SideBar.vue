<template>
  <div 
    :class="[
      'h-screen bg-white dark:bg-gray-900 shadow-lg flex flex-col transition-all duration-300 border border-gray-300 dark:border-gray-700 rounded-xl m-4',
      isCollapsed ? 'w-16' : 'w-56'
    ]"
  >
    <nav class="flex-1 px-2 py-4">
      <ul class="space-y-2">
        <!-- Dashboard -->
        <li>
          <router-link 
            to="/" 
            class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md"
            :class="isCollapsed ? 'justify-center' : 'justify-start'"
          >
            <i class="pi pi-database" :class="isCollapsed ? 'text-xl' : 'mr-2'"></i>
            <span :class="isCollapsed ? 'hidden' : 'block'">IP Address Manager</span>
          </router-link>
        </li>

        <!-- UI Components Header (Hide when collapsed) -->
        <li v-if="!isCollapsed" class="text-xs uppercase text-gray-500 dark:text-gray-400 px-4 pt-4">Admin Settings</li>

        <!-- Input -->
        <li>
          <router-link 
            to="/input" 
            class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md"
            :class="isCollapsed ? 'justify-center' : 'justify-start'"
          >
            <i class="pi pi-book" :class="isCollapsed ? 'text-xl' : 'mr-2'"></i>
            <span :class="isCollapsed ? 'hidden' : 'block'">Audit Logs</span>
          </router-link>
        </li>
      </ul>
    </nav>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";

const isCollapsed = ref(localStorage.getItem("sidebarCollapsed") === "true");

const toggleSidebar = () => {
  isCollapsed.value = !isCollapsed.value;
  localStorage.setItem("sidebarCollapsed", isCollapsed.value);
};

onMounted(() => {
  isCollapsed.value = localStorage.getItem("sidebarCollapsed") === "true";
});

defineExpose({ toggleSidebar });
</script>