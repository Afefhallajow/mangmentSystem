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
              <button class="btn btn-primary" @click="addToCart(item)">AddToCart</button>
              <button class="btn btn-danger">Buy</button>
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

export default {
  name: "AppMarket",
  data(){
    return {
      items:[],
      currentPage:1,
      totalPages:1,
    }
  },
  mounted() {
    this.fetchProducts(this.currentPage)
  },
  methods: {
    fetchProducts: function (page) {
      axios.get(`inventory/product/market?page=${page}`).then((response) => {
        const data = response.data.data;
        this.items = data.data; // Products for the current page
        console.log(data)
        this.currentPage = data.pagination.current_page;
        this.totalPages = data.pagination.last_page;
      }).catch((error) => {
        console.error("Error fetching product data:", error);
      });
    },
    showProduct(elementId) {
      // Example: Redirect to an update page with projectId
      this.$router.push({ name: "showProduct", params: { id: elementId } });
    },
    addToCart: function (product) {
      const cartItem = {
        id: product.id, // Ensure unique id is passed
        name: product.name,
        price: product.price,
        color: "black",
        quantity: 1,
      }
      if (product.quantity < this.quantity) {
        alert("quantity should be less than " + product.quantity)
        return;
      }
      // Call the mutation in the `cart` module
      this.$store.commit('cart/setCart', cartItem);
      alert("Item added to cart!");
    },
  }
  }
</script>

<style scoped>
.card {
  border-radius: 10px;
  overflow: hidden;
  transition: transform 0.2s, box-shadow 0.2s;
}

.card:hover {
  transform: scale(1.05);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

.img-card {
  height: 200px;
  object-fit: cover;
}

.card-title {
  font-size: 1.25rem;
  color: #333;
}

.card-text {
  font-size: 0.9rem;
}

.container-fluid {
  background-color: #f8f9fa; /* Light background for better visual appeal */
}</style>