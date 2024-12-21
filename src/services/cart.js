import Cookies from 'js-cookie';
export default {
    namespaced: true,
    state: {
        cart: [],
    },
    mutations: {
        setCart(state, product) {
            state.cart.push(product);
            console.log(state.cart)
        },
        removeFromCart(state, product) {
            console.log(product);
            state.cart = state.cart.filter((item) => (
                item.id !== product.id ||
                item.color !== product.color ||
                item.quantity !== product.quantity));
            Cookies.set('cart', JSON.stringify(state.cart));
        },
        clearCart(state) {
            state.cart = [];
            Cookies.set('cart', JSON.stringify(state.cart));
        },
    },
    actions: {
        loadCartFromCookies({ commit }) {
            const savedCart = Cookies.get('cart');
            if (savedCart) {
                commit('setCart', JSON.parse(savedCart));
            }
        },
    },
    getters: {
        cart(state) {
            console.log(state.cart)
            return state.cart; // Expose the cart state via a getter
        },
    }
};