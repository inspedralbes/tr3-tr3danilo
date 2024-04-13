<template>
  <header class="cine-header bg-white-100">
    <!-- Menú de navegación -->
    <nav
      class="cine-menu mx-auto flex max-w-7xl items-center justify-between p-6 lg:px-8"
      aria-label="Global"
    >
      <div class="flex lg:flex-1">
        <nuxt-link to="/" class="-m-1.5 p-1.5">
          <span class="sr-only">Your Company</span>
          <img class="h-12 w-auto" src="/Logo.jpg" alt="" />
        </nuxt-link>
      </div>
      <div class="flex lg:hidden">
        <button
          type="button"
          class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700"
          @click="mobileMenuOpen = true"
        >
          <span class="sr-only">Open main menu</span>
          <!-- Icono de menú para dispositivos móviles -->
        </button>
      </div>
      <!-- Elementos del menú principal -->
      <nav class="hidden lg:flex lg:gap-x-12">
        <ul class="flex space-x-4">
          <li><nuxt-link to="/">Inici</nuxt-link></li>
          <li><nuxt-link to="/peliculas">Cartellera</nuxt-link></li>
          <li><nuxt-link to="/sesiones">Sessions del dia</nuxt-link></li>
          <li><nuxt-link to="/entradas">Entrades</nuxt-link></li>
        </ul>
      </nav>
      <div class="hidden lg:flex lg:flex-1 lg:justify-end">
        <a
          v-if="store.isAuthenticated"
          @click="logoutAndReload"
          class="text-sm font-semibold leading-6 text-gray-900"
          >Logout <span aria-hidden="true">&rarr;</span></a
        >
        <a v-else href="/login" class="text-sm font-semibold leading-6 text-gray-900"
          >Log in <span aria-hidden="true">&rarr;</span></a
        >
      </div>
    </nav>
    <!-- Menú desplegable para dispositivos móviles -->
    <Dialog
      as="div"
      class="lg:hidden"
      @close="mobileMenuOpen = false"
      :open="mobileMenuOpen"
    >
      <div class="fixed inset-0 z-10" />
      <DialogPanel
        class="fixed inset-y-0 right-0 z-10 w-full overflow-y-auto bg-white px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10"
      >
        <div class="flex items-center justify-between">
          <nuxt-link to="/" class="-m-1.5 p-1.5">
            <span class="sr-only">Your Company</span>
            <img
              class="h-8 w-auto"
              src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600"
              alt=""
            />
          </nuxt-link>
          <button
            type="button"
            class="-m-2.5 rounded-md p-2.5 text-gray-700"
            @click="mobileMenuOpen = false"
          >
            <span class="sr-only">Close menu</span>
            <!-- Icono de cierre para menú desplegable móvil -->
          </button>
        </div>
        <div class="py-6">
          <a v-if="store.isAuthenticated" @click="logoutAndReload">Logout</a>
          <nuxt-link to="/login">Login</nuxt-link>
        </div>
      </DialogPanel>
    </Dialog>
  </header>
</template>

<script setup>
import { ref } from "vue";
import { Dialog, DialogPanel } from "@headlessui/vue";
import { compraStore } from "../stores/compra.js";

const mobileMenuOpen = ref(false);
const store = compraStore();

function logoutAndReload() {
  store.isAuthenticated = false;
  window.location.reload(); // Reload the page after logout
}
</script>
