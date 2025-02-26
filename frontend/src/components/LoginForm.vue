<template>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
      <Card class="w-96 shadow-lg rounded-2xl border-t-4 border-green-500">
        <template #content>
          <div class="flex flex-col items-center space-y-4 p-6">
            <h2 class="text-xl font-semibold">Welcome</h2>
            <p class="text-gray-500 text-sm">Sign in to continue</p>
  
            <div class="w-full">
              <!-- Email Input -->
              <label for="email" class="block text-sm font-medium text-white-700">Email</label>
              <InputText id="email" v-model="email" class="w-full mt-1" placeholder="Email address" @blur="validateEmail" />
              <p v-if="emailError" class="text-red-500 text-xs mt-1">Please enter a valid email address.</p>
  
              <!-- Password Input -->
              <label for="password" class="block mt-4 text-sm font-medium text-white-700">Password</label>
              <Password id="password" v-model="password" class="w-full mt-1" toggleMask placeholder="Password" :feedback="false"/>
  
              <!-- Sign In Button -->
              <Button label="Sign In" class="w-full mt-6 bg-green-500 hover:bg-green-600 text-white" />
            </div>
          </div>
        </template>
      </Card>
    </div>
  </template>
  
<script setup lang="ts">
    import { ref } from 'vue';
    import Card from 'primevue/card';
    import InputText from 'primevue/inputtext';
    import Password from 'primevue/password';
    import Checkbox from 'primevue/checkbox';
    import Button from 'primevue/button';

    const email = ref<string>('');
    const password = ref<string>('');
    const rememberMe = ref<boolean>(false);
    const emailError = ref<boolean>(false);

    const validateEmail = () => {
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    emailError.value = !emailPattern.test(email.value);
    };
</script>

<style>
.p-card {
border-radius: 12px;
}
</style>