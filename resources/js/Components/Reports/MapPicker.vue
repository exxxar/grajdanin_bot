<template>
    <div class="map-container">


        <div class="input-group mb-2">

            <div
                class="form-floating">
                <input type="text"
                       v-model="searchQuery"
                       @keyup.enter="searchAddress"
                       class="form-control" id="deliveryForm-city"
                       placeholder="Ваш город">
                <label for="deliveryForm-city">Адрес</label>
            </div>

            <button
                type="button"
                class="btn btn-primary" @click="searchAddress">Найти
            </button>
        </div>

        <div class="my-2 small" v-if="findAddress">
            <strong>Адрес:</strong> {{ findAddress }} ({{ coords.lat }}, {{ coords.lng }})
        </div>

        <div ref="map" class="map"></div>


    </div>
</template>

<script>
import maplibregl from "maplibre-gl";
import "maplibre-gl/dist/maplibre-gl.css";

export default {
    name: "MapPickerVector",

    props: {
        ruNames: {
            type: Array,
            default: () => []
        },

        address: {
            type: String,
            default: ""
        },

    },
    emits: ["update:address"],
    watch: {
        'searchQuery': {
            handler: function (newValue) {
                localStorage.setItem("map_tile_search_query", this.searchQuery)
            },
            deep: true
        },
        'findAddress': {
            handler: function (newValue) {
                this.$emit("update:address", this.findAddress || this.searchQuery);
                this.$emit("update:lng", this.coords.lng);
                this.$emit("update:lat", this.coords.lat);


                localStorage.setItem("map_tile_search_query",  this.findAddress)

                window.dispatchEvent(new CustomEvent('change-address', {
                    detail: {
                        city: this.city || "",
                        borough: this.borough || "",
                        house_number: this.house_number || "",
                        road: this.road || "",

                        address: this.findAddress || this.searchQuery,
                        lng: this.coords.lng,
                        lat: this.coords.lat,
                    }
                }));
            },
            deep: true
        },
    },
    computed: {

        shopCoordsParsed() {
            const latDef =  import.meta.env.VITE_MAP_DEFAULT_LAT || 0
            const lonDef =  import.meta.env.VITE_MAT_DEFAULT_LON || 0

            const lng= parseFloat(lonDef)
            const lat = parseFloat(latDef)

            return {
                lat,
                lng
            }
        }
    },
    data() {
        return {
            map: null,
            marker: null,
            searchQuery: "",
            coords: {lat: null, lng: null},
            findAddress: "",
            city: "",
            borough: "",
            house_number: "",
            road: "",


        };
    },

    mounted() {
        this.initMap();

        this.searchQuery = localStorage.getItem("map_tile_search_query") != null ?
            localStorage.getItem("map_tile_search_query") : null

    },

    methods: {
        initMap() {


            const mapKey = import.meta.env.VITE_MAP_TILER

            this.map = new maplibregl.Map({
                container: this.$refs.map,
                style: `https://api.maptiler.com/maps/streets/style.json?key=${mapKey}`,
                center: [this.shopCoordsParsed.lat || 0, this.shopCoordsParsed.lng || 0],
                zoom: 13
            });

            this.map.addControl(new maplibregl.NavigationControl());

            this.map.on("load", () => {
                this.applyRussianLabels();
            });

            this.map.on("click", (e) => {
                const {lng, lat} = e.lngLat;
                this.coords = {lat, lng};
                this.placeMarker(lng, lat);
                this.reverseGeocode(lat, lng);
            });
        },

        applyRussianLabels() {
            const layers = this.map.getStyle().layers;

            layers.forEach(layer => {
                if (layer.type === "symbol" && layer.layout && layer.layout["text-field"]) {
                    this.map.setLayoutProperty(layer.id, "text-field", [
                        "coalesce",
                        ["get", "name:ru"], // русский
                        ["get", "name"],    // fallback
                        ["get", "name:uk"],
                        ["get", "name:en"]
                    ]);
                }
            });
        },

        placeMarker(lng, lat) {
            if (this.marker) this.marker.remove();

            this.marker = new maplibregl.Marker({color: "red"})
                .setLngLat([lng, lat])
                .addTo(this.map);

        },

        async searchAddress() {
            if (!this.searchQuery.trim()) return;

            const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(
                this.searchQuery
            )}&addressdetails=1&limit=1`;

            const res = await fetch(url);
            const data = await res.json();

            if (!data.length) return;

            const {lat, lon} = data[0];

            this.map.flyTo({center: [lon, lat], zoom: 16});
            this.placeMarker(lon, lat);

            this.coords = {lat, lng: lon};
            this.findAddress = this.formatAddress(data[0].address);


            this.city = data[0].address.city
            this.borough = data[0].address.borough
            this.house_number = data[0].address.house_number
            this.road = data[0].address.road


        },

        async reverseGeocode(lat, lng) {
            const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1`;

            const res = await fetch(url);
            const data = await res.json();

            this.city = data.address.city
            this.borough = data.address.borough
            this.house_number = data.address.house_number
            this.road = data.address.road

            this.findAddress = this.formatAddress(data.address);
            this.searchQuery = this.findAddress
        },

        formatAddress(addr) {
            if (!addr) return "";

            const street = [addr.road, addr.house_number].filter(Boolean).join(", ");
            const city = addr.city || addr.town || addr.village || "";

            let full = [street, city].filter(Boolean).join(", ");

            // Подмена на русский через словарь
            this.ruNames.forEach(item => {
                full = full.replace(item.original, item.ru);
            });


            return full;
        },


    }
};
</script>

<style scoped>
.map {
    width: 100%;
    height: 450px;
    border-radius: 6px;
    overflow: hidden;
}
</style>
