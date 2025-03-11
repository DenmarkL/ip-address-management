<template>
  <div 
    :class="[
      'h-screen bg-white dark:bg-gray-900 shadow-lg flex flex-col transition-all duration-300 dark:border-gray-700 rounded-xl m-4',
      isCollapsed ? 'w-16' : 'w-56'
    ]"
  >
    <nav class="flex-1 px-2 py-4 border-l border-green-500 rounded-xl">
      <ul class="space-y-2">
        <!-- Dashboard (IP Address Manager) -->
        <li>
          <router-link 
            to="/dashboard/ip-management" 
            class="flex items-center px-4 py-2 rounded-md transition-all"
            :class="[ 
              isCollapsed ? 'justify-center' : 'justify-start',
              route.path.startsWith('/dashboard/ip-management')
                ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' 
                : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800'
            ]"
          >
            <i class="pi pi-database" :class="isCollapsed ? 'text-xl' : 'mr-2'"></i>
            <span :class="isCollapsed ? 'hidden' : 'block'">IP Address Manager</span>
          </router-link>
        </li>

        <!-- UI Components Header (Hide when collapsed) -->
        <li v-if="authStore.isAdmin && !isCollapsed" class="text-xs uppercase text-gray-500 dark:text-gray-400 px-4 pt-4">Admin Settings</li>

        <!-- Audit Logs -->
        <li v-if="authStore.isAdmin">
          <router-link 
            to="/dashboard/audit-logs" 
            class="flex items-center px-4 py-2 rounded-md transition-all"
            :class="[
              isCollapsed ? 'justify-center' : 'justify-start',
              route.path.startsWith('/dashboard/audit-logs') 
                ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' 
                : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800'
            ]"
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
import { useRoute } from "vue-router";
import { useAuthStore } from '@/stores/AuthStore';

const authStore = useAuthStore();
const route = useRoute();
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
