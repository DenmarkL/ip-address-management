<template>
  <div class="h-screen flex flex-col">
    <TopBar class="fixed top-0 left-0 right-0 h-16 bg-gray-900 text-white shadow-md z-10" />

    <div class="flex flex-1 pt-16">
      <SideBar ref="sidebarRef"/>

      <!-- Main Content -->
      <div class="flex-1 p-6 overflow-auto">
        <router-view />
      </div>
    </div>
  </div>
</template>


<script setup>
import { ref, provide, onMounted } from "vue";
import TopBar from "@/components/dashboard/TopBar.vue";
import SideBar from "@/components/dashboard/SideBar.vue";

const sidebarRef = ref(null);

provide("toggleSidebar", () => {
  if (sidebarRef.value && sidebarRef.value.toggleSidebar) {
    sidebarRef.value.toggleSidebar();
  }
});

provide("toggleSidebar", () => {
  sidebarRef.value.toggleSidebar();
});


</script>
