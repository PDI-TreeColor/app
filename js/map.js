// Zone concernée
const paramsString = window.location.search;
const searchParams = new URLSearchParams(paramsString);
const zone = searchParams.get("projet");

// Création de la carte
var map = L.map('map');

osmLayer = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
});

osmLayer.addTo(map);
var layerControl = L.control.layers({'OpenStreetMaps': osmLayer}).addTo(map);

// Plot zone Burkina

fetch(`data/${zone}/${zone}.geojson`)
    .then(response => response.json())
    .then(data => {
        var geojsonLayer = L.geoJSON(data, {
            style: {
                color: "red",
                weight: 2,
                fillOpacity: 0
            }
        }).addTo(map);

        layerControl.addOverlay(geojsonLayer, "Zone reforestée");

    map.fitBounds(geojsonLayer.getBounds());

  });


// Import des couches Copernicus

var date = new Date().toJSON().slice(0,10);
var today = date;
console.log(date);

var urlWmsCopernicus = `https://sh.dataspace.copernicus.eu/ogc/wms/040a9e84-1617-4bf1-9b85-1e537e4fcb0d?TIME=${date}`

var copernicusLayers = {
    'Couleurs naturelles': L.tileLayer.wms(urlWmsCopernicus, {
        layers: 'TRUE_COLOR'
    }),

    'Indice de végétation': L.tileLayer.wms(urlWmsCopernicus, {
        layers: 'VEGETATION_INDEX'
    })
}

Object.entries(copernicusLayers).forEach(([nom, layer]) => {
    layerControl.addBaseLayer(layer, nom)
});

// Comportement du calendrier

var calendrier = document.getElementById("calendrier")
calendrier.value = today;
calendrier.setAttribute("max", today);

function handler(e){
    date = e.target.value;
    urlWmsCopernicus = `https://sh.dataspace.copernicus.eu/ogc/wms/040a9e84-1617-4bf1-9b85-1e537e4fcb0d?TIME=${date}`

    Object.entries(copernicusLayers).forEach(([nom, layer]) => {
        layer.setUrl(urlWmsCopernicus);
});
}