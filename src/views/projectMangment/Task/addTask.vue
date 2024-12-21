<template>
  <div class="container mt-2 ">
    <h1>{{ isEdit ? 'Edit Task' : 'Add Task' }}</h1>
    <form class="" @submit.prevent="handleSubmit">
      <div class="form-group mb-2">
        <label for="name">Task Name</label>
        <select type="text" id="project" v-model="task.project_id" class="form-control">
          <option value="" disabled>Select a project</option>
          <option v-for="project in projects" :key="project.id" :value="project.id">
            {{project.name}}</option>
        </select>
      </div>
      <div class="form-group mb-2">
        <label for="name">Task Name</label>
        <input type="text" id="name" v-model="task.name" class="form-control" required/>
      </div>
      <div class="form-group mb-2">
        <label for="content">Description</label>
        <textarea id="content" v-model="task.content" class="form-control" required></textarea>
      </div>
      <div class="form-group mb-2">
        <label for="name">Status</label>
        <select type="text" id="status" v-model="task.status" class="form-control">
          <option value="" disabled>Select a status</option>
          <option v-for="status in statuses" :key="status.value" :value="status.value">
            {{status.label}}</option>
        </select>
      </div>
      <div class="form-group mb-2">
        <label for="name">Priority</label>
        <select type="text" id="project" v-model="task.priority" class="form-control">
          <option value="" disabled>Select a Priority</option>
          <option v-for="priority in priorities" :key="priority.value" :value="priority.value">
            {{priority.label}}</option>
        </select>
      </div>

      <button type="submit" class="btn btn-primary ">
        {{ isEdit ? 'Update' : 'Add' }}
      </button>
    </form>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "AddTask",
  data() {
    return {
      task: {
        id:'',
        name: '',
        content: '',
        project_id: '',
        priority: '',
        status: ''
      },
      statuses:[],
      priorities:[],
      projects: [],
      isEdit: false,
    };
  },
  mounted() {
    this.fetchProject(); // Fetch projects when the component is mounted
    this.fetchTaskPriority();
    this.fetchTaskStatus();
  },
  created() {
    const taskId = this.$route.params.id;
    if (taskId) {
      this.isEdit = true;
      this.fetchTask(taskId);
    }
  },
  methods: {
    async fetchProject(){
      try {
        const response = await axios.get(`api/project/getall/`);
        this.projects = response.data;
      } catch (error) {
        console.error('Error fetching task:', error);
      }
    },
    async fetchTaskPriority(){
      try {
        const response = await axios.get(`api/task/taskPriority/getall`);
        this.priorities = response.data;
      } catch (error) {
        console.error('Error fetching task:', error);
      }
    },
    async fetchTaskStatus(){
      try {
        const response = await axios.get(`api/task/taskStatus/getall`);
        this.statuses = response.data;
      } catch (error) {
        console.error('Error fetching task:', error);
      }
    },
    async fetchTask(id) {
      try {
        const response = await axios.get(`api/task/getone/${id}`);
        this.task = response.data;
      } catch (error) {
        console.error('Error fetching task:', error);
      }
    },
    async handleSubmit() {
      try {
        if (this.isEdit) {
          // Update project
          await axios.post(`http://localhost:8000/api/task/update`, this.task);
          alert('Task updated successfully');
        } else {
          // Add new project
          await axios.post('http://localhost:8000/api/task/save', this.task);
          alert('Project added successfully');
        }
        this.$router.push({ name: 'task' });
      } catch (error) {
        console.error('Error saving task:', error);
      }
    },
  },
};
</script>

<style scoped>

</style>