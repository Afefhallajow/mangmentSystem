export default {
    state: {
        user: String,
    },
    mutations: {
        setUser(state, user) {
            state.user = user;
            console.log("userType: " + state.user)
        },
    },
    getters: {
        getUser: (state) => () => {
            return state.user;
        },
    },
};