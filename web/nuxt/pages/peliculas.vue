<!-- 
Ruta de desplegament: https://tr3cine.a17danvicfer.daw.inspedralbes.cat/laravel/public
Ruta Local: http://localhost:8000
-->
<template>
  <div class="bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-semibold text-center mb-8">CARTELLERA</h1>
    <div class="flex flex-wrap justify-center gap-8">
      <div v-for="movie in movies" :key="movie.id" class="bg-white shadow-md hover:shadow-lg transition duration-300 ease-in-out rounded-lg overflow-hidden" style="max-width: 300px;">
        <div class="overflow-hidden h-99">
          <img :src="movie.enllaç_imatge" :alt="movie.títol" class="w-full h-full object-cover object-center">
        </div>
        <div class="p-4">
          <h2 class="text-xl font-semibold mb-2">{{ movie.títol }}</h2>
          <p class="text-gray-700 mb-2">{{ movie.descripció }}</p>
          <p class="text-gray-700 mb-2">Director: {{ movie.director }}</p>
          <p class="text-gray-700">Any d'estrena: {{ movie.any_estrena }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      ruta: 'http://tr3cine.a17danvicfer.daw.inspedralbes.cat/laravel/public',
      movies: []
    };
  },
  mounted() {
    fetch(`${this.ruta}/api/pelicules`)
      .then(response => {
        if (!response.ok) {
          throw new Error('Error al obtener los datos de la API');
        }
        return response.json();
      })
      .then(data => {
        // Verificar si la respuesta tiene la estructura esperada
        if (Array.isArray(data)) {
          this.movies = data;
        } else {
          throw new Error('La respuesta de la API no tiene el formato esperado');
        }
      })
      .catch(error => {
        console.error('Error al obtener datos de la API:', error);
      });
  }
};
</script>
