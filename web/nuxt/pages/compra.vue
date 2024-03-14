<template>
  <div class="container">
    <h1>Compra</h1>
    <p v-if="sessioPinia">Detalles de la sesión: {{ sessioPinia.id }}</p>

    <button class="session-button" @click="selectSession">Seleccionar sesión</button>

    <!-- Renderiza el componente de butacas -->
    <Butacas :sessionId="sessioPinia && sessioPinia.id" @seatSelected="handleSeatSelected"
      @seatDeselected="handleSeatDeselected" />

    <!-- Renderiza el menú de butacas seleccionadas -->
    <div v-if="selectedSeats.length" class="selected-seats">
      <h2>Butacas seleccionadas:</h2>
      <ul>
        <li v-for="(seat, index) in selectedSeats" :key="index">
          Butaca: {{ seat.id }} - Precio: {{ seat.precio }}€
        </li>
      </ul>
      <p>Total de butacas seleccionadas: {{ totalSeats }}</p>
      <p>Total a pagar: {{ totalPrice }}€</p>
    </div>
    <button @click="efectuarCompra">Comprar</button>
  </div>
</template>

<script>

import { compraStore } from '../stores/compra.js'
import Butacas from '../components/butacas.vue'

export default {
  components: {
    Butacas
  },
  data() {
    return {
      sessioPinia: null,
      sessionId: null,
      selectedSeats: []  // Nueva propiedad para almacenar las butacas seleccionadas
    };
  },
  computed: {
    totalSeats() {
      return this.selectedSeats.length;  // Retorna el número total de butacas seleccionadas
    },
    totalPrice() {
      return this.selectedSeats.reduce((total, seat) => total + seat.precio, 0);  // Suma el precio de todas las butacas seleccionadas
    }
  },
  created() {

    this.sessioPinia = compraStore.sessio;
    this.sessionId = this.sessioPinia ? this.sessioPinia.id : null;
  },
  methods: {
    efectuarCompra() {
      let storeSesion = compraStore();   
      storeSesion.setButacaSeleccionada(this.selectedSeats);
      let sessioId = storeSesion.getSessio().id;
      const data = {
        seats: this.selectedSeats.map(seat => ({ id: seat.id, price: seat.precio, status: seat.status })),
        sessionId: sessioId
      };
      console.log('Datos de la compra:', data);
      fetch('http://localhost:8000/api/efectuarCompra', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
      })
        .then(response => response.json())
        .then(result => {
          // Handle the response from the API
          console.log(result);
        })
        .catch(error => {
          // Handle any errors that occurred during the fetch request
          console.error(error);
        });
    },
    handleSeatSelected(seat) {
      //let storeSesion = compraStore();   
      console.log('Butaca seleccionada:', seat);
      //storeSesion.setButacaSeleccionada(seat);
      const index = this.selectedSeats.findIndex(s => s.id === seat.id);
      if (index !== -1) {
        // Si la butaca ya está seleccionada, la elimina del array
        this.selectedSeats.splice(index, 1);
      } else {
        // Si la butaca no está seleccionada, la agrega al array
        this.selectedSeats.push(seat);
      }
    },
    handleSeatDeselected(seat) {
      const index = this.selectedSeats.findIndex(s => s.id === seat.id);
      if (index !== -1) {
        this.selectedSeats.splice(index, 1);
      }
    },
    selectSession() {
      // Aquí puedes agregar lógica para seleccionar una sesión
    }
  }
};
</script>
<style scoped>
.container {
  width: 80%;
  margin: 0 auto;
  font-family: Arial, sans-serif;
}

.session-button {
  background-color: #4CAF50;
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