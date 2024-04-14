<!-- 
Ruta de desplegament: https://tr3cine.a17danvicfer.daw.inspedralbes.cat/laravel/public
Ruta Local: http://localhost:8000
-->
<template>
  <div class="p-4">
    <h2 class="text-lg font-semibold mb-4">Selecciona tus butacas</h2>

    <!-- Pantalla -->
    <div class="bg-gray-900 text-white p-4 mb-4 text-center">
      <h1 class="text-3xl mb-2">PANTALLA</h1>
    </div>

    <!-- Butacas -->
    <div class="grid grid-cols-10 gap-1 justify-center max-w-4xl mx-auto">
      <img
        v-for="seat in availableSeats"
        :key="seat.id"
        :src="getSeatImage(seat)"
        @click="toggleSeatStatus(seat)"
        :id="'seat_' + seat.id"
        class="w-15 h-12 cursor-pointer"
        :class="{ 'bg-blue-500': seat.selected, 'bg-gray-300': !seat.selected }"
      />
    </div>

    <!-- Resumen -->
    <div class="mt-8 text-center">
      <div class="bg-gray-200 rounded-lg p-4 inline-block">
        <p class="mb-2">
          Total de butacas seleccionadas:
          <span class="font-semibold">{{ totalSelectedSeats }}</span>
        </p>
        <p class="mb-2">
          Total a pagar: <span class="font-semibold">{{ totalPrice }}€</span>
        </p>
        <div v-if="selectedSeats.length">
          <p class="mb-2">Butacas seleccionadas:</p>
          <ul>
            <li v-for="(seat, index) in selectedSeats" :key="index">
              Fila {{ seat.row }} Columna {{ seat.column }}
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { io } from "socket.io-client";
import { compraStore } from "../stores/compra.js";

export default {
  props: {
    sessionId: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      ruta: "http://tr3cine.a17danvicfer.daw.inspedralbes.cat/laravel/public",
      sessioPinia: null,
      availableSeats: Array.from({ length: 120 }, (_, index) => ({
        id: index + 1,
        row: Math.floor(index / 10) + 1,
        column: (index % 10) + 1,
        status: "available",
        precio: 6.5,
      })),
      selectedSeats: [],
      butacasOcupadas: [],
      vipRow: 6,
      vipPrice: 8,
      normalPrice: 6,
      socket: null,
    };
  },
  mounted() {
    let storeSesion = compraStore();
    //console.log("Sesion", storeSesion.sessio.id);
    this.socket = io(`http://tr3cine.a17danvicfer.daw.inspedralbes.cat:3102`); // Conecta con tu servidor Socket.IO
    if (storeSesion.sessio.id) {
      this.socket.emit("joinSession", storeSesion.sessio.id);
    }
    this.socket.on("userJoined", (userId) => {
      //console.log("Nuevo usuario conectado:", userId);
    });

    if (this.socket) {
      this.socket.on("seatSelected", (seat) => {
        //console.log(`Butaca seleccionada: ${JSON.stringify(seat)}`);
        const index = this.availableSeats.findIndex((s) => s.id === seat.id);
        if (index !== -1) {
          this.availableSeats[index].status = "selected";
        }
      });

      this.socket.on("seatDeselected", (seat) => {
        //console.log(`Butaca deseleccionada: ${JSON.stringify(seat)}`);
        const index = this.availableSeats.findIndex((s) => s.id === seat.id);
        if (index !== -1) {
          this.availableSeats[index].status = "available";
        }
      });
    } else {
      console.log("No hay socket");
    }
  },
  beforeDestroy() {
    if (this.socket) {
      this.socket.disconnect();
    }
  },
  computed: {
    totalSelectedSeats() {
      return this.selectedSeats.length;
    },
    totalPrice() {
      let total = 0;
      this.selectedSeats.forEach((seat) => {
        total += seat.row === this.vipRow ? this.vipPrice : this.normalPrice;
      });
      return total;
    },
  },
  methods: {
    toggleSeatStatus(seat) {
      if (seat.status === "available" || seat.status === "vip") {
        seat.status = "selected";
        this.selectedSeats.push(seat);
        this.socket.emit("seatSelected", seat);
      } else if (seat.status === "selected") {
        seat.status = "available";
        const index = this.selectedSeats.findIndex((s) => s.id === seat.id);
        if (index !== -1) {
          this.selectedSeats.splice(index, 1);
        }
        this.socket.emit("seatDeselected", seat);
      }
      this.emitSelectedSeats();
    },
    obtenerButacasOcupadas() {
      //console.log("SessioId: Butacas:", this.sessioPinia);
      fetch(`${this.ruta}/api/${this.sessioPinia}/ocupadas`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ sessionId: this.sessionId }),
      })
        .then((response) => {
          if (!response.ok) {
            throw new Error("No se pudo obtener las butacas ocupadas");
          }
          return response.json();
        })
        .then((data) => {
          this.butacasOcupadas = data;
          this.availableSeats.forEach((seat) => {
            const seatId = `${seat.row}-${seat.column}`;
            if (this.butacasOcupadas.includes(seatId)) {
              seat.status = "ocupado";
            }
          });
        })
        .catch((error) => {
          console.error("Error al obtener las butacas ocupadas:", error);
        });
    },
    emitSelectedSeats() {
      const selectedSeatsData = this.selectedSeats.map((seat) => ({
        row: seat.row,
        column: seat.column,
        precio: seat.row === this.vipRow ? this.vipPrice : this.normalPrice,
      }));
      //console.log("Butacas seleccionadas PaginaButacas:", selectedSeatsData);
      this.$emit("selectedSeatsUpdated", {
        sessionId: this.sessionId,
        seats: selectedSeatsData,
      });
    },
    getSeatImage(seat) {
      // Comprobar si la butaca está seleccionada
      if (seat.status === "selected") {
        // Devolver la imagen de la butaca seleccionada
        return "/reservado.svg";
      } else if (seat.status === "ocupado") {
        // Comprobar si la butaca está ocupada
        return "/ocupado.svg";
      } else if (seat.row === this.vipRow) {
        // Comprobar si la butaca pertenece a la fila 6
        // Devolver la imagen de la butaca VIP
        return "/vip.svg";
      } else {
        // Devolver la imagen según el estado de la butaca
        switch (seat.status) {
          case "selected":
            return "/reservado.svg";
          case "ocupado":
            return "/ocupado.svg";
          default:
            return "/disponible.svg";
        }
      }
    },
  },
  created() {
    let storeSesion = compraStore();
    this.sessioPinia = storeSesion.sessio.id;
    if (typeof this.sessioPinia !== "undefined") {
      this.obtenerButacasOcupadas();
    }
  },
};
</script>

<style scoped>
/* Estilos CSS específicos del componente */
.screen {
  border-top: 2px solid black;
  /* Línea simulando la pantalla */
  width: 100%;
  /* Ancho total */
  margin-bottom: 10px;
  /* Espacio entre la pantalla y las butacas */
}

.screen-title {
  text-align: center;
  font-size: 24px;
  margin-bottom: 10px;
}
</style>
