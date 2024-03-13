export const compraStore = defineStore('compra', () => {
  const sessio = ref({})
   
  function setSessio(valor) {
    sessio.value = valor;
  }

  return { sessio, setSessio };
})