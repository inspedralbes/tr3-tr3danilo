<template>
  <div>
    <div v-if="datosCompra" class="mb-8">
      <h1 class="text-2xl font-bold mb-4">Datos de la compra</h1>
      <ul>
        <li v-for="(seat, index) in datosCompra.seats" :key="index" class="mb-2">
          <span class="font-bold">ID:</span> {{ seat.id }} - <span class="font-bold">Precio:</span> {{ seat.price }} - <span class="font-bold">Estado:</span> {{ seat.status }}
        </li>
      </ul>
      <p class="mt-4"><span class="font-bold">Session ID:</span> {{ datosCompra.sessionId }}</p>
      <button @click="abrirModal" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">
        Enviar Correo
      </button>
    </div>
    <div v-else>
      <p class="text-gray-500">No hay datos de compra disponibles.</p>
    </div>

    <!-- Modal -->
    <div v-if="modalAbierto" class="fixed z-10 inset-0 overflow-y-auto">
      <div class="flex items-center justify-center min-h-screen">
        <div class="relative bg-white w-1/2 rounded-lg">
          <div class="p-8">
            <h2 class="text-xl font-bold mb-4">Enviar Correo Electrónico</h2>
            <input v-model="correoElectronico" type="email" placeholder="Correo Electrónico" class="border border-gray-300 px-3 py-2 rounded mb-4">
            <button @click="enviarCorreo" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
              Enviar
            </button>
          </div>
          <button @click="cerrarModal" class="absolute top-0 right-0 m-4 text-gray-500 hover:text-gray-700">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      datosCompra: null,
      modalAbierto: false,
      correoElectronico: ''
    };
  },
  mounted() {
  let storeSesion = compraStore();
  let ticket = storeSesion.getSessio();
  console.log("Datos del ticketPinia:", ticket)
  // Obtener los datos de la película y las butacas del ticket
  //let datosButacas = ticket.sessio.butacas;
  //let sesionID = ticket.sessio.id;
  console.log("Datos de las butacas:", storeSesion.getButacasSeleccionadas());

  fetch(`http://localhost:8000/api/sessions/${sesionID}`) // Cambiar la ruta si es necesario
      .then(response => {
        if (!response.ok) {
          throw new Error('Error al obtener los datos de la API');
        }
        return response.json();
      })
      .then(data => {
        console.log("Datos de la compra:", data.session);
        if (data && data.session) {
          this.datosCompra = {
            butacas: datosButacas,
            datosSesion: data.session
          };
        } else {
          throw new Error('La respuesta de la API no tiene el formato esperado');
        }
      })
      .catch(error => {
        console.error('Error al obtener datos de la API:', error);
      });
},

  methods: {
    abrirModal() {
      this.modalAbierto = true;
    },
    cerrarModal() {
      this.modalAbierto = false;
      // Limpia el campo de correo electrónico al cerrar el modal
      this.correoElectronico = '';
    },
    enviarCorreo() {

      // Aquí puedes realizar la lógica para enviar el correo electrónico con los datos de la compra
      // A través de un fetch u otra forma de comunicación con tu servidor
      // Una vez que se envíe el correo, cierra el modal
      this.cerrarModal();
    }
  }
};
</script>

<style>
/* Estilos para el modal */
.fixed {
  position: fixed;
}
.inset-0 {
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
}
.overflow-y-auto {
  overflow-y: auto;
}
.flex {
  display: flex;
}
.items-center {
  align-items: center;
}
.justify-center {
  justify-content: center;
}
.min-h-screen {
  min-height: 100vh;
}
.relative {
  position: relative;
}
.bg-white {
  background-color: #fff;
}

.rounded-lg {
  border-radius: 0.5rem;
}
.p-8 {
  padding: 2rem;
}
.text-xl {
  font-size: 1.25rem;
}
.font-bold {
  font-weight: 700;
}
.mb-4 {
  margin-bottom: 1rem;
}
.border {
  border-width: 1px;
}
.border-gray-300 {
  border-color: #e2e8f0;
}
.px-3 {
  padding-left: 0.75rem;
  padding-right: 0.75rem;
}
.py-2 {
  padding-top: 0.5rem;
  padding-bottom: 0.5rem;
}
.rounded {
  border-radius: 0.25rem;
}
.mb-4 {
  margin-bottom: 1rem;
}
.bg-blue-500 {
  background-color: #4299e1;
}
.hover\:bg-blue-700:hover {
  background-color: #2b6cb0;
}
.text-white {
  color: #fff;
}
.font-bold {
  font-weight: 700;
}
.py-2 {
  padding-top: 0.5rem;
  padding-bottom: 0.5rem;
}
.px-4 {
  padding-left: 1rem;
  padding-right: 1rem;
}
.mb-4 {
  margin-bottom: 1rem;
}
.hover\:text-gray-700:hover {
  color: #4a5568;
}
.absolute {
  position: absolute;
}
.top-0 {
  top: 0;
}
.right-0 {
  right: 0;
}
.m-4 {
  margin: 1rem;
}
.text-gray-500 {
  color: #718096;
}
.hover\:text-gray-700:hover {
  color: #4a5568;
}
.h-6 {
  height: 1.5rem;
}
.w-6 {
  width: 1.5rem;
}
.fill-current {
  fill: currentColor;
}
.stroke-current {
  stroke: currentColor;
}
</style>
