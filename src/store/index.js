// store/index.js
import createPersistedState from 'vuex-persistedstate';
import Cookies from 'js-cookie';
import { createStore } from 'vuex'; // Use createStore in Vue 3
import permissions from '../services/permission';
import cart from "@/services/cart";
import user from "@/services/userType";

export default createStore({
    modules: {
        permissions, // Register the permissions module
        cart,
        user,
    },
    plugins: [
        createPersistedState({
            key: 'my-cart', // The key under which state is stored in cookies
            storage: {
                // Define how to get, set, and remove data in cookies
                getItem: (key) => Cookies.get(key),
                setItem: (key, value) => Cookies.set(key, value, { expires: 7, secure: true }),
                removeItem: (key) => Cookies.remove(key),
            },
            paths: ['cart'], // Persist only the cart module
        }),
    ],
});
