<template>
  <div class="container-fluid">
    <h1>Tasks</h1>
    <vue-good-table
        :columns="headers"
        :rows="tasks"
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
        <span v-if="column.field !== 'actions'">
          <span v-if="column.field === 'projectName'">{{ row.project?.name || 'No Project Name' }}</span>
          <span v-else>{{ row[column.field] }}</span>
        </span>        <!-- Custom "Actions" cell -->
        <span v-else>
          <button class="btn btn-warning bi bi-pencil" @click="updateTask(row.id)">
            Update
          </button>
          <button class="btn btn-danger bi bi-trash" @click="deleteTask(row.id)">
            Delete
          </button>
        </span>
      </template>
    </vue-good-table>
  </div>
</template>

<script>
import axios from "axios";
export default {
name: "taskPage",
  data() {
  return{
  tasks: [],
  headers: [
    { label: 'Id', field: 'id' },
    { label: 'Name', field: 'name' },
    { label: 'Content', field: 'content' },
    { label: 'projectName', field: 'projectName' },
    { label: 'Priority', field: 'priority' },
    { label: 'Status', field: 'status' },
    { label: 'Actions', field: 'actions' }
  ],// This will hold the fetched projects
};
},
mounted() {
  this.fetchTasks(); // Fetch projects when the component is mounted
},
methods: {
  async fetchTasks() {
    try {
      // Replace with your API URL
      const response = await axios.get('http://localhost:8000/api/task/getall');

      // Assign the fetched data to the 'projects' array
      this.tasks = response.data;
    } catch (error) {
      console.error('Error fetching projects:', error);
    }
  },
  // Delete a task
  async deleteTask(taskId) {
    try {
      await axios.delete(`http://localhost:8000/api/project/delete/${taskId}`);
      // After deleting, refetch the projects
      this.fetchTasks();
    } catch (error) {
      console.error('Error deleting project:', error);
    }
  },

  // Update a project (you can replace this with routing to an update page)
  updateTask(taskId) {
    // Example: Redirect to an update page with projectId
    this.$router.push({ name: 'editTask', params: { id: taskId } });
  }
}
}
</script>
<style scoped>

</style>