<template>
    <div class="container">
        <h1>Mapa com Leaflet</h1>

        <!-- Uso básico -->
        <div class="section">
            <h2>Mapa Simples</h2>
            <GoogleMap :latitude="41.26484569108199" :longitude="-8.268448315691128" :zoom="15"
                title="Quinta Redolho de Cima, Lousada" height="500px" />
        </div>

        <!-- Com múltiplos marcadores -->
        <!-- <div class="section">
            <h2>Mapa com Múltiplos Marcadores</h2>
            <GoogleMap ref="mapWithMarkers" :latitude="-8.629" :longitude="-40.5095" :zoom="12"
                title="Localização Principal" height="500px" />
            <button @click="addMoreMarkers" class="btn">Adicionar Mais Marcadores</button>
        </div> -->

        <!-- Com zoom dinâmico -->
        <!-- <div class="section">
            <h2>Controle de Zoom</h2>
            <GoogleMap ref="mapWithZoom" :latitude="latitude" :longitude="longitude" :zoom="zoom"
                title="Localização Dinâmica" height="400px" />
            <div class="controls">
                <input v-model.number="latitude" type="number" step="0.001" placeholder="Latitude" class="input">
                <input v-model.number="longitude" type="number" step="0.001" placeholder="Longitude" class="input">
                <select v-model.number="zoom" class="select">
                    <option v-for="z in 20" :key="z" :value="z">Zoom: {{ z }}</option>
                </select>
            </div>
        </div> -->
    </div>
</template>

<script>
import GoogleMap from './GoogleMap.vue'

export default {
    components: {
        GoogleMap
    },
    data() {
        return {
            latitude: -8.629,
            longitude: -40.5095,
            zoom: 15
        }
    },
    methods: {
        addMoreMarkers() {
            const markers = [
                { lat: -8.62, lng: -40.50, title: 'Marcador 1' },
                { lat: -8.64, lng: -40.51, title: 'Marcador 2' },
                { lat: -8.63, lng: -40.52, title: 'Marcador 3' }
            ]

            markers.forEach(m => {
                this.$refs.mapWithMarkers.addMarkerAt(m.lat, m.lng, m.title)
            })
        }
    }
}
</script>

<style scoped>
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    font-family: system-ui, -apple-system, sans-serif;
}

h1 {
    color: #333;
    margin-bottom: 30px;
}

h2 {
    color: #555;
    margin-top: 30px;
    margin-bottom: 15px;
    font-size: 20px;
}

.section {
    margin-bottom: 40px;
}

.btn {
    margin-top: 15px;
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.3s;
}

.btn:hover {
    background-color: #0056b3;
}

.controls {
    display: flex;
    gap: 10px;
    margin-top: 15px;
    flex-wrap: wrap;
}

.input,
.select {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 14px;
}

.input:focus,
.select:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
}
</style>