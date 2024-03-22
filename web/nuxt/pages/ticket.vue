<!-- 
Ruta de desplegament: https://tr3cine.a17danvicfer.daw.inspedralbes.cat/laravel/public
Ruta Local: http://localhost:8000
-->
<template>
  <div class="flex justify-center h-screen">
    <div>
      <div v-if="datosCompra" class="mb-8">
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

          <button
            @click="abrirModal"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4 inline-block"
          >
            Enviar Correo
          </button>
        </div>
      </div>

      <div v-else>
        <p class="text-gray-500">No hay datos de compra disponibles.</p>
      </div>

      <!-- Modal -->
      <div
        v-if="modalAbierto"
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white w-96 rounded-lg p-8 relative">
          
          <h2 class="text-xl font-bold mb-4">Nombre</h2>
          <input
            v-model="datosUsuario.nombre"
            type="email"
            placeholder="Correo Electrónico"
            class="border border-gray-300 px-3 py-2 rounded mb-4 w-full"
          />
          <h2 class="text-xl font-bold mb-4">Apellido</h2>
          <input
            v-model="datosUsuario.apellido"
            type="email"
            placeholder="Correo Electrónico"
            class="border border-gray-300 px-3 py-2 rounded mb-4 w-full"
          />
          <h2 class="text-xl font-bold mb-4">Enviar Correo Electrónico</h2>
          <input
            v-model="datosUsuario.correoElectronico"
            type="email"
            placeholder="Correo Electrónico"
            class="border border-gray-300 px-3 py-2 rounded mb-4 w-full"
          />
          <button
            @click="ConfirmarCompra"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full"
          >
            Confirmar Compra
          </button>
          <button
            @click="cerrarModal"
            class="absolute top-2 right-2 text-gray-500 hover:text-gray-700"
          >
            <svg
              class="h-6 w-6"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              ></path>
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
      ruta: 'https://tr3cine.a17danvicfer.daw.inspedralbes.cat/laravel/public',
      datosCompra: null,
      modalAbierto: false,
      datosUsuario: {
        nombre: "",
        apellido: "",
        correoElectronico: "",
      }
    };
  },
  mounted() {
    let storeSesion = compraStore();
    let ticket = storeSesion.sessio;
    console.log("Datos del ticketPinia:", ticket);
    console.log("Datos de las butacas:", storeSesion.butacas);
    console.log("ID de la sesión:", ticket.id);
    fetch(`${this.ruta}/api/sessions/${ticket.id}`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Error al obtener los datos de la API");
        }
        return response.json();
      })
      .then((data) => {
        console.log("Datos de la compra:", data.session);
        if (data && data.session) {
          this.datosCompra = {
            butacas: storeSesion.butacas,
            datosSesion: data.session,
          };
        } else {
          throw new Error("La respuesta de la API no tiene el formato esperado");
        }
        console.log("Datos de la compra OFICIAL:", this.datosCompra);
      })
      .catch((error) => {
        console.error("Error al obtener datos de la API:", error);
      });
  },

  methods: {
    abrirModal() {
      this.modalAbierto = true;
    },
    cerrarModal() {
      this.modalAbierto = false;
      this.correoElectronico = "";
    },
    ConfirmarCompra() {
      let storeSesion = compraStore();
      let idUsuario = storeSesion.idUser;
      console.log("ID del usuario COMPRA:", idUsuario);
      const datosUsuario = {
        nombre: this.datosUsuario.nombre,
        apellido: this.datosUsuario.apellido,
        correoElectronico: this.datosUsuario.correoElectronico
    };
    const data = {
        seats: this.datosCompra.butacas.map((seat) => ({
            id: seat.id,
            price: seat.precio,
            status: seat.status,
        })),
        sessionId: this.datosCompra.datosSesion.sesion.id,
        id_user: idUsuario
    };  
    console.log("Datos de la compra LARAVEL:", data);
    console.log("Datos Usuario LARAVEL:", datosUsuario);

    const token = localStorage.getItem('token');

     fetch("http://localhost:8000/api/efectuarCompra", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "Authorization": `Bearer ${token}`
        },
        body: JSON.stringify(data),
      })
        .then((response) => response.json())
        .then((result) => {
          console.log("Compra realizada:", result);

        })
        .catch((error) => {
          // Handle any errors that occurred during the fetch request
          console.error(error);
        });


    // Realizar una solicitud POST al servidor
    fetch('http://localhost:8000/api/enviarCorreo', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ datosUsuario, data })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error al guardar los datos de compra');
        }
        console.log('Datos de compra guardados correctamente');
        // Aquí podrías agregar lógica adicional si es necesario, como cerrar el modal
        this.cerrarModal();
    })
    .catch(error => {
        console.error('Error al guardar los datos de compra:', error);
    });

      this.cerrarModal();
    },
  },
};
</script>
