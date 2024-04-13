<template>
  <div class="flex flex-col items-center h-screen">
    <h1 class="text-3xl font-bold mb-8">Benvingut a CinesCar</h1>
    <img src="/cineCar.jpg" alt="CinesCar" class="w-70 mb-8 rounded-lg shadow-lg" />

    <div class="flex justify-center">
      <router-link
        to="/peliculas"
        class="mx-4 bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded"
      >
        CARTELLERA
      </router-link>
      <router-link
        to="/sesiones"
        class="mx-4 bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded"
      >
        SESSIONS
      </router-link>
      <router-link
        to="/login"
        class="mx-4 bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded"
      >
        LOGIN
      </router-link>
      <router-link
        v-if="isAdmin"
        to="/Admin/panelAdmin"
        class="mx-4 bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded"
      >
        AdminPanel
      </router-link>
    </div>
  </div>
</template>

<script>
import { compraStore } from "../stores/compra.js";
export default {
  data() {
    return {
      isAdmin: false,
    };
  },
  mounted() {
    const store = compraStore();
    this.isAdmin = store.isAdmin;
    //console.log("Index el user es:", this.isAdmin);
  },
};
</script>

<style scoped>
/* Estilos específicos del componente */
</style>
