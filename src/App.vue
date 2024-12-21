<template>
  <div class="background">
  <app-error/>
  <app-header v-if="showHeaderAndSidebar"/>
  <router-view/>
  <side-bar v-if="showHeaderAndSidebar"/>
  </div>
</template>

<style>
/*
.background {
  height: 100vh;
  width: 100vw;
  background-color: #f0f0f04f; !* Set your desired styles *!
}
*/
:global(body) {
  background-color: black;
}
#app {

  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}

</style>
<script>
import AppHeader from "@/components/Global/Header";
import SideBar from "@/components/Global/SideBar";
import AppError from "@/components/Global/Error.vue";

export default {
  components: {AppError, SideBar, AppHeader},
  computed: {
    showHeaderAndSidebar() {
      // Hide Header and Sidebar for specific routes
      const noHeaderSidebarRoutes = ["login", "register", "Inventory/product/"]; // Add your login-related routes here
      return !noHeaderSidebarRoutes.includes(this.$route.name);
    },
  }
}
</script>