<template>
  <div class="flex flex-col items-center h-screen">
    <h1 class="text-3xl font-bold mb-8">Bienvenido a CinesCar</h1>
    <img src="/cineCar.jpg" alt="CinesCar" class="w-1/2 mb-8 rounded-lg shadow-lg" />

    <div class="flex justify-center">
      <router-link
        to="/peliculas"
        class="mx-4 bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded"
      >
        Cartelera
      </router-link>
      <router-link
        to="/sesiones"
        class="mx-4 bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded"
      >
        Sessio del Dia
      </router-link>
      <router-link
        to="/login"
        class="mx-4 bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded"
      >
        Login
      </router-link>
    </div>
  </div>
</template>

<script>
export default {
  // Componente de bienvenida
};
</script>

<style scoped>
/* Estilos específicos del componente */
</style>
