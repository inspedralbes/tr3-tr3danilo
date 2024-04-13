<!-- 
Ruta de desplegament: https://tr3cine.a17danvicfer.daw.inspedralbes.cat/laravel/public
Ruta Local: http://localhost:8000
-->

<template>
  <div>
    <div class="max-w-md mx-auto mt-8 p-4 bg-white rounded shadow-md">
      <h1 class="text-2xl font-bold mb-4">Selecció de Pelicula</h1>
      <select
        v-model="selectedPelicula"
        class="w-full border rounded py-2 px-3 mb-4 text-gray-700"
      >
        <option value="">Seleccioneu una pel·lícula</option>
        <option v-for="pelicula in pelicules" :key="pelicula.id" :value="pelicula.id">
          {{ pelicula.títol }}
        </option>
      </select>

      <h1 class="text-2xl font-bold mb-2">Data</h1>
      <input
        type="date"
        v-model="selectedDate"
        class="w-full border rounded py-2 px-3 mb-4 text-gray-700"
      />

      <h1 class="text-2xl font-bold mb-2">Hora</h1>
      <input
        type="time"
        v-model="selectedTime"
        class="w-full border rounded py-2 px-3 mb-4 text-gray-700"
      />

      <div
        v-if="sesioGuardada"
        class="text-center text-green-600 font-semibold p-2 bg-green-100 rounded"
      >
        Sesión guardada correctamente
      </div>

      <br />
      <button
        @click="guardarDatos"
        class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
      >
        Guardar
      </button>
    </div>

    <div class="mt-4 flex justify-center">
      <nuxt-link
        to="/Admin/panelAdmin"
        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
        >Tornar</nuxt-link
      >
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      ruta: "http://tr3cine.a17danvicfer.daw.inspedralbes.cat/laravel/public",
      pelicules: [],
      selectedPelicula: "",
      selectedDate: "",
      selectedTime: "",
      sesioGuardada: false,
    };
  },
  mounted() {
    this.datosPelicula();
  },
  methods: {
    datosPelicula() {
      fetch(`${this.ruta}/api/pelicules`)
        .then((response) => {
          if (!response.ok) {
            throw new Error("Error al obtener los datos de la API");
          }
          return response.json();
        })
        .then((data) => {
          if (Array.isArray(data)) {
            this.pelicules = data;
            //console.log("Películas:", this.pelicules);
          } else {
            throw new Error("La respuesta de la API no tiene el formato esperado");
          }
        })
        .catch((error) => {
          console.error("Error al obtener datos de la API:", error);
        });
    },
    guardarDatos() {
      const fecha = this.formatoFecha(this.selectedDate);
      const hora = this.formatoHora(this.selectedTime);
      const data = {
        pelicula_id: this.selectedPelicula,
        fecha: fecha,
        hora: hora,
      };
      //console.log("Datos a guardar:", data);
      fetch(`${this.ruta}/api/afegirSessio`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ data: data }),
      })
        .then((response) => {
          if (!response.ok) {
            throw new Error("Error al guardar la sesión");
          }
          return response.json();
        })
        .then((data) => {
          this.sesioGuardada = true;
          //console.log("Sesión guardada:", data.session);
        })
        .catch((error) => {
          console.error("Error al guardar la sesión:", error);
        });
    },
    formatoFecha(fecha) {
      const d = new Date(fecha);
      return `${d.getDate()}/${d.getMonth() + 1}/${d.getFullYear()}`;
    },
    formatoHora(hora) {
      return hora + ":00";
    },
  },
};
</script>

<style lang="scss" scoped></style>
