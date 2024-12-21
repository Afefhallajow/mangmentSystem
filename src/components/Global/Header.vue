<template>
  <nav class="header">
    <div class="logo">
      <img alt="logo-photo" class="logo-photo" src="@/assets/logo.png">
      <h2>Afef Vue</h2>
    </div>
    <div class="nav-content">
      <ul>
        <li>
        <router-link to="/">Home</router-link>
        </li> |
        <li>
          <router-link to="/about">About</router-link>
        </li> |
        <li v-show="isAuth()">
          <loguot></loguot>
        </li>

      </ul>
    </div>
  </nav>
</template>

<script>
import Loguot from "@/components/Global/Loguot";
export default {
name: "AppHeader",
  components: {Loguot},
  methods:{
  isAuth:function (){
    const isAuth = localStorage.getItem('auth_token');
    return isAuth !== null
  }
  }

}

</script>

<style scoped>

</style>