<template>
  <div>
    <a class="link-info" @click="logout">Logout</a>
  </div>
</template>
<script>
export default {
  name: "LogOut",
  methods: {
    async logout() {
      try {
        // Make a POST request to the Laravel logout route
//        await axios.post('api/logout');

        // Clear any locally stored authentication data (e.g., token or user info)
        localStorage.removeItem('auth_token'); // Example: remove token
        this.$router.push('/login'); // Redirect to login page
      } catch (error) {
        console.error('Error during logout:', error.response?.data || error.message);
        alert('Failed to log out. Please try again.');
      }
    }
  }
}
</script>

<style scoped>

</style>