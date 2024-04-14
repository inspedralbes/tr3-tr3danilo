
<!-- 
Ruta de desplegament: https://tr3cine.a17danvicfer.daw.inspedralbes.cat/laravel/public
Ruta Local: http://localhost:8000
-->
<template>
  <div class="container">
    <!-- Renderiza el componente de butacas -->
    <Butacas :sessionId="sessionId" @selectedSeatsUpdated="handleSelectedSeatsUpdated" />
    <!-- Botón Comprar -->
    <div class="flex justify-center mt-4">
      <!-- Espacio en blanco a la izquierda para centrar el botón -->
      <div></div>
      <!-- Botón Comprar -->
      <button
        @click="efectuarCompra"
        :disabled="!hasSelectedSeats"
        class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded"
      >
        Comprar
      </button>
    </div>
    <br /><br />
  </div>
</template>

<script>
import { compraStore } from "../stores/compra.js";
import Butacas from "../components/butacas.vue";

export default {
  components: {
    Butacas,
  },
  data() {
    return {
      sessioPinia: null,
      sessionId: null,
      selectedSeats: [],
    };
  },
  computed: {
    totalSeats() {
      return this.selectedSeats.length; // Retorna el número total de butacas seleccionadas
    },
    totalPrice() {
      return this.selectedSeats.reduce((total, seat) => total + seat.precio, 0); // Suma el precio de todas las butacas seleccionadas
    },
    hasSelectedSeats() {
      return this.selectedSeats.length > 0; // Retorna true si hay butacas seleccionadas
    },
  },
  created() {
    let storeSesion = compraStore();
    this.sessioPinia = storeSesion.sessio;
    this.sessionId = this.sessioPinia ? this.sessioPinia.id : null;
  },
  methods: {
    efectuarCompra() {
      let storeSesion = compraStore();
      storeSesion.butacas = this.selectedSeats;
      let sessioId = storeSesion.sessio.id;
      //console.log("Butacas seleccionadas:", this.selectedSeats);
      const data = {
        seats: this.selectedSeats.map((seat) => ({
          row: seat.row, // Agregar la fila de la butaca seleccionada
          column: seat.column, // Agregar la columna de la butaca seleccionada
          price: seat.precio,
          status: seat.status,
        })),
        sessionId: sessioId,
      };
      //console.log("Datos de la compra:", data);
      this.$router.push({ path: "/ticket" });
    },
    handleSeatSelected(seat) {
      const index = this.selectedSeats.findIndex((s) => s.id === seat.id);
      if (index === -1) {
        // Si la butaca no está seleccionada, la agrega al array y actualiza el estado
        this.selectedSeats.push(seat);
      }
    },

    handleSeatDeselected(seat) {
      const index = this.selectedSeats.findIndex((s) => s.id === seat.id);
      if (index !== -1) {
        // Si la butaca está seleccionada, la elimina del array y actualiza el estado
        this.selectedSeats.splice(index, 1);
      }
    },
    handleSelectedSeatsUpdated(data) {
      // Manejar los datos recibidos del evento 'selectedSeatsUpdated'
      //console.log("Datos actualizados de las butacas seleccionadas:", data);
      // Actualizar las butacas seleccionadas en el componente Compra
      this.selectedSeats = data.seats;
    },
  },
};
</script>

<style scoped>
.container {
  width: 80%;
  margin: 0 auto;
  font-family: Arial, sans-serif;
}
</style>
