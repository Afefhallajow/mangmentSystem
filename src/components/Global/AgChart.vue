<template><!-- The AG Charts component with chartsOptions as an attribute -->
  <ag-charts :options="options"> </ag-charts>
</template>

<script>
// Vue Chart Component
import { AgCharts } from 'ag-charts-vue3';

export default {
  name: 'chartsApp',
  components: {
    'ag-charts': AgCharts,
  },
  setup() {},
}
</script>