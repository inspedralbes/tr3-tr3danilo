
<!-- 
Ruta de desplegament: http://tr3cine.a17danvicfer.daw.inspedralbes.cat/laravel/public
Ruta Local: http://localhost:8000
-->
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
      ruta: 'http://tr3cine.a17danvicfer.daw.inspedralbes.cat/laravel/public',
      sessioPinia: null,
      availableSeats: Array.from({ length: 80 }, (_, index) => ({
        id: index + 1,
        status: 'available',
        precio: 6.50
      })),
      occupiedSeats: []

    };
  },
  methods: {
    toggleSeatStatus(seat) {
  if (seat.status === 'available') {
    // Comprueba si la butaca está ocupada
    const isOccupied = this.isSeatOccupied(seat.id);
    if (isOccupied) {
      seat.status = 'ocupado'; // Cambiar a 'ocupado'
    } else {
      seat.status = 'selected';
      this.$emit('seatSelected', seat);
    }
  } else if (seat.status === 'selected') {
    seat.status = 'available';
    this.$emit('seatDeselected', seat);
  }
},

    getSeatImage(status) {
      // Ajusta el retorno de la imagen dependiendo del estado de la butaca
      switch (status) {
        case 'selected':
          return '/butacaVerde.jpg';
        case 'ocupado':
          return '/butacaOcupada.jpg';
        default:
          return '/butacaAzul.png';
      }
    },
    isSeatOccupied(seatId) {
      // Busca la butaca por su ID en el array de butacas ocupadas
      return this.occupiedSeats.some(seat => seat.id === seatId);
    },
  obtenerButacasOcupadas() {
    console.log('SessioId: Butacas:', this.sessioPinia);
      fetch(`${this.ruta}/${this.sessioPinia}/ocupadas`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(this.sessioPinia)
      })
      .then(response => response.json())
      .then(result => {
        // Actualizar el estado de las butacas ocupadas
        result.forEach(occupiedSeat => {
          const seat = this.availableSeats.find(seat => seat.id === occupiedSeat.id);
          if (seat) {
            seat.status = 'ocupado';
          }
        });
        console.log('Butacas ocupadas:', result);
      })
      .catch(error => {
        console.error(error);
      });
    }
  },
  created() {
    let storeSesion= compraStore();   
    this.sessioPinia = storeSesion.sessio.id;
    console.log('ID de la sesión butaca Created:', this.sessioPinia);
    if (typeof this.sessioPinia !== 'undefined') {
      this.obtenerButacasOcupadas();
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
