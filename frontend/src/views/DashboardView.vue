<template>
    <TopBar />
    <SideBar ref="sidebarRef" />
    <router-view />
</template>

<script setup>
import { ref, provide } from "vue";
import TopBar from '@/components/dashboard/TopBar.vue';
import SideBar from '@/components/dashboard/SideBar.vue';

const sidebarRef = ref(null);

provide("toggleSidebar", () => {
  sidebarRef.value.toggleSidebar();
});
</script>