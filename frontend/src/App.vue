<template>
    <ConfirmDialog />
    <router-view />
</template>
  
<script setup lang="ts">
import { onMounted } from 'vue';
import ConfirmDialog from 'primevue/confirmdialog';
// import { authService } from '@/services/refresh';

//Refresh access token when the app loads
// onMounted(async () => {
//     await authService.refreshAccessToken();
// });

onMounted(() => {
  const shouldReload = localStorage.getItem('shouldReload');
  if (shouldReload === 'true') {
    window.location.reload();
    localStorage.removeItem('shouldReload'); // Prevent infinite loop
  }
});
</script>