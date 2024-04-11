<template>
  <div class="container">
    <!-- Renderiza el componente de butacas -->
    <Butacas :sessionId="sessionId" @selectedSeatsUpdated="handleSelectedSeatsUpdated" />

    <!-- Renderiza el menú de butacas seleccionadas -->
    
    <button @click="efectuarCompra">Comprar</button>
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
      console.log("Butacas seleccionadas:", this.selectedSeats)
      const data = {
        seats: this.selectedSeats.map((seat) => ({
          row: seat.row, // Agregar la fila de la butaca seleccionada
          column: seat.column, // Agregar la columna de la butaca seleccionada
          price: seat.precio,
          status: seat.status
        })),
        sessionId: sessioId,
      };
      console.log("Datos de la compra:", data);
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
      console.log("Datos actualizados de las butacas seleccionadas:", data);
      // Actualizar las butacas seleccionadas en el componente Compra
      this.selectedSeats = data.seats;
    }
  },
};
</script>
<style scoped>
.container {
  width: 80%;
  margin: 0 auto;
  font-family: Arial, sans-serif;
}

.session-button {
  background-color: #4caf50;
  /* Green */
  border: none;
  color: white;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
}

.selected-seats {
  background-color: #f8f8f8;
  padding: 20px;
  margin-top: 20px;
  border-radius: 5px;
}

.selected-seats ul {
  list-style-type: none;
  padding: 0;
}

.selected-seats ul li {
  padding: 10px 0;
  border-bottom: 1px solid #ddd;
}

.selected-seats ul li:last-child {
  border-bottom: none;
}
</style>
