<template>
    <div>
      <!-- Mostrar resumen de entradas si el usuario está autenticado -->
      <div v-if="isLoggedIn" class="mb-8">
        <h1 class="text-3xl font-semibold mb-2">Detalles de la compra</h1>
        <!-- Reducido el margen inferior -->

        <div class="bg-gray-100 rounded-lg p-6">
          <h2 class="text-xl font-bold mb-4">
            {{ datosCompra.datosSesion.pelicula.titulo }}
          </h2>

          <img
            :src="datosCompra.datosSesion.pelicula.imagen"
            class="w-56 rounded-lg mb-4"
            alt="Imagen de la película"
          />

          <ul>
            <li v-for="(seat, index) in datosCompra.butacas" :key="index" class="mb-2">
              <span class="font-semibold">Butaca:</span> {{ seat.id }} -
              <span class="font-semibold">Precio:</span> {{ seat.precio }}€
            </li>
          </ul>

          <p class="mt-4">
            <span class="font-semibold">Día:</span>
            {{ datosCompra.datosSesion.sesion.fecha }} -
            <span class="font-semibold">Hora:</span>
            {{ datosCompra.datosSesion.sesion.hora }}
          </p>
        </div>
      </div>

      
      <!-- Mostrar modal para solicitar correo electrónico si el usuario no está autenticado -->
      <div v-else>
        <div v-if="showModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
          <!-- Contenido del modal para ingresar el correo electrónico -->
          <div class="bg-white p-6 rounded-lg shadow-lg">
            <input type="email" v-model="email" placeholder="Introdueix el teu correu electrònic" class="block w-full mb-4 px-3 py-2 border rounded-md">
            <button @click="obtenerEntradasPorCorreo" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Enviar</button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        isLoggedIn: false, // Variable para indicar si el usuario está autenticado
        datosCompra: null, // Array para almacenar las entradas del usuario
        showModal: false, // Variable para controlar la visibilidad del modal
        email: '', // Variable para almacenar el correo electrónico introducido por el usuario
        error: '', // Variable para manejar errores de la solicitud
      };
    },
    created() {
      // Realizar comprobación de autenticación al cargar el componente
      this.comprobarAutenticacion();
    },
    methods: {
      async comprobarAutenticacion() {
        // Aquí deberías realizar la lógica para verificar si el usuario está autenticado.
        // Puedes usar localStorage, Vuex u otro método para verificar el estado de autenticación.
        // Por ahora, simplemente estableceremos isLoggedIn a true si hay un token en localStorage.
        const token = localStorage.getItem('token');
        this.isLoggedIn = token ? true : false;
  
        // Si el usuario está autenticado, obtenemos las entradas directamente
        if (this.isLoggedIn) {
          await this.obtenerEntradas();
        } else {
          // Si no está autenticado, mostramos el modal
          this.showModal = true;
        }
      },
    async obtenerEntradas() {
        try {
            const response = await fetch('http://localhost:8000/api/obtenerEntradasDeUsuario', {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                },
            });

            if (!response.ok) {
                throw new Error('Error al obtener las entradas');
            }

            const data = await response.json();
            console.log('Datos de las entradas:', data);
            // Actualizar las entradas en el componente con los datos obtenidos del servidor
            this.datosCompra = data;

            // Limpiar cualquier error previo
            this.error = '';
        } catch (error) {
            console.error(error);
            // Manejar el error, por ejemplo, mostrar un mensaje al usuario
            this.error = 'Error al obtener las entradas. Por favor, inténtelo de nuevo más tarde.';
        }
    },
      async obtenerEntradasPorCorreo() {
        try {
          const response = await fetch('http://localhost:8000/api/obtenerEntradasPorCorreo', {
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
          this.entries = data.sesion;
    
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
  