<template>
  <div id="app">
    <Toast />
    <LoginCard />
  </div>
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