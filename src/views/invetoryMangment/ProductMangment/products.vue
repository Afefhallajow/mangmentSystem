<template>
<table-data title="Products" edit-page-name="editProduct" url="/inventory/product" item-name="products" :headers="headers" >
</table-data>
</template>

<script>
import TableData from "@/components/Global/Table.vue";

export default {
  name: "ViewProducts",
  components: {TableData},
  data(){
    return{
      headers:[
      { label: 'Id', field: 'id' },
      { label: 'Name', field: 'name' },
      { label: 'Price', field: 'price' },
      { label: 'Quantity', field: 'quantity' },
      { label: 'Actions', field: 'actions' }
  ]
    }
  },
}
</script>


<style scoped>

</style>