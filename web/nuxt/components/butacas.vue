<!-- components/Butacas.vue -->

<template>
    <div>
      <h2>Selecciona tus butacas</h2>
      <!-- Aquí mostrarías las butacas disponibles y permitirías al usuario seleccionarlas -->
      <!-- Por simplicidad, este componente solo muestra un mensaje -->
      <p>Componente de butacas aquí...</p>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      sessionId: {
        type: String,
        required: true
      }
    }
  };
  </script>
  
  <style scoped>
  /* Estilos CSS */
  </style>
  