const URL_WMS_COPERNICUS = "https://sh.dataspace.copernicus.eu/ogc/wms/040a9e84-1617-4bf1-9b85-1e537e4fcb0d";

// Récupérer l'ID du projet
const paramsString = window.location.search;
const searchParams = new URLSearchParams(paramsString);
const idProjet = searchParams.get("projet");


// Création de la carte
const map = L.map('map');

// Ajout d'une couche OpenStreetMaps
const osmLayer = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

// Ajout d'un gestionnaire de couche
const layerControl = L.control.layers({'OpenStreetMaps': osmLayer}, {}, {collapsed: false}).addTo(map);

// Ajout de l'outil de mesure (distances et surfaces)
const measureControl = L.control.measure({
    position: 'topright',
    primaryLengthUnit: 'meters', 
    secondaryLengthUnit: 'kilometers',
    primaryAreaUnit: 'sqmeters', 
    secondaryAreaUnit: 'hectares'
});
measureControl.addTo(map);

// Afficher la zone du projet
fetch(`read_db.php?projet=${idProjet}`)
    .then(response => response.json())
    .then(data => {
        let popupContent = document.createElement('span');
        popupContent.textContent = `${data.features[0].properties.surface} m²`;

        let style = {
            color: "#ff0000",    // Couleur de la bordure (rouge)
            weight: 1,             // Bordure fine (1px)
            opacity: 1,            // Bordure bien visible
            dashArray: "5, 5",     // Effet pointillé (5px trait, 5px vide)
        }

        const geojsonLayer = L.geoJSON(data, {style: style}).addTo(map);
        layerControl.addOverlay(geojsonLayer, "Zone reforestée");
        geojsonLayer.bindPopup(popupContent);

        map.fitBounds(geojsonLayer.getBounds());
    })
    .catch(err => console.error("Erreur :", err));

// Au chargement de la page, importer les couches Copernicus à la date d'aujourd'hui
function updateDateWmsUrl() {
    
}
let date = getSelectedDate();
let urlWmsCopernicus = `${URL_WMS_COPERNICUS}?TIME=${date}`;

let copernicusLayers = {
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

// Comportement du calendrier à un changement de date

function getSelectedDate() {
    let selectedYear = yearSelect.value;
    let selectedMonth = monthSelect.value;
    return `${selectedYear}-${selectedMonth}`;
}

function updateCopernicusLayers(date) {
    urlWmsCopernicus = `https://sh.dataspace.copernicus.eu/ogc/wms/040a9e84-1617-4bf1-9b85-1e537e4fcb0d?TIME=${date}`;

    Object.entries(copernicusLayers).forEach(([nom, layer]) => {
        layer.setUrl(urlWmsCopernicus);
    })
}

function ouvrirForm(){
    document.getElementById("formulaire").style.display = "block";
}

function fermerForm(){
    document.getElementById("formulaire").style.display = "none";
}

calendar.addEventListener("change", (event) => {
    var date = getSelectedDate();
    updateCopernicusLayers(date);
});
