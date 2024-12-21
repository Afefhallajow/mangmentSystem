<template>
  <div v-if="errorMessage" class="alert alert-danger" style="position: fixed; top: 10px; left: 50%; transform: translateX(-50%); z-index: 1000;">
    {{ errorMessage }}
  </div>
</template>

<script>
import {inject} from "vue";

export default {
  name: "AppError",
  data() {
    return {
      errorMessage: null, // Holds the current error message

    };
  },
  created() {
    // Inject the globally provided error handler
    const axiosErrorHandler = inject('axiosErrorHandler');

    if (axiosErrorHandler) {
      axiosErrorHandler((message) => {
        this.errorMessage = message;

        // Auto-hide the error after 5 seconds
        setTimeout(() => {
          this.errorMessage = null;
        }, 5000);
      });
    } else {
      console.error('axiosErrorHandler not found. Ensure the plugin is properly registered.');
    }
  },
};
</script>