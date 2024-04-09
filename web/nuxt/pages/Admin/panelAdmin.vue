<template>
    <div>
        <h1>Panel del Administrador</h1>
        <button @click="agregarSesion"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">
            Afegir Sessio
        </button>
        <button @click="esborrarSessio"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">
            Esborrar Sessio
        </button>
        <canvas id="myChart"></canvas>
    </div>


</template>

<script>
import Chart from 'chart.js/auto';
export default {
    data() {
        return {
            sessionsData: [],
            chartData: {}
        };
    },
    mounted() {
        this.fetchSessionsData();
    },
    methods: {
        agregarSesion() {
            this.$router.push('/Admin/afegirSessio');
        },
        esborrarSessio() {
            this.$router.push('/Admin/esborrarSessio');
        },
        async fetchSessionsData() {
            try {
                // Llamar al método para obtener los datos de butacas ocupadas por sesión
                const response = await fetch("http://localhost:8000/api/butacasPorSesion");
                const data = await response.json();

                // Procesar los datos para el gráfico
                this.processChartData(data);
            } catch (error) {
                console.error("Error al obtener datos de la API:", error);
            }
        },
        processChartData(data) {
            // Convertir los datos en un array de objetos
            const chartData = Object.entries(data).map(([sessionId, numButacas]) => {
                return {
                    sessionId: parseInt(sessionId),
                    numButacas: numButacas
                };
            });

            // Ordenar los datos por ID de sesión
            chartData.sort((a, b) => a.sessionId - b.sessionId);

            // Procesar los datos para el gráfico
            const labels = chartData.map(item => item.sessionId);
            const dataValues = chartData.map(item => item.numButacas);

            this.chartData = {
                labels: labels,
                datasets: [{
                    label: 'Butacas Ocupadas',
                    data: dataValues,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            };

            // Renderizar el gráfico
            this.renderChart();
        },
        renderChart() {
            const ctx = document.getElementById('myChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: this.chartData,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    }
};
</script>

<style lang="scss" scoped></style>