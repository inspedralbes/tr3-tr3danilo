<template>
    <div>
      <h1>Compra</h1>
      <p>Detalles de la sesión:</p>
      {{ sessioPinia.sessio.id }}

      <button>Seleccionar sesión</button>
    </div>
  </template>
  
  <script>
  import { defineComponent } from 'vue'
  import { compraStore } from '../stores/compra.js'
 
  

  export default {
  data() {
    return {
     
    };
  },
  created() {
    const sessioPinia = compraStore();
    console.log(sessioPinia)
    console.log()
    
  },
  methods: {
   
  }
};
  </script>

