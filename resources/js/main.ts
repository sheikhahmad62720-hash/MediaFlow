import { createApp } from 'vue';
import { createPinia } from 'pinia';
import App from '@/App.vue';
import { setupRouter } from '@/router/index';
import { useThemeStore } from '@/stores/theme';
import { useAuthStore } from '@/stores/auth';

import '../css/app.css';

const pinia = createPinia();
const app = createApp(App);

app.use(pinia);

const router = setupRouter(pinia);
app.use(router);

useThemeStore(pinia).apply();

app.mount('#app');

useAuthStore(pinia).me();