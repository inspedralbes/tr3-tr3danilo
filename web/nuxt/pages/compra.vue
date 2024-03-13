<template>
  <div>
    <h1>Compra</h1>
    <p v-if="sessioPinia">Detalles de la sesión: {{ sessioPinia.id }}</p>

    <button @click="selectSession">Seleccionar sesión</button>

    <!-- Renderiza el componente de butacas -->
    <Butacas :sessionId="sessioPinia && sessioPinia.id" @seatSelected="handleSeatSelected" />
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
      sessionId: null
    };
  },
  created() {
    
    this.sessioPinia = compraStore.sessio;
    this.sessionId = this.sessioPinia ? this.sessioPinia.id : null;
  },
  methods: {
    handleSeatSelected(seat) {
      let storeSesion = compraStore();   
      console.log('Butaca seleccionada:', seat);
      console.log(seat);
      storeSesion.setButacaSeleccionada(seat);
},
    selectSession() {
      // Aquí puedes agregar lógica para seleccionar una sesión
    }
  }
};
</script>