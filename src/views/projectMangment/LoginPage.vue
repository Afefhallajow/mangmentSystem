<template>
  <div class="container mt-2">
    <form class="" @submit.prevent="login">
      <div class="form-group mb-2 mt-2">
        <label for="email" class="form-label">Email</label>
        <input id="email" class="form-control " v-model="email" type="email" placeholder="Email" />
      </div>
      <div class="form-group mb-2 mt-2">
        <label for="password" class="form-label">Password</label>
        <input id="password" class="form-control" v-model="password" type="password" placeholder="Password" />
      </div>
  <button class="btn btn-primary" type="submit">Login</button>
    </form>
  </div>
</template>

<script>
import axios from "axios";

export default {
name: "LoginPage",
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
        this.$router.push('/');
      } catch (error) {

        console.error(error.response.data.message);
      }
    },
  }

}
</script>

<style scoped>
.container{
  box-shadow: 0px 6px 13px 10px #eceaea;
  padding: 10px;
  background-color: white;
}
</style>