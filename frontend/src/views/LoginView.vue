<template>
  <div id="app">
    <Toast />
    <LoginCard />
  </div>
  <router-view />
</template>

<script>
import Toast from 'primevue/toast';
import LoginCard from '../components/LoginForm.vue';

export default {
  components: {
    Toast,
    LoginCard
  }
};
</script>