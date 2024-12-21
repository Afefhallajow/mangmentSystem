<template>
<div>

</div>
</template>

<script>
import axios from "axios";

export default {
  name: "RegisterPage",
  data() {
      return {
        email: '',
        password: '',
      };
    },
    methods: {
      async login() {
        try {
          const response = await axios.post('/login', {
            email: this.email,
            password: this.password,
          });
          localStorage.setItem('auth_token', response.data.token);
          this.$router.push('/home');
        } catch (error) {
          console.error(error.response.data.message);
        }
      },
    }
}
</script>

<style scoped>

</style>