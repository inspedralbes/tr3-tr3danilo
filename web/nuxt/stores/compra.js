export const compraStore = defineStore('compra', () => {
  const sessio = ref({ butacas: [] })
   
  function setSessio(valor) {
    sessio.value = { ...sessio.value, ...valor };
  }
  function getSessio() {
    return sessio.value;
  }
  function setButacaSeleccionada(butaca) {
    
    sessio.value = { ...sessio.value, butacas: [...sessio.value.butacas, butaca] };
  }
  return { sessio, setSessio, getSessio, setButacaSeleccionada };
})