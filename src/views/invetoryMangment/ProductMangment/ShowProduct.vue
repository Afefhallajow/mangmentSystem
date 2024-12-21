<template>
  <div class="container-fluid py-4 mt-4" style="width: 90%; background-color: rgb(255 254 254 / 68%)">
    <!-- Product Showcase Section -->
    <div class="row g-4">
      <!-- Image Gallery (Left) -->
      <div class="col-md-1">
        <!-- Thumbnail Images (Vertical list) -->
        <div v-for="(attachment, index) in product.attachments" :key="index" class="mb-3">
          <img
              :src="'http://localhost:8000' + attachment"
              alt="Thumbnail Image"
              class="img-fluid"
              @click="openModal(attachment)"
              style="cursor: pointer; width: 100%;"
          />
        </div>
      </div>

      <!-- Image Preview (Center) -->
      <div class="col-md-5">
        <!-- Main Image -->
        <div class="card shadow-lg">
          <img
              :src="'http://localhost:8000' + selectedImage"
              alt="Product Image"
              class="img-fluid card-img-top"
              style="width: 100%; height: auto;"
          />

          <span role="button" @click="openFullScree" v-if="selectedImage !== null" class="bi bi-arrows-fullscreen image-full-screen "> </span>
        </div>
      </div>

      <!-- Product Details (Right) -->
      <div class="col-md-6 " style="text-align: start">

            <p class="mb-0 text-muted">{{ product.name }}</p>
            <b class="text-danger mb-0">${{ product.price }}</b>

            <!-- Colors -->
            <div v-if="product.colors.length > 0" class="mb-3">
              <label class="form-label" for="color">Color</label>
              <multiselect
                  v-model="selectedColor"
                  :options="product.colors"
                  multiple="true"
                  placeholder="Select a color"
                  class="form-control"
              />
            </div>

            <!-- Description -->
            <p class="text-muted">{{ product.description }}</p>
        <!-- Quantity -->
        {{product.quantity}}

        <div class="d-flex justify-content-start mt-2 align-items-center gap-4" >
          <div class="quantity-container d-flex align-items-center">
            <!-- Minus Button -->
            <button class="quantity-btn" @click="decrease">-</button>
            <!-- Quantity Display -->
            <input
                type="number"
                v-model="quantity"
                :max="product.quantity"
                id="quantity"
                class="form-control quantity-input text-center"
                min="0"
            />
            <!-- Plus Button -->
            <button class="quantity-btn" @click="increase">+</button>
          </div>
        <button class="btn btn-success " @click="addToCart">
              <i class="fa fa-shopping-cart"></i> Add to Cart
            </button>
        </div>
      </div>
    </div>

    <!-- Image Modal -->
    <div
        class="modal fade"
        id="imageModal"
        tabindex="-1"
        role="dialog"
        aria-labelledby="imageModalLabel"
        aria-hidden="false"
    >

      <div class="modal-dialog modal-fullscreen" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body text-center">
            <img :src="'http://localhost:8000' + selectedImage"
                 alt="Preview"
                 class="img-fluid" />
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
import { Modal } from 'bootstrap';
export default {
  components: { Multiselect },
  name: "ShowProduct",
  data() {
    return {
      product: {
        attachments: [],
        colors: [] // Initialize colors as an empty array
      },
      selectedColor: [],
      quantity: 0,
      selectedImage: null
    };
  },
  mounted() {
    // Fetch product details
    const id = this.$route.params.id;
    axios.get(`inventory/product/getOne/${id}`).then((response) => {
      const data = response.data.data;
      this.product = {
        ...data,
        colors: data.colors || [], // Default to an empty array if undefined
      };
      this.selectedImage = this.product.attachments[0]; // Set default image
    }).catch((error) => {
      console.error("Error fetching product data:", error);
    });
  },
  methods: {
    increase(){
      this.quantity ++ ;
    },
    decrease(){
      this.quantity > 0 ? this.quantity-- : 0 ;
    },
    openFullScree() {
      const modal = new Modal(document.getElementById('imageModal'));
      modal.show();
    },
    openModal(imageUrl) {
      this.selectedImage = imageUrl;
    },
    addToCart() {
      const cartItem = {
        id: this.product.id, // Ensure unique id is passed
        name: this.product.name,
        price: this.product.price,
        color: this.selectedColor,
        quantity: this.quantity,
      };
      if(this.product.quantity < this.quantity) {
        alert("quantity should be less than " + this.product.quantity)
        return;
      }
      // Call the mutation in the `cart` module
      this.$store.commit('cart/setCart', cartItem);
      alert("Item added to cart!");
    },
  },
};
</script>

<style scoped>
/* Customizing shadow for the card */
.card.shadow-lg {
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2); /* Subtle shadow effect */
}

/* Optional: Adding shadow to the main container */
.container-fluid {
  box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15); /* Soft shadow for the container */
  border-radius: 10px; /* Optional: rounded corners */
}

/* Styling the thumbnail images */
.col-md-3 img {
  cursor: pointer;
  transition: transform 0.3s ease;
}
.multiselect{
  padding: 0px;
}
.col-md-3 img:hover {
  transform: scale(1.1); /* Slight zoom effect on hover */
}
.quantity-input{
  border: white;

}
.quantity-container {
  display: flex;
  background-color: white;
  align-items: center;
  border: 1px solid #eceaea;
  border-radius: 23px;
  width: auto;
  height: auto;
  justify-self: center;
  gap: 5px;}

.quantity-btn {
  width: 40px;
  height: 40px;
  border-radius: 50%; /* Half circle */
  background-color: white;
  color: black;
  border: none;
  font-size: 20px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background-color 0.3s ease;
}

.quantity-btn:hover {
  background-color: #dadee1;
}

.quantity-input {
  width: 60px;
  height: 40px;
  text-align: center;
  border: none;
  border-radius: 5px;
  font-size: 16px;
}
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
.image-full-screen{
  width: 20px;
  height: 20px;
  bottom: 12px;
  position: absolute;
  right: 19px;
}
.form-control:focus {
  box-shadow: none !important;
  border-color: #ccc; /* Optional: Set a neutral border color */
  outline: none; /* Ensure no additional outline appears */
}
.quantity-container:focus-within {
  box-shadow: 0 0 10px rgba(0, 123, 255, 0.65);
  border-color: rgba(0, 123, 255, 0.42); /* Optional: Add a border color */
}
</style>
