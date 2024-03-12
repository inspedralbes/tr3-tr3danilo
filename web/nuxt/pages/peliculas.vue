<template>
    <div class="movie-list">
      <h1 class="page-title">Benvingut a CineCar</h1>
      <div class="movies-container">
        <div v-for="movie in movies" :key="movie.id" class="movie">
          <div class="movie-card">
            <div class="movie-poster">
              <img :src="movie.enllaç_imatge" :alt="movie.títol">
            </div>
            <div class="movie-details">
              <h2>{{ movie.títol }}</h2>
              <p>{{ movie.descripció }}</p>
              <p>Director: {{ movie.director }}</p>
              <p>Any d'estrena: {{ movie.any_estrena }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
export default {
  data() {
    return {
      movies: []
    };
  },
  mounted() {
    fetch('http://localhost:8000/api/pelicules')
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
  
  <style>
  .movie-list {
    text-align: center;
  }
  
  .page-title {
    margin-bottom: 20px;
  }
  
  .movies-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    border: 2px solid #ddd;
    border-radius: 10px;
    padding: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
  }
  
  .movie {
    margin: 20px;
  }
  
  .movie-card {
    width: 300px;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease; /* Añadir transición */
  }
  
  .movie-card:hover {
    transform: translateY(-5px); /* Efecto de levantar al pasar el ratón */
  }
  
  .movie-poster img {
    width: 100%;
    height: auto;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
  }
  
  .movie-details {
    background-color: #f9f9f9;
    padding: 20px;
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
  }
  </style>
  