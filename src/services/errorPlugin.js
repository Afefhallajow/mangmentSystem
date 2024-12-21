export default {
    install(app) {
        let errorCallback = null;

        // Provide a method for registering a callback
        app.provide('axiosErrorHandler', (callback) => {
            errorCallback = callback;
        });

        // Provide a method to trigger the error
        app.config.globalProperties.triggerAxiosError = (message) => {
            if (errorCallback) {
                errorCallback(message);
            }
        };
    },
};