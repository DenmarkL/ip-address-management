<template>
    <router-view />
</template>
  
<script setup lang="ts">
import { onMounted } from 'vue';
// import { authService } from '@/services/refresh';

//Refresh access token when the app loads
// onMounted(async () => {
//     await authService.refreshAccessToken();
// });
</script>