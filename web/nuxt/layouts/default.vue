<template>
    <div>
      <header class="cine-header">
        <!-- Menú de navegación -->
        <nav class="cine-menu">
          <ul>
            <li><nuxt-link to="/">Inicio</nuxt-link></li>
            <li><nuxt-link to="/peliculas">Cartelera</nuxt-link></li>
            <li><nuxt-link to="/sesiones">Sesió del Dia</nuxt-link></li>
          </ul>
        </nav>
      </header>
      <slot/>
    </div>
  </template>
  
  <script>
  export default {

  };
  </script>
  
  <style scoped>
  .cine-header {
    background-color: #333;
    color: #fff;
    padding: 20px;
    text-align: center;
  }
  
  .cine-menu ul {
    list-style-type: none;
    padding: 0;
  }
  
  .cine-menu li {
    display: inline;
    margin: 0 10px;
  }
  
  .cine-menu a {
    color: #fff;
    text-decoration: none;
    font-weight: bold;
  }
  
  .cine-menu a:hover {
    color: #ffd700; /* Cambia el color al pasar el ratón */
  }
  </style>
  