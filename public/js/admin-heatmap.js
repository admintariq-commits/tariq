document.addEventListener('DOMContentLoaded', function () {
    const mapContainer = document.getElementById('map');
    if (!mapContainer || typeof L === 'undefined') {
        return;
    }

    const map = L.map('map').setView([-6.3690, 34.8888], 6);
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png').addTo(map);

    function getColor(rate) {
        if (rate >= 50) return '#dc2626';
        if (rate >= 30) return '#f97316';
        return '#10b981';
    }

    fetch('/geojson/tanzania.geojson')
        .then(function (response) {
            if (!response.ok) {
                throw new Error('GeoJSON file not found');
            }
            return response.json();
        })
        .then(function (data) {
            L.geoJSON(data, {
                style: function (feature) {
                    return {
                        fillColor: getColor(feature.properties.unemployment || 0),
                        weight: 2,
                        color: 'white',
                        fillOpacity: 0.7
                    };
                },
                onEachFeature: function (feature, layer) {
                    layer.bindPopup('<strong>' + feature.properties.NAME_1 + '</strong><br>Unemployment: ' + (feature.properties.unemployment || 0) + '%');
                }
            }).addTo(map);
        })
        .catch(function () {
            mapContainer.innerHTML = '<div class="bg-red-100 p-4 rounded text-center">⚠️ GeoJSON file not found. Please create geojson file.</div>';
        });
});
