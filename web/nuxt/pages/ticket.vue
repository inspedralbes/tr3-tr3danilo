<template>
  <div></div>
</template>

<script>
export default {
  // Recibe las props enviadas desde la página anterior
  props: {
    datosCompra: {
      type: Object,
      required: true,
    },
  },
  // Aquí puedes manejar los datos de la compra recibidos en las props
  created() {
    console.log("Datos de la compra recibidos:", this.datosCompra);
    // Puedes realizar cualquier acción necesaria con los datos de la compra, como mostrarlos en la página
  },
};
</script>

<style lang="scss" scoped></style>
