<template>
    <div class="max-w-md mx-auto mt-8 p-4 bg-white rounded shadow-md">
        <h1 class="text-2xl font-bold mb-4">Seleccion de Pelicula</h1>
        <select v-model="selectedPelicula" class="w-full border rounded py-2 px-3 mb-4">
            <option value="">Selecciona una película</option>
            <option v-for="pelicula in pelicules" :key="pelicula.id" :value="pelicula.id">{{ pelicula.títol }}</option>
        </select>

        <h1 class="text-2xl font-bold mb-2">Fecha</h1>
        <input type="date" v-model="selectedDate" class="w-full border rounded py-2 px-3 mb-4">

        <h1 class="text-2xl font-bold mb-2">Hora</h1>
        <input type="time" v-model="selectedTime" class="w-full border rounded py-2 px-3 mb-4">

        <button @click="guardarDatos"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Guardar</button>
    </div>
</template>

<script>
export default {
    data() {
        return {
            pelicules: [],
            selectedPelicula: '',
            selectedDate: '',
            selectedTime: ''
        };
    },
    mounted() {
        this.datosPelicula();
    },
    methods: {
        datosPelicula() {
            fetch('http://localhost:8000/api/pelicules')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al obtener los datos de la API');
                    }
                    return response.json();
                })
                .then(data => {
                    if (Array.isArray(data)) {
                        this.pelicules = data;
                        console.log('Películas:', this.pelicules);
                    } else {
                        throw new Error('La respuesta de la API no tiene el formato esperado');
                    }
                })
                .catch(error => {
                    console.error('Error al obtener datos de la API:', error);
                });
        },
        guardarDatos() {
            const fecha = this.formatoFecha(this.selectedDate);
            const hora = this.formatoHora(this.selectedTime);
            const data = {
                pelicula_id: this.selectedPelicula,
                fecha: fecha,
                hora: hora
            };
            console.log('Datos a guardar:', data);
            fetch('http://localhost:8000/api/afegirSessio', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ data: data })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al guardar la sesión');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Sesión guardada:', data.session);
                    
                })
                .catch(error => {
                    console.error('Error al guardar la sesión:', error);
                });

        },
        formatoFecha(fecha) {
            const d = new Date(fecha);
            return `${d.getDate()}/${d.getMonth() + 1}/${d.getFullYear()}`;
        },
        formatoHora(hora) {
            return hora + ':00';
        }
    }
}
</script>

<style lang="scss" scoped></style>