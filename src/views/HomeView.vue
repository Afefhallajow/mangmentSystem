<template>
  <div class="container-fluid">
    <div class="row d-flex justify-content-between m-1 p-2">
      <!-- Iterate over all categories and render a chart for each -->
      <div class="card chartCard" v-for="(category, index) in chartOptions" :key="index">
        <div class="card-header">
          <h3>{{ category.title }}</h3>
        </div>
        <div class="card-body">
          <ChartsApp :options="category.options" />
        </div>
      </div>
    </div>
    <div class="row p-2 justify-content-between shadow m-2">
      <div class="col-12 mb-2 text-start p-3">
        <h3>Projects</h3>
      </div>
      <div class="ProjectCard card text-bg-secondary">
        <div class="card-header">Active Projects</div>
        <div class="card-body text-white">
          <p class="card-text">{{ projects.active_projects}}</p>
        </div>
        <span class="bi bi-people cardSpan" ></span>
      </div>
      <div class="card text-bg-success ProjectCard " >
        <div class="card-header">Freezing Projects</div>
        <div class="card-body">
          <p class="card-text">{{ projects.freezing_projects}}</p>
        </div>
        <span class="bi bi-people cardSpan" ></span>
      </div>
      <div class="card text-bg-danger  ProjectCard" >
        <div class="card-header">Pending Projects</div>
        <div class="card-body">
          <p class="card-text">{{ projects.pending_projects}}</p>
        </div>
        <span class="bi bi-people cardSpan" ></span>
      </div>
      <div class="card text-bg-warning shadow text-white ProjectCard">
        <div class="card-header">Done Projects</div>
        <div class="card-body">
          <p class="card-text">{{ projects.done_projects}}</p>
        </div>
        <span class="bi bi-diagram-2 cardSpan" style="color: rgb(236 234 234 / 41%) !important;" ></span>
      </div>
    </div>
    <div class="row p-2 justify-content-between shadow m-2 mt-4">
      <div class="col-12 mb-2 text-start p-3">
        <h3>Users</h3>
      </div>
      <div class="userCard card text-bg-secondary">
        <div class="card-header">Users</div>
        <div class="card-body text-white">
          <p class="card-text">{{ users.UserCount}}</p>
        </div>
        <span class="bi bi-people cardSpan" ></span>
      </div>
      <div class="card text-bg-success userCard " >
        <div class="card-header">Employess</div>
        <div class="card-body">
          <p class="card-text">{{ users.EmployeeCount}}</p>
        </div>
        <span class="bi bi-people cardSpan" ></span>
      </div>
      <div class="card text-bg-danger  userCard" >
        <div class="card-header">Clients</div>
        <div class="card-body">
          <p class="card-text">{{ users.ClientCount}}</p>
        </div>
        <span class="bi bi-people cardSpan" ></span>
      </div>
    </div>


  </div>
</template>

<script>
import { ref, onMounted } from "vue";
import axios from "axios"; // For making API requests
import ChartsApp from "@/components/Global/AgChart.vue"; // Your chart component

export default {
  name: "DynamicCharts",
  components: {
    ChartsApp,
  },
  data(){
    return{
      projects:[],
      users:[]
    }
  },
  methods: {
    async getProjects() {
      axios.post("/project/report").then((response) => {
        this.projects = response.data;
      }).catch((error) => {
        console.error("Error fetching report data:", error);
      });
    }
    ,
    async getUsers() {
      axios.get("/crm/report").then((response) => {
        this.users = response.data.data;
      }).catch((error) => {
        console.log("error ", error);
      })
    },
  },
  setup() {
    const chartOptions = ref([]); // To hold chart configurations for each category
    const fetchChartData = async () => {
      try {
        const response = await axios.get("/home"); // Replace with your API endpoint
        const data = response.data.data;

        // Dynamically generate chart options for each category (purchase, sells, etc.)
        chartOptions.value = Object.keys(data).map((category) => {
          return {
            title: category.charAt(0).toUpperCase() + category.slice(1), // Capitalize category name
            options: {
              data: data[category].map(item => ({
                ...item,
                total_quantity: parseFloat(item.total_quantity), // Ensure total_quantity is a float
                total_price: parseFloat(item.total_price), // Ensure total_price is a float
              })),
              series: [
                {
                  type: "bar",
                  xKey: "name", // The name of the item (e.g., "cars", "apple")
                  yKey: "total_quantity", // The value to plot (e.g., total quantity)
                  yName: "Total Quantity", // Label for the Y axis
                },
                {
                  type: "bar",
                  xKey: "name", // The name of the item
                  yKey: "total_price", // The value to plot (e.g., total price)
                  yName: "Total Price", // Label for the Y axis
                },
              ],
            },
          };
        });
      } catch (error) {
        console.error("Error fetching chart data:", error);
      }
    };

    // Fetch data when the component is mounted
    onMounted(() => {
      fetchChartData();
    });

    return {
      chartOptions,
    };
  },
  mounted() {
    // Call getProjects on component mount
    this.getProjects();
    this.getUsers();
  },
};
</script>
<style scoped>
.chartCard{
  width: 49% !important;
}
.ProjectCard{
  width:23% !important ;
  justify-items: center;
  align-items: end;
  display: flex;
  overflow: hidden;
}
.userCard{
  width:31% !important ;
  justify-items: center;
  align-items: end;
  display: flex;
  overflow: hidden;
}

.cardSpan{
  position: absolute;
  left: -14px;
  font-size: 119px;
  color: #cfd9c938;
  bottom: -53px;
}
.card-header{
  font-size:large ;
}

</style>