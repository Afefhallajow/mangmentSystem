<template>
  <div class="container mt-2 ">
    <h1>{{ isEdit ? 'Edit Project' : 'Add Project' }}</h1>
    <form class="" @submit.prevent="handleSubmit">
      <div class="form-group mb-2">
        <label for="name">Project Name</label>
        <input type="text" id="name" v-model="project.name" class="form-control" required/>
      </div>
      <div class="form-group mb-2">
        <label for="content">Description</label>
        <textarea id="content" v-model="project.content" class="form-control" required></textarea>
      </div>
      <button type="submit" class="btn btn-primary ">
        {{ isEdit ? 'Update' : 'Add' }}
      </button>
    </form>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  name: "addProject",
  data() {
    return {
      project: {
        id:'',
        name: '',
        content: '',
      },
      isEdit: false,
    };
  },
  created() {
    const projectId = this.$route.params.id;
    if (projectId) {
      this.isEdit = true;
      this.fetchProject(projectId);
    }
  },
  methods: {
    async fetchProject(id) {
      try {
        const response = await axios.get(`http://localhost:8000/api/project/getone/${id}`);
        this.project = response.data;
      } catch (error) {
        console.error('Error fetching project:', error);
      }
    },
    async handleSubmit() {
      try {
        if (this.isEdit) {
          // Update project
          await axios.post(`http://localhost:8000/api/project/update`, this.project);
          alert('Project updated successfully');
        } else {
          // Add new project
          await axios.post('http://localhost:8000/api/project/save', this.project);
          alert('Project added successfully');
        }
        this.$router.push({ name: 'project' });
      } catch (error) {
        console.error('Error saving project:', error);
      }
    },
  },
};
</script>

<style scoped>

</style>