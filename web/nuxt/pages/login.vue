<!-- 
Ruta de desplegament: https://tr3cine.a17danvicfer.daw.inspedralbes.cat/laravel/public
Ruta Local: http://localhost:8000
-->
<template>
  <div class="flex min-h-full flex-1 flex-col justify-center px-6 py-12 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
      <img class="mx-auto w-auto" src="/cineCar.jpg" alt="Your Company" />
      <h2
        class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900"
      >
        Inicia Sessió
      </h2>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
      <div class="space-y-6">
        <div>
          <label for="email" class="block text-sm font-medium leading-6 text-gray-900"
            >Correu electrònic</label
          >
          <div class="mt-2">
            <input
              v-model="email"
              id="email"
              name="email"
              type="email"
              autocomplete="email"
              required
              class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset"
            />
          </div>
        </div>

        <div>
          <div class="flex items-center justify-between">
            <label
              for="password"
              class="block text-sm font-medium leading-6 text-gray-900"
              >Contrasenya</label
            >
          </div>
          <div class="mt-2">
            <input
              v-model="password"
              id="password"
              name="password"
              type="password"
              autocomplete="current-password"
              required
              class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset"
            />
          </div>
        </div>

        <div>
          <button
            @click="login"
            class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
          >
            Inicia Sessio
          </button>
        </div>
      </div>

      <p class="mt-10 text-center text-sm text-gray-500">
        No ets membre?
        <a
          href="/registre"
          class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500"
          >Registrat</a
        >
      </p>
    </div>
  </div>
</template>

<script>
import { compraStore } from "../stores/compra.js";
export default {
  data() {
    return {
      ruta: 'http://tr3cine.a17danvicfer.daw.inspedralbes.cat/laravel/public',
      email: '',
      password: ''
    };
  },
  methods: {
    async login() {
      try {
        const response = await fetch(`${this.ruta}/api/login`, {
          method: 'POST',
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            email: this.email,
            password: this.password,
          }),
        });

        if (!response.ok) {
          throw new Error("Credenciales inválidas");
        }

        const data = await response.json();

        // Almacena el token en localStorage o Vuex
        localStorage.setItem("token", data.token);
        const store = compraStore();
        if (data.role == "admin") {
          store.isAdmin = true;
        } else {
          store.isAdmin = false;
          //console.log("No eres administrador");
        }
        store.idUser = data.user_id;
        store.isAuthenticated = true;
        //console.log("ID del usuario PINIA:", store.idUser);
        // Redirige al usuario a la página de inicio, por ejemplo
        //console.log("Login USUARIO correcto");
        if (store.isAdmin) {
          this.$router.push("/Admin/panelAdmin");
        } else {
          this.$router.push("/sesiones");
        }
      } catch (error) {
        console.error(error);
        // Manejar el error, por ejemplo, mostrar un mensaje al usuario
      }
    },
  },
};
</script>
