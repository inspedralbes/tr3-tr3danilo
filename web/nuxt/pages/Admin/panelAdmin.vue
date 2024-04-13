<!-- 
Ruta de desplegament: https://tr3cine.a17danvicfer.daw.inspedralbes.cat/laravel/public
Ruta Local: http://localhost:8000
-->
<template>
  <div class="p-6">
    <h1 class="text-3xl font-bold mb-4">Panell de l'Administrador</h1>
    <canvas id="myChart" class="w-full h-64"></canvas>
    <button
      @click="agregarSesion"
      class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4"
    >
      Afegir Sessio
    </button>
    <button
      @click="esborrarSessio"
      class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4 ml-4"
    >
      Esborrar Sessio
    </button>
  </div>
</template>

<script>
import { compraStore } from "../stores/compra.js";
import Chart from "chart.js/auto";
export default {
  data() {
    return {
      ruta: "http://tr3cine.a17danvicfer.daw.inspedralbes.cat/laravel/public",
      sessionsData: [],
      chartData: {},
    };
  },
  mounted() {
    const store = compraStore();
    if (store.isAuthenticated && store.isAdmin) {
      this.$router.push("/Admin/panelAdmin");
    } else {
      this.$router.push("/login");
    }
    this.fetchSessionsData();
  },
  methods: {
    agregarSesion() {
      this.$router.push("/Admin/afegirSessio");
    },
    esborrarSessio() {
      this.$router.push("/Admin/esborrarSessio");
    },
    async fetchSessionsData() {
      try {
        const response = await fetch(`${this.ruta}/api/butacasPorSesion`);
        const data = await response.json();
        this.processChartData(data);
      } catch (error) {
        console.error("Error al obtener datos de la API:", error);
      }
    },
    processChartData(data) {
      const chartData = Object.entries(data).map(([sessionId, numButacas]) => {
        return {
          sessionId: parseInt(sessionId),
          numButacas: numButacas,
        };
      });
      chartData.sort((a, b) => a.sessionId - b.sessionId);
      const labels = chartData.map((item) => item.sessionId);
      const dataValues = chartData.map((item) => item.numButacas);
      this.chartData = {
        labels: labels,
        datasets: [
          {
            label: "Butaques Ocupades",
            data: dataValues,
            backgroundColor: "rgba(255, 99, 132, 0.2)",
            borderColor: "rgba(255, 99, 132, 1)",
            borderWidth: 1,
          },
        ],
      };
      this.renderChart();
    },
    renderChart() {
      const ctx = document.getElementById("myChart").getContext("2d");
      new Chart(ctx, {
        type: "bar",
        data: this.chartData,
        options: {
          scales: {
            y: {
              beginAtZero: true,
            },
          },
        },
      });
    },
  },
};
</script>

<style lang="scss" scoped></style>
