<template>
  <div class="container-fluid">
    <h1>Projects</h1>
    <vue-good-table
        :columns="headers"
        :rows="projects"
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
        <span v-if="column.field !== 'actions'">{{ row[column.field] }}</span>
        <!-- Custom "Actions" cell -->
        <span v-else>
          <button class="btn btn-warning bi bi-pencil" @click="updateProject(row.id)">
            Update
          </button>
          <button class="btn btn-danger bi bi-trash" @click="deleteProject(row.id)">
            Delete
          </button>
        </span>
      </template>
    </vue-good-table>
<!--    <table>
      <thead>
      <tr>
        <th>Project ID</th>
        <th>Project Name</th>
        <th>Description</th>
        <th></th>
      </tr>
      </thead>
      <tbody>
      &lt;!&ndash; Loop through projects and display each one in a table row &ndash;&gt;
      <tr v-for="project in projects" :key="project.id">
        <td>{{ project.id }}</td>
        <td>{{ project.name }}</td>
        <td>{{ project.content }}</td>
        <td>   <button class="btn btn-danger bi bi-trash" @click="deleteProject(project.id)"></button>
          <button class="btn btn-warning bi bi-pencil" @click="updateProject(project.id)"></button></td>
      </tr>
      </tbody>
    </table>-->
  </div>
</template>

<script>
// Import axios for making API requests
import axios from 'axios';
export default {
  name: 'projectPage',
  data() {
    return {
      projects: [],
      headers: [
        { label: 'Id', field: 'id' },
        { label: 'Name', field: 'name' },
        { label: 'Content', field: 'content' },
        { label: 'Actions', field: 'actions' }
      ],// This will hold the fetched projects
    };
  },
  mounted() {
    this.fetchProjects(); // Fetch projects when the component is mounted
  },
  methods: {
    async fetchProjects() {
      try {
        // Replace with your API URL
        const response = await axios.get('project/project/getAll');

        // Assign the fetched data to the 'projects' array
        this.projects = response.data;
      } catch (error) {
        console.error('Error fetching projects:', error);
      }
    },
    // Delete a project
    async deleteProject(projectId) {
      try {
        await axios.delete(`api/project/delete/${projectId}`);
        // After deleting, refetch the projects
        this.fetchProjects();
      } catch (error) {
        console.error('Error deleting project:', error);
      }
    },

    // Update a project (you can replace this with routing to an update page)
    updateProject(projectId) {
      // Example: Redirect to an update page with projectId
      this.$router.push({ name: 'editProject', params: { id: projectId } });
    }
  }
}
</script>
<!--
<style scoped>
table {
  width: 100%;
  border-collapse: collapse;
}

th, td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: left;
}

th {
  background-color: #f2f2f2;
}
</style>
-->
