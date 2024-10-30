<template>
  <div class="admin-sales-chart">
    <h2>Sales Chart</h2>
    <Bar :data="chartData" :options="chartOptions" />
  </div>
</template>

<script setup>
import { ref } from "vue";
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

// Dummy sales data for each month
const salesData = [120, 150, 180, 130, 200, 220, 170, 250, 300, 280, 320, 350];

// Create chart data
const chartData = ref({
  labels: [
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December",
  ],
  datasets: [
    {
      label: "Sales ($)",
      backgroundColor: "rgba(250, 249, 246, 1)", // Set bars to white
      borderColor: "rgba(250, 249, 246, 1)", // Set border color to white
      data: salesData,
    },
  ],
});

// Chart options
const chartOptions = ref({
  responsive: true,
  plugins: {
    legend: {
      position: "top",
      labels: {
        color: "#faf9f6", // Set legend text color to white
      },
    },
    title: {
      display: true,
      text: "Monthly Sales Data",
      color: "#faf9f6", // Set title text color to white
    },
    tooltip: {
      callbacks: {
        label: (tooltipItem) => {
          return `Sales: $${tooltipItem.raw}`;
        },
      },
    },
  },
  scales: {
    x: {
      ticks: {
        color: "#faf9f6", // Set X-axis tick color to white
      },
    },
    y: {
      ticks: {
        color: "#faf9f6", // Set Y-axis tick color to white
      },
    },
  },
});
</script>
