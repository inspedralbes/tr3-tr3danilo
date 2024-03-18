export const compraStore = defineStore('compra', () => {
  //ALVARO
  //state:() => ({sessio: {} , butacas: [] }),
  const sessio = ref({ butacas: [] })
   
  function setSessio(valor) {
    sessio.value = { ...sessio.value, ...valor };
  }
  function getSessio() {
    return sessio.value;
  }

  function getButacasSeleccionadas() {
    return sessio.butacas;
  }
  function setButacaSeleccionada(butaca) {
    
    sessio.value = { ...sessio.value, butacas: [...sessio.value.butacas, butaca] };
  }
  return { sessio, setSessio, getSessio, setButacaSeleccionada, getButacasSeleccionadas };
})