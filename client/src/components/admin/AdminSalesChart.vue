<template>
  <div class="admin-sales-chart">
    <h2>Sales Chart</h2>
    <div style="width: 100%; height: 400px">
      <Bar
        :data="chartData"
        :options="chartOptions"
        :width="400"
        :height="200"
      />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import { Bar } from "vue-chartjs";
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale,
} from "chart.js";

// Register Chart.js components
ChartJS.register(
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale
);

// State for chart data
const chartData = ref({
  labels: [],
  datasets: [
    {
      label: "Sales ($)",
      backgroundColor: "rgba(15, 15, 15, 1)",
      borderColor: "rgba(15, 15, 15, 1)",
      data: [],
    },
  ],
});

// Chart options
const chartOptions = ref({
  responsive: true,
  maintainAspectRatio: false, // Add this to ensure chart fills container
  plugins: {
    legend: {
      position: "top",
      labels: {
        color: "#0f0f0f",
      },
    },
    title: {
      display: true,
      text: "Monthly Sales Data",
      color: "#0f0f0f",
    },
    tooltip: {
      callbacks: {
        label: (tooltipItem) => {
          return `Sales: $${tooltipItem.raw.toFixed(2)}`;
        },
      },
    },
  },
  scales: {
    x: {
      ticks: {
        color: "#0f0f0f",
      },
    },
    y: {
      ticks: {
        color: "#0f0f0f",
        callback: (value) => `$${value.toFixed(2)}`,
      },
      beginAtZero: true,
    },
  },
});

// Watch for changes in chart data
watch(
  () => chartData.value,
  (newValue) => {
    console.log("chartData changed:", newValue);
  },
  { deep: true }
);
// Function to fetch sales data from the backend
const fetchSalesData = async () => {
  try {
    const response = await fetch(
      `${import.meta.env.VITE_API_URL}/payments/monthly-sales`,
      {
        method: "GET",
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      }
    );

    if (!response.ok) {
      throw new Error("Failed to fetch sales data");
    }

    const data = await response.json();
    console.log("Fetched sales data:", data);

    // Create a new chart data object
    chartData.value = {
      labels: data.labels,
      datasets: [
        {
          label: "Sales ($)",
          backgroundColor: "rgba(15, 15, 15, 1)",
          borderColor: "rgba(15, 15, 15, 1)",
          data: data.data.map((value) => parseFloat(value)),
        },
      ],
    };

    console.log("New chart data:", chartData.value);
  } catch (error) {
    console.error("Error fetching sales data:", error);
  }
};

// Fetch data when the component is mounted
onMounted(fetchSalesData);
</script>

<style scoped>
.admin-sales-chart {
  width: 100%;
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

h2 {
  text-align: center;
  margin-bottom: 20px;
}
</style>
