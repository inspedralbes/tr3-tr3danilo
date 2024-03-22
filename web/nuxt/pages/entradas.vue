<!-- 
Ruta de desplegament: http://tr3cine.a17danvicfer.daw.inspedralbes.cat/laravel/public
Ruta Local: http://localhost:8000
-->

<template>
  <div>
    <!-- Mostrar resumen de entradas si el usuario está autenticado -->
    <div v-if="datosCompra" class="mb-8">
      <h1 class="text-3xl font-semibold mb-2">Detalles de la compra</h1>
      <!-- Reducido el margen inferior -->

      <div class="bg-gray-100 rounded-lg p-6">
        <h2 class="text-xl font-bold mb-4">
          {{ datosCompra.sesion[0].pelicula_id }}
        </h2>

        <ul>
          <li v-for="(seatGroup, index) in datosCompra.butacas" :key="'seatGroup-' + index" class="mb-2">
            <ul>
              <li v-for="(seat, seatIndex) in seatGroup" :key="'seat-' + seatIndex">
                <span class="font-semibold">Butaca:</span> {{ seat.id }} -
                <span class="font-semibold">Precio:</span> {{ seat.precio }}€
              </li>
            </ul>
          </li>
        </ul>

        <p class="mt-4">
          <span class="font-semibold">Día:</span>
          {{ datosCompra.sesion[0].fecha }} -
          <span class="font-semibold">Hora:</span>
          {{ datosCompra.sesion[0].hora }}
        </p>
      </div>
    </div>

    <!-- Mostrar modal para solicitar correo electrónico si el usuario no está autenticado -->
    <div v-else>
      <div v-if="showModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <!-- Contenido del modal para ingresar el correo electrónico -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
          <input type="email" v-model="email" placeholder="Introduce tu correo electrónico"
            class="block w-full mb-4 px-3 py-2 border rounded-md">
          <button @click="obtenerEntradasPorCorreo"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Enviar</button>
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
      datosCompra: null, // Array para almacenar las entradas del usuario
      showModal: true, // Variable para controlar la visibilidad del modal
      email: '', // Variable para almacenar el correo electrónico introducido por el usuario
      error: '', // Variable para manejar errores de la solicitud
    };
  },
  methods: {
    async obtenerEntradasPorCorreo() {
      try {
        const response = await fetch(`${this.ruta}/api/obtenerEntradasPorCorreo`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            correo: this.email,
          }),
        });

        if (!response.ok) {
          throw new Error('Error al obtener las entradas');
        }

        const data = await response.json();
        console.log('Datos de las entradas:', data);
        // Actualizar las entradas en el componente con los datos obtenidos del servidor
        this.datosCompra = {
          sesion: data.sesion,
          butacas: data.butacas
        };

        // Reiniciar el correo electrónico después de una solicitud exitosa
        this.email = '';

        // Limpiar cualquier error previo
        this.error = '';

        // Cerrar el modal después de obtener las entradas exitosamente
        this.showModal = false;
      } catch (error) {
        console.error(error);
        // Manejar el error, por ejemplo, mostrar un mensaje al usuario
        this.error = 'Error al obtener las entradas. Por favor, inténtelo de nuevo más tarde.';
      }
    }
  }
}
</script>

<style>
/* Aquí puedes agregar estilos adicionales si es necesario */
</style>