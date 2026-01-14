<template>
    <div class="map-wrapper">
        <div ref="mapContainer" class="map-container"></div>
    </div>
</template>

<script>
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'

export default {
    name: 'GoogleMap',
    props: {
        latitude: {
            type: Number,
            default: -8.629
        },
        longitude: {
            type: Number,
            default: -40.5095
        },
        zoom: {
            type: Number,
            default: 15
        },
        title: {
            type: String,
            default: 'Minha Localização'
        },
        height: {
            type: String,
            default: '400px'
        }
    },
    data() {
        return {
            map: null,
            marker: null
        }
    },
    mounted() {
        this.initMap()
    },
    watch: {
        latitude(newVal) {
            this.updateMarker(newVal, this.longitude)
        },
        longitude(newVal) {
            this.updateMarker(this.latitude, newVal)
        }
    },
    methods: {
        initMap() {
            // Criar mapa
            this.map = L.map(this.$refs.mapContainer).setView(
                [this.latitude, this.longitude],
                this.zoom
            )

            // Adicionar tiles do OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19
            }).addTo(this.map)

            // Adicionar marcador
            this.addMarker(this.latitude, this.longitude, this.title)
        },
        addMarker(lat, lng, title) {
            this.marker = L.marker([lat, lng]).addTo(this.map)
            this.marker.bindPopup(`<strong>${title}</strong>`).openPopup()
        },
        updateMarker(lat, lng) {
            if (this.marker) {
                this.marker.setLatLng([lat, lng])
                this.map.setView([lat, lng], this.zoom)
            }
        },
        // Métodos úteis
        addMarkerAt(lat, lng, title = 'Marcador') {
            L.marker([lat, lng]).addTo(this.map)
                .bindPopup(`<strong>${title}</strong>`)
        },
        centerMap(lat, lng) {
            this.map.setView([lat, lng], this.zoom)
        },
        setZoom(zoomLevel) {
            this.map.setZoom(zoomLevel)
        }
    }
}
</script>

<style scoped>
.map-wrapper {
    width: 100%;
}

.map-container {
    width: 100%;
    height: v-bind(height);
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

:deep(.leaflet-popup-content-wrapper) {
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

:deep(.leaflet-popup-tip) {
    background-color: white;
}
</style>