<template>
    <ConfirmDialog />
    <router-view />
</template>
  
<script setup lang="ts">
import { onMounted } from 'vue';
import ConfirmDialog from 'primevue/confirmdialog';

onMounted(() => {
  const shouldReload = localStorage.getItem('shouldReload');
  if (shouldReload === 'true') {
    window.location.reload();
    localStorage.removeItem('shouldReload'); // Prevent infinite loop
  }
});
</script>