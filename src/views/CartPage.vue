<template>
  <div class="container">
    <h1>Your Cart</h1>
    <div class="alert alert-info" v-if="!cart || cart.length === 0">
      <p>Your cart is empty.</p>
    </div>
    <div v-else>
        <vue-good-table
            :columns="headers"
            :rows="cart"
            :search-options="{
          enabled: true}">
          <template #table-row="{row,column }">
            <span v-if="column.field === 'actions'">
              <button class="btn btn-primary" @click="removeFromCart(row)">Remove</button>
            </span>

          </template>

        </vue-good-table>
        <button class="btn btn-primary m-2" @click="clearCart">Clear All</button>

      </div>
    </div>
</template>

<script>
import {mapMutations, mapState} from "vuex";

export default {
  name: "CartPage",
  data(){return{
    headers:[
      { label: 'Id', field: 'id' },
      { label: 'Name', field: 'name' },
      { label: 'price', field: 'price' },
      { label: 'quantity', field: 'quantity' },
      { label: 'color', field: 'color' },
      { label: 'actions', field: 'actions' }
    ],
  }},
  computed: {
    // Map Vuex state to the component
    ...mapState('cart', ['cart']), // Access cart state from the cart module
  },
  methods: {
    ...mapMutations('cart', ['removeFromCart', 'clearCart']),
  }
};
</script>

<style scoped>
/* Add your styles here */
</style>
