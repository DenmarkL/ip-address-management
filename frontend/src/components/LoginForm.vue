<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <Card class="w-96 shadow-lg rounded-2xl border-t-4 border-green-500">
      <template #content>
        <div class="flex flex-col items-center space-y-4 p-6">
          <h2 class="text-xl font-semibold">Welcome</h2>
          <p class="text-gray-500 text-sm">Sign in to continue</p>
          <div class="w-full">
            <!-- Email Input -->
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <InputText 
              id="email" 
              v-model="email" 
              class="w-full mt-1" 
              placeholder="Email address" 
              @blur="validateEmail" 
              :class="{ 'border-red-500': emailError }"
            />
            <p v-if="emailError" class="text-red-500 text-xs mt-1">Please enter a valid email address.</p>

            <!-- Password Input -->
            <label for="password" class="block mt-4 text-sm font-medium text-gray-700">Password</label>
            <Password 
              id="password" 
              v-model="password" 
              class="w-full mt-1" 
              toggleMask 
              placeholder="Password" 
              :feedback="false"
              :class="{ 'border-red-500': passwordError }"
            />
            <p v-if="passwordError" class="text-red-500 text-xs mt-1">Password is required.</p>

            <!-- Error Message -->
            <p v-if="loginError" class="text-red-500 text-sm font-medium">
              {{ loginError }}
            </p>
            
            <!-- Sign In Button -->
            <Button 
              label="Sign In" 
              class="w-full mt-6 bg-green-500 hover:bg-green-600 text-white" 
              @click="login" 
              :disabled="loading"
            />
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
import Button from 'primevue/button';
import api from "../services/auth";
import { useRouter } from "vue-router";
import axios from "axios";
import { useAuthStore } from '@/stores/AuthStore';

const email = ref<string>("");
const password = ref<string>("");
const emailError = ref<boolean>(false);
const passwordError = ref<boolean>(false);
const loginError = ref<string | null>(null);
const loading = ref<boolean>(false);
const router = useRouter();
const authStore = useAuthStore();

const validateEmail = () => {
  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  emailError.value = !emailPattern.test(email.value);
};

const login = async () => {
  loginError.value = null;
  passwordError.value = password.value.trim() === "";

  if (emailError.value || passwordError.value) {
    return;
  }

  loading.value = true;

  try {
    const response = await api.post<{ access_token: string, expires_in: string }>("/login", {
      email: email.value,
      password: password.value
    });

    // Store the access token using the same key as in Axios interceptor
    localStorage.setItem("accessToken", response.data.access_token);
    localStorage.setItem('token_expiration', response.data.expires_in);

    await authStore.fetchUserRole();
    
    router.push("/");
  } catch (error: any) {
    if (axios.isAxiosError(error)) {
      if (error.response?.status === 401) {
        loginError.value = "Invalid email or password. Please try again.";
      } else {
        loginError.value = "An error occurred. Please try again later.";
      }
    } else {
      loginError.value = "Network error. Please check your connection.";
    }
  } finally {
    loading.value = false;
  }
};
</script>

<style>
.p-card {
  border-radius: 12px;
}
.border-red-500 {
  border-color: #f87171 !important;
}
</style>
