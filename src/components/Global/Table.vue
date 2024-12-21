<template>
  <div class="container-fluid">
    <h1>{{title}}</h1>
    <vue-good-table
        :columns="headers"
        :rows="items"
        :search-options="{
          enabled: true
        }"
        :pagination-options="{
            enabled: true,
            mode: 'records',
            perPage: 50,
            position: 'down',
            perPageDropdown: [10, 15, 20],
            dropdownAllowAll: true,
            setCurrentPage: 1,
            nextLabel: 'next',
            prevLabel: 'prev',
            rowsPerPageLabel: 'Rows per page',
            ofLabel: 'of',
            pageLabel: 'page', // for 'pages' mode
            allLabel: 'All',
            infoFn: (params) => `page ${params.firstRecordOnPage}`,}">
      <!-- Scoped slot for the 'Actions' column -->
      <template #table-row="{ row, column }">
        <!-- Render normal cells -->
        <span v-if="column.field !== 'actions'">{{ row[column.field]  }}</span>
        <!-- Custom "Actions" cell -->
        <span v-else>
          <button v-if="hasPermission('edit ' + itemName)" class="btn btn-warning bi bi-pencil m-1" @click="updateProject(row.id)">
            Update
          </button>
          <button v-if="hasPermission('delete ' + itemName)" class="btn btn-danger bi bi-trash m-1" @click="deleteProject(row.id)">
            Delete
          </button>
        </span>
      </template>
    </vue-good-table>
  </div>
</template>


<script>
import axios from "axios";
import { mapGetters } from 'vuex';

export default {
  name: "TableData",
  props : {
    title:{
      type:String,
      required:true
    },
    headers: {
      type: Array, // Define the prop as an Array
      required: true, // Mark it as required
    },
    url: {
      type:String,
      required:true
    },
    editPageName:{
      type:String,
      required:true
    },
    itemName:{
      type:String,
      required:true
    },
    extra:{
      type:String,
      required:false
    }
  },
  computed:{
    ...mapGetters(['hasPermission']),
  },
  data(){
    return{
      items:[]
    }
  },
  mounted() {
    console.log('Mounted lifecycle hook called.');
    this.fetchElements(); // Fetch projects when the component is mounted
  },
  methods: {
    async fetchElements() {
      try {
        // Replace with your API URL
        const response = await axios.get(this.url + '/getAll');

        // Assign the fetched data to the 'projects' array
        this.items = response.data;
      } catch (error) {
        console.error('Error fetching items:', error);
      }
    },
    // Delete a project
    async deleteProject(elmentID) {
      try {
        await axios.delete(this.url + `${elmentID}`);
        // After deleting, refetch the projects
        this.fetchElements();
      } catch (error) {
        console.error('Error deleting item:', error);
      }
    },

    // Update a project (you can replace this with routing to an update page)
    updateProject(elementId) {
      // Example: Redirect to an update page with projectId
      this.$router.push({ name: this.editPageName, params: { id: elementId } });
    },

    formatPrice(value) {
      return value ? parseFloat(value).toFixed(2) : "0.00";
    },
  }
}
</script>


<style scoped>

</style>