export default {
    state: {
        permissions: [],
    },
    mutations: {
        setPermissions(state, permissions) {
            state.permissions = permissions;
            console.log(state.permissions)
        },
    },
    getters: {
        hasPermission: (state) => (permission) => {
            return state.permissions.includes(permission) || state.permissions.includes('admin');
        },
        getPermission: (state) => () => {
            return state.permission;
        },
    },
};