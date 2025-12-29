<x-filament-widgets::widget>
    <x-filament::section>
        <div class="fi-section-header">
            <h3 class="font-bold text-lg text-gray-950 dark:text-white mb-4">
                Customer Distribution Map
            </h3>
        </div>

        {{-- PENAWAR TAILWIND (WAJIB ADA) --}}
        <style>
            /* Memaksa elemen peta agar tidak dirusak oleh style bawaan Tailwind */
            .leaflet-pane, .leaflet-tile, .leaflet-marker-icon, .leaflet-marker-shadow, .leaflet-tile-container, .leaflet-pane > svg, .leaflet-pane > canvas, .leaflet-zoom-box, .leaflet-image-layer, .leaflet-layer {
                max-width: none !important;
                max-height: none !important;
            }
        </style>

        <div 
            wire:ignore
            class="w-full rounded-xl overflow-hidden shadow-sm"
            style="min-height: 400px; position: relative; z-index: 1;"
            x-data="{
                map: null,
                initMap() {
                    if (!this.$refs.mapContainer) return;
                    
                    if (this.map) {
                        this.map.off();
                        this.map.remove();
                        this.map = null;
                    }

                    this.map = L.map(this.$refs.mapContainer, {
                        attributionControl: false,
                        zoomControl: false
                    }).setView([-2.5489, 118.0149], 5);

                    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                        maxZoom: 20,
                        subdomains: 'abcd'
                    }).addTo(this.map);

                    L.control.zoom({ position: 'bottomright' }).addTo(this.map);

                    const locations = {{ json_encode($locations) }};
                    
                    locations.forEach(loc => {
                        if (loc.lat && loc.lng) {
                            L.circleMarker([loc.lat, loc.lng], {
                                color: '#10b981',      
                                fillColor: '#10b981',  
                                fillOpacity: 0.8,
                                radius: 6,
                                weight: 2
                            }).addTo(this.map).bindPopup(loc.popup);
                        }
                    });

                    const resizeObserver = new ResizeObserver(() => {
                        this.map.invalidateSize();
                    });
                    resizeObserver.observe(this.$refs.mapContainer);
                }
            }"
            x-init="
                $nextTick(() => {
                    let checkInterval = setInterval(() => {
                        if (typeof L !== 'undefined') {
                            initMap();
                            clearInterval(checkInterval);
                        }
                    }, 100);
                });
            "
        >
            <div 
                x-ref="mapContainer" 
                class="w-full"
                style="height: 400px; width: 100%; background-color: #1a1a1a;"
            ></div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>