import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import Button from "primevue/button"
import Aura from '@primeuix/themes/aura';


import App from './App.vue'
import router from './router'
import PrimeVue from 'primevue/config';

const app = createApp(App)

app.use(createPinia())
app.use(router)
app.use(PrimeVue, {
    theme: {
        preset: Aura
    }
});
app.component('Button', Button);

app.mount('#app')
