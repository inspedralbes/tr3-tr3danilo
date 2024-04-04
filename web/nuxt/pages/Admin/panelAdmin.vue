<template>
    <div>
        <h1>Panel del Administrador</h1>
        <button @click="agregarSesion"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">
            Afegir Sessio
        </button>
        <button @click="esborrarSessio"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">
            Esborrar Sessio
        </button>
    </div>
</template>

<script>
export default {
    data() {
        return {
            
        };
    },
    methods: {
        agregarSesion() {
            this.$router.push('/Admin/afegirSessio');
        },
        esborrarSessio() {
            this.$router.push('/Admin/esborrarSessio');
        },
    },
}
</script>

<style lang="scss" scoped></style>