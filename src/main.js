import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import "./css/MainPage/Body.css";
import 'normalize.css';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import 'bootstrap-icons/font/bootstrap-icons.css';

import VueGoodTablePlugin from 'vue-good-table-next';
// import the styles
import 'vue-good-table-next/dist/vue-good-table-next.css'
import axios from "axios";
import errorPlugin from "@/services/errorPlugin";
import store from "@/store";

axios.defaults.baseURL = 'http://localhost:8000/gateway';

axios.interceptors.request.use((config) => {
    const token = localStorage.getItem('auth_token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});


const app = createApp(App)
app.use(errorPlugin);

// Axios interceptor for triggering errors globally
axios.interceptors.response.use(
    (response) => response,
    (error) => {
        const triggerAxiosError = app.config.globalProperties.triggerAxiosError;
        if (triggerAxiosError) {
            triggerAxiosError(error.response?.data?.message || 'An error occurred');
        }
        return Promise.reject(error);
    }
);

app.use(VueGoodTablePlugin).use(store).use(router).mount('#app')
