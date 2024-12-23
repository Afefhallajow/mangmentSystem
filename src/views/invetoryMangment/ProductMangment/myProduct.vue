<template>
  <div class="container-fluid py-4">
    <!-- Row containing cards -->
    <div class="row g-4 justify-content-start">
      <div class="col-md-4" v-for="item in items" :key="item.id">
        <div class="card h-100 shadow-sm">
          <img @click="showProduct(item.id)"
               v-if="item.attachments && item.attachments.length > 0"
               :src="'http://localhost:8000' + item.attachments[0]"
               class="card-img-top img-card"
               alt="Product Image"
          />
          <img
              v-else
              @click="showProduct(item.id)"
              src="@/assets/1.png"
              class="card-img-top img-card"
              alt="Default Image"
          />
          <div class="card-body">
            <h5 class="card-title text-truncate">{{ item.name }}</h5>
            <p class="card-text text-muted text-truncate">
              {{ item.description }}
            </p>
            <p class="card-text fw-bold">
              {{ item.price }} <small class="text-muted">USD</small>
            </p>
            <div class="card-footer d-flex justify-content-between">
            </div>
          </div>
        </div>
      </div>
    </div>
    <nav class="mt-4">
      <ul class="pagination justify-content-center">
        <li class="page-item" :class="{ disabled: currentPage === 1 }">
          <button class="page-link" @click="fetchProducts(currentPage - 1)">Previous</button>
        </li>
        <li
            v-for="page in totalPages"
            :key="page"
            class="page-item"
            :class="{ active: page === currentPage }"
        >
          <button class="page-link" @click="fetchProducts(page)">{{ page }}</button>
        </li>
        <li class="page-item" :class="{ disabled: currentPage === totalPages }">
          <button class="page-link" @click="fetchProducts(currentPage + 1)">Next</button>
        </li>
      </ul>
    </nav>
  </div>

</template>
<script>

import axios from "axios";
import {mapGetters} from "vuex";

export default {
  name: "myProduct",
  data(){
    return{
      items:[]
    }
  },
  mounted() {
    this.fetchElements();
  },
  computed: {
    ...mapGetters(["getUser"]),
  },
  methods: {
    showProduct (elementId) {
      this.$router.push({name: "editProduct" , params: {id: elementId}});
    },
    async fetchElements(){
      const id = this.getUser().id;
      axios.get(`inventory/product/myproduct/${id}`).then((response) => {
        this.items = response.data.data.data;
      }).catch(error => {
        console.log(error);
      });
    }
  }
}
</script>


<style scoped>

</style>