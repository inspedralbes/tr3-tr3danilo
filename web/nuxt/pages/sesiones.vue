<!-- 
Ruta de desplegament: https://tr3cine.a17danvicfer.daw.inspedralbes.cat/laravel/public
Ruta Local: http://localhost:8000
-->
<template>
  <div class="sesiones-list">
    <h1 class="text-2xl font-bold mb-8">SESSIONS DEL DIA</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div
        v-for="session in sessions"
        :key="session.sesion.id"
        class="rounded-lg overflow-hidden shadow-md cursor-pointer hover:shadow-lg"
        @click="goToSession(session.sesion)"
      >
        <div class="relative">
          <img
            :src="session.pelicula.imagen"
            :alt="session.pelicula.titulo"
            class="w-full h-78 object-cover"
          />
          <div class="absolute inset-0 bg-black opacity-40"></div>
          <div class="absolute inset-0 flex items-center justify-center">
            <h2 class="text-white text-2xl font-bold">{{ session.pelicula.titulo }}</h2>
          </div>
        </div>
        <div class="p-4">
          <p class="text-gray-700">{{ session.pelicula.descripcion }}</p>
          <p class="text-gray-700 mt-2">
            {{ session.sesion.fecha }} - {{ session.sesion.hora }}
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { compraStore } from "../stores/compra.js";

export default {
  data() {
    return {
      ruta: "http://tr3cine.a17danvicfer.daw.inspedralbes.cat/laravel/public",
      pelicula: null,
      sessions: [],
    };
  },
  mounted() {
    //Comporbar si el usuario es admin
    let storeSesion = compraStore();
    // Comprobar si el usuario está autenticado
    if (storeSesion.isAuthenticated) {
      fetch(`${this.ruta}/api/sessions`)
        .then((response) => {
          if (!response.ok) {
            throw new Error("Error al obtener los datos de la API");
          }
          return response.json();
        })
        .then((data) => {
          this.sessions = data.sessions;
        })
        .catch((error) => {
          console.error("Error al obtener datos de la API:", error);
        });
    } else {
      alert("No estás autenticado, por favor inicia sesión");
      this.$router.push(`/login`);
    }
  },
  methods: {
    goToSession(session) {
      let storeSesion = compraStore();
      storeSesion.sessio = session;
      //console.log("Sesión seleccionada Pinia:", storeSesion.sessio.id);

      this.$router.push(`/compra`);
    },
  },
};
</script>

<style scoped>
.sesiones-list {
  max-width: 800px;
  margin: auto;
}
</style>
