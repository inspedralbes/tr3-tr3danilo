<template>
  <div>
    <h2 class="text-lg font-semibold mb-4">Selecciona tus butacas</h2>
    <div class="screen"></div> <!-- Línea simulando la pantalla -->
    <div class="grid grid-cols-10 gap-1 justify-center max-w-4xl mx-auto">
      <img 
        v-for="seat in availableSeats" 
        :key="seat.id" 
        :src="getSeatImage(seat)"
        @click="toggleSeatStatus(seat)"
        :id="'seat_' + seat.id"
        class="max-w-xxs"
      >
    </div>
    <h1 class="screen-title">PANTALLA</h1>
    <div class="screen"></div> <!-- Línea simulando la pantalla -->
    <p>Total de butacas seleccionadas: {{ totalSelectedSeats }}</p>
    <p>Total a pagar: {{ totalPrice }}€</p>
    <div v-if="selectedSeats.length">
      <p>Butacas seleccionadas:</p>
      <ul>
        <li v-for="(seat, index) in selectedSeats" :key="index">
          Fila {{ seat.row }} Columna {{ seat.column }}
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import { compraStore } from '../stores/compra.js'

export default {
  props: {
    sessionId: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      sessioPinia: null,
      availableSeats: Array.from({ length: 120 }, (_, index) => ({
        id: index + 1,
        row: Math.floor(index / 10) + 1,
        column: index % 10 + 1,
        status: 'available',
        precio: 6.50
      })),
      selectedSeats: [],
      vipRow: 6,
      vipPrice: 8,
      normalPrice: 6,
    };
  },
  computed: {
    totalSelectedSeats() {
      return this.selectedSeats.length;
    },
    totalPrice() {
      let total = 0;
      this.selectedSeats.forEach(seat => {
        total += seat.row === this.vipRow ? this.vipPrice : this.normalPrice;
      });
      return total;
    }
  },
  methods: {
    toggleSeatStatus(seat) {
      if (seat.status === 'available') {
        seat.status = 'selected';
        this.selectedSeats.push(seat);
      } else if (seat.status === 'selected') {
        seat.status = 'available';
        const index = this.selectedSeats.findIndex(s => s.id === seat.id);
        if (index !== -1) {
          this.selectedSeats.splice(index, 1);
        }
      }
      this.emitSelectedSeats();
    },
    emitSelectedSeats() {
      const selectedSeatsData = this.selectedSeats.map(seat => ({
        row: seat.row,
        column: seat.column
      }));
      this.$emit('selectedSeatsUpdated', {
        sessionId: this.sessionId,
        seats: selectedSeatsData
      });
    },
    getSeatImage(seat) {
      switch (seat.status) {
        case 'selected':
          return '/butacaVerde.jpg';
        case 'ocupado':
          return '/butacaOcupada.jpg';
        default:
          return '/butacaAzul.png';
      }
    },
  },
  created() {
    let storeSesion = compraStore();   
    this.sessioPinia = storeSesion.sessio.id;
    if (typeof this.sessioPinia !== 'undefined') {
      //this.obtenerButacasOcupadas();
    }
  }
};
</script>

<style scoped>
/* Estilos CSS específicos del componente */
.screen {
  border-top: 2px solid black; /* Línea simulando la pantalla */
  width: 100%; /* Ancho total */
  margin-bottom: 10px; /* Espacio entre la pantalla y las butacas */
}

.screen-title {
  text-align: center;
  font-size: 24px;
  margin-bottom: 10px;
}
</style>
