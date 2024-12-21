<template>
  <div class="container py-4 bg-light">
    <!-- Product Showcase Section -->
    <div class="row g-4">
      <!-- Image Gallery -->
      <div class="col-md-4">
        <!-- Main Image -->
        <div class="card shadow-sm">
          <img :src="'http://localhost:8000' + product.attachments[0]" alt="Product Image" class="img-fluid card-img-top" />
        </div>

        <!-- Thumbnail Images -->
        <div class="row g-2 mt-3">
          <div v-for="(group, groupIndex) in chunkedAttachments" :key="groupIndex" class="d-flex">
            <div v-for="(attachment, index) in group" :key="index" class="col-4">
              <img
                  :src="'http://localhost:8000' + attachment"
                  alt="Thumbnail Image"
                  class="img-thumbnail img-fluid cursor-pointer"
                  @click="openModal('http://localhost:8000' + attachment)"
              />
            </div>
          </div>
        </div>
      </div>

      <!-- Product Details -->
      <div class="col-md-8">
        <div class="card shadow-sm">
          <div class="card-header bg-primary text-white">
            <h2 class="mb-0">{{ product.name }}</h2>
          </div>
          <div class="card-body">
            <!-- Colors -->
            <div v-if="product.colors.length > 0" class="mb-3">
              <label class="form-label" for="color">Color:</label>
              <multiselect
                  v-model="selectedColor"
                  :options="product.colors"
                  placeholder="Select a color"
                  class="form-control"
              />
            </div>

            <!-- Quantity -->
            <div class="mb-3">
              <label for="quantity" class="form-label">Quantity:</label>
              <input type="number" v-model="quantity" id="quantity" class="form-control" placeholder="Enter quantity" />
            </div>

            <!-- Description -->
            <p class="text-muted">{{ product.description }}</p>
          </div>
          <div class="card-footer d-flex justify-content-between align-items-center bg-light">
            <h4 class="text-primary mb-0">Price: ${{ product.price }}</h4>
            <button class="btn btn-success" @click="addToCart">
              <i class="fas fa-shopping-cart"></i> Add to Cart
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import Multiselect from 'vue-multiselect';
import 'vue-multiselect/dist/vue-multiselect.min.css';

export default {
  components: { Multiselect },
  name: "testTest",
  data() {
    return {
      product: {
        attachments: [],
        colors: [] // Initialize colors as an empty array
      },
      selectedColor: [],
      quantity: 0,
    };
  },
  computed: {
    // Create chunks of 3 images for each row
    chunkedAttachments() {
      const chunkSize = 3;
      const chunks = [];
      for (let i = 1; i < this.product.attachments.length; i += chunkSize) {
        chunks.push(this.product.attachments.slice(i, i + chunkSize));
      }
      return chunks;
    }
  },
  mounted() {
    // Fetch product details
    const id = this.$route.params.id;
    axios.get(`inventory/product/getOne/${id}`).then((response) => {
      const data = response.data.data;
      this.product = {
        ...data,
        colors: data.colors || ["red", "yellow"], // Default to an empty array if undefined
      };
    }).catch((error) => {
      console.error("Error fetching product data:", error);
    });
  },
  methods: {
    addToCart() {
      const cartItem = {
        id: this.product.id, // Ensure unique id is passed
        name: this.product.name,
        price: this.product.price,
        color: this.selectedColor,
        quantity: this.quantity,
      };

      // Call the mutation in the `cart` module
      this.$store.commit('cart/setCart', cartItem);
      alert("Item added to cart!");
    },
  },
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style scoped>
</style>
