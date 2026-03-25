// Récupérer l'ID du projet
const paramsString = window.location.search;
const searchParams = new URLSearchParams(paramsString);
const idProjet = searchParams.get("projet");

// Définir ces fonctions globalement
window.ouvrirForm = function () {
    const form = document.getElementById("formulaire");
    if (form) form.style.display = "block";
};

window.fermerForm = function () {
    const form = document.getElementById("formulaire");
    if (form) form.style.display = "none";
};

if (document.getElementById('map')) {
    // Création de la carte
    const map = L.map('map');

    // Ajout d'une couche OpenStreetMaps
    const osmLayer = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    });

    // Ajout de la couche Google Satellite
    const googleSat = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
        attribution: '© Google'
    });

    // Ajout d'un gestionnaire de couche
    const layerControl = L.control.layers({ 
        '<b>Fonds de carte</b>': L.layerGroup(), // Dummy layer for header
        'OpenStreetMaps': osmLayer,
        'Satellite (Google)': googleSat 
    }, {}, { collapsed: false }).addTo(map);

    // Suppression du bouton radio pour le header (hack CSS)
    map.on('overlayadd', function() { /* fallback */ }); 


    // Gestion de l'état du calendrier en fonction de la couche active
    map.on('baselayerchange', function(e) {
        let isCopernicus = (e.name === 'Couleurs naturelles' || e.name === 'Indice de végétation' || e.name === 'Infrarouge colorisé');
        
        const yearSelect = document.getElementById("year-select");
        const monthSelect = document.getElementById("month-select");
        const calendarDiv = document.getElementById("calendar");
        
        if (yearSelect) yearSelect.disabled = !isCopernicus;
        if (monthSelect) monthSelect.disabled = !isCopernicus;
        
        if (calendarDiv) {
            calendarDiv.style.opacity = isCopernicus ? '1' : '0.5';
            calendarDiv.style.pointerEvents = isCopernicus ? 'auto' : 'none';
        }
    });

    // Afficher la zone du projet
    fetch(`actions/get_project_data.php?projet=${idProjet}`)
        .then(response => response.json())
        .then(data => {
            let popupContent = document.createElement('span');
            popupContent.textContent = `${data.features[0].properties.surface} m²`;

            let style = {
                color: "#000000",    // Bordure Noire
                weight: 2,             // Bordure un peu plus épaisse
                opacity: 1,            // Bordure bien visible
                fillColor: "#d63384",  // Remplissage Rose
                fillOpacity: 0.2,      // Opacité du remplissage
                dashArray: "0",        // On retire l'effet pointillé pour plus de netteté
            }

            const geojsonLayer = L.geoJSON(data, { style: style }).addTo(map);
            layerControl.addOverlay(geojsonLayer, "Zone reforestée");
            geojsonLayer.bindPopup(popupContent);

            map.fitBounds(geojsonLayer.getBounds());
        })
        .catch(err => console.error("Erreur :", err));

    // Au chargement de la page, importer les couches Copernicus à la date d'aujourd'hui
    let urlWmsCopernicus = ""; // Initialisé plus tard
    let copernicusLayers = {
        'Couleurs naturelles': L.tileLayer.wms("", { // L'URL sera mise à jour juste après
            layers: 'TRUE_COLOR'
        }),

        'Indice de végétation': L.tileLayer.wms("", {
            layers: 'VEGETATION_INDEX'
        }),

        'Infrarouge colorisé': L.tileLayer.wms("", {
            layers: 'COLOR_INFRARED'
        })
    };

    layerControl.addBaseLayer(L.layerGroup(), '<b>Imagerie Temporelle</b>'); // Header

    Object.entries(copernicusLayers).forEach(([nom, layer]) => {
        layerControl.addBaseLayer(layer, nom)
    });

    // Activation de la couche "Google Satellite" par défaut
    googleSat.addTo(map);

    const URL_WMS_COPERNICUS = "https://sh.dataspace.copernicus.eu/ogc/wms/040a9e84-1617-4bf1-9b85-1e537e4fcb0d";

    function getSelectedDate() {
        let yearSelect = document.getElementById("year-select");
        let monthSelect = document.getElementById("month-select");
        if (yearSelect && monthSelect) {
            let selectedYear = yearSelect.value;
            let selectedMonth = monthSelect.value;
            return `${selectedYear}-${selectedMonth}`;
        }
        return new Date().toJSON().slice(0, 7);
    }

    function updateCopernicusLayers(date) {
        urlWmsCopernicus = `${URL_WMS_COPERNICUS}?TIME=${date}`;

        Object.entries(copernicusLayers).forEach(([nom, layer]) => {
            layer.setUrl(urlWmsCopernicus);
        })
    }

    // Initialisation
    let date = getSelectedDate();
    updateCopernicusLayers(date);

    // Comportement du calendrier à un changement de date
    const calendar = document.getElementById("calendar");
    if (calendar) {
        calendar.addEventListener("change", () => {
            updateCopernicusLayers(getSelectedDate());
        });
    }
}
