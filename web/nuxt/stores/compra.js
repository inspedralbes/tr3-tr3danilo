export const compraStore = defineStore('compra', () => {
  const sessio = ref({ butacas: [] })
   
  function setSessio(valor) {
    sessio.value = { ...sessio.value, ...valor };
  }
  function setButacaSeleccionada(butaca) {
    // Aquí puedes realizar cualquier lógica adicional, como validaciones, antes de actualizar la butaca seleccionada
    sessio.value = { ...sessio.value, butacas: [...sessio.value.butacas, butaca] };
  }
  return { sessio, setSessio, setButacaSeleccionada };
})