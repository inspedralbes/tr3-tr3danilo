<template>
  <div>
    <h2>Selecciona tus butacas</h2>
    <div class="screen"></div> <!-- Línea simulando la pantalla -->
    <div class="cinema-seats">
      <img 
        v-for="seat in availableSeats" 
        :key="seat.id" 
        :src="getSeatImage(seat.status)"
        @click="toggleSeatStatus(seat)"
        :id="'seat_' + seat.id"
      >
    </div>
    <h1 class="screen-title">PANTALLA</h1>
    <div class="screen"></div> <!-- Línea simulando la pantalla -->
  </div>
</template>

<script>
export default {
  props: {
    sessionId: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      availableSeats: Array.from({ length: 80 }, (_, index) => ({
        id: index + 1,
        status: 'available',
        precio: 6.50
      }))
    };
  },
  methods: {
    toggleSeatStatus(seat) {
      if (seat.status === 'available') {
        seat.status = 'selected';
        this.$emit('seatSelected', seat);
      } else if (seat.status === 'selected') {
        seat.status = 'available';
        this.$emit('seatDeselected', seat); // Emitir evento cuando la butaca se deselecciona
      }
    },
    getSeatImage(status) {
      return status === 'selected' ? '/butacaVerde.jpg' : '/butacaAzul.png';
    }
  },
  created() {
    // Verifica si sessionId está definido antes de usarlo
    if (typeof this.sessionId !== 'undefined') {
      // Aquí puedes realizar alguna lógica utilizando sessionId
    }
  }
};
</script>

<style scoped>
/* Estilos CSS específicos del componente */
.cinema-seats {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  margin: 0 auto; /* Centrar el contenedor */
  max-width: 900px; /* Ancho máximo para dejar espacio en los lados */
}

.cinema-seats img {
  margin: 5px;
  width: 50px; /* Ajusta el tamaño de la imagen según sea necesario */
}

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
