import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'
import { useAuthStore } from '@/stores/AuthStore';

//Primevue
import PrimeVue from 'primevue/config';
import Aura from '@primeuix/themes/aura';
import ToastService from 'primevue/toastservice';
import Toast from 'primevue/toast'
import ConfirmationService from 'primevue/confirmationservice';
import 'primeicons/primeicons.css'; 

const app = createApp(App)
app.use(createPinia())
const authStore = useAuthStore();
authStore.fetchUserRole();

app.use(router)
app.use(PrimeVue, {
    theme: {
        preset: Aura
    }
});
app.use(ConfirmationService);
app.use(ToastService);

app.mount('#app')
