<template>
  <div class="sesiones-list">
    <h1 class="page-title">Benvingut a CineCar</h1>
    <div class="sesiones-container movies-container">
      <div
        v-for="session in sessions"
        :key="session.id"
        class="sesion-link movie"
        @click="goToSession(session)"
      >
        <div class="sesion-item movie-card">
          <div class="sesion-content movie-details">
            <div class="pelicula-info">
              <h2>{{ pelicula.titulo }}</h2>
              <img :src="pelicula.imagen" :alt="pelicula.titulo" class="pelicula-imagen">
              <p>{{ pelicula.descripcion }}</p>
            </div>
            <p class="sesion-hora">{{ session.fecha }} - {{ session.hora }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { compraStore } from '../stores/compra.js'; // Importa el store de Pinia
   
export default {
  data() {
    return {
      pelicula: null,
      sessions: []
    };
  },
  mounted() {
    this.fetchData();
    
  },
  methods: {
    fetchData() {
      fetch('http://localhost:8000/api/sessions') // Cambiar la ruta si es necesario
        .then(response => {
          if (!response.ok) {
            throw new Error('Error al obtener los datos de la API');
          }
          return response.json();
        })
        .then(data => {
          this.pelicula = data.pelicula;
          this.sessions = data.sessions;
        })
        .catch(error => {
          console.error('Error al obtener datos de la API:', error);
        });
    },
    goToSession(session) {
      let storeSesion = compraStore();   
      console.log("estoy guardando la sesion")
      console.log(session);
      storeSesion.setSessio(session); // Guarda la sesión en el store de Pinia
      this.$router.push(`/compra`);
    }
  }
};
</script>

<style scoped>
.sesiones-list {
  max-width: 800px;
  margin: auto;
  text-align: center;
}

.page-title {
  margin-bottom: 20px;
}

.sesiones-container {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  border: 2px solid #ddd;
  border-radius: 10px;
  padding: 10px;
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
}

.sesion-item {
  margin: 20px;
  width: 300px;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease; /* Añadir transición */
}

.sesion-item:hover {
  transform: translateY(-5px); /* Efecto de levantar al pasar el ratón */
}

.pelicula-imagen {
  width: 100%;
  height: auto;
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
}

.sesion-hora {
  margin-top: 10px;
}

.movie-details {
  background-color: #f9f9f9;
  padding: 20px;
  border-bottom-left-radius: 10px;
  border-bottom-right-radius: 10px;
}
</style>
