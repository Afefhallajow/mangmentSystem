<template>
  <div>
    <h2>Sales Chart</h2>
    <BarChart :chart-data="chartData" :options="chartOptions" />
  </div>
</template>

<script>
import { Bar } from 'vue-chartjs';
import {onMounted, ref} from "vue";

export default {
  name: "BarChart",
  components: {
    BarChart: Bar,
  },
  setup() {
    const chartData = ref(null);
    const chartOptions = ref({
      responsive: true,
      maintainAspectRatio: false,
    });

    onMounted(async () => {
      try {
        const response = await fetch('/home'); // Adjust base URL if needed
        const data = await response.json();
        chartData.value = data;
      } catch (error) {
        console.error('Error fetching chart data:', error);
      }
    });

    return {
      chartData,
      chartOptions,
    };
  },
};
</script>

<style scoped>
</style>