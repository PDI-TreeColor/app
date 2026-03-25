// Récupérer l'ID du projet
const paramsString = window.location.search;
const searchParams = new URLSearchParams(paramsString);
const idProjet = searchParams.get("projet");

window.ouvrirForm = function () {
    const form = document.getElementById("formulaire");
    if (form) form.style.display = "block";
};

window.fermerForm = function () {
    const form = document.getElementById("formulaire");
    if (form) form.style.display = "none";
};

if (document.getElementById('map')) {
    const map = L.map('map');

    const osmLayer = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    });

    const googleSat = L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
        attribution: '© Google'
    })

    const layerControl = L.control.layers({
        '<b>Fonds de carte</b>': L.layerGroup(),
        'OpenStreetMaps': osmLayer,
        'Satellite (Google)': googleSat
    }, {}, { collapsed: false }).addTo(map);

    map.on('baselayerchange', function (e) {
        const isCopernicus = (
            e.name === 'Couleurs naturelles' ||
            e.name === 'Indice de végétation' ||
            e.name === 'Infrarouge colorisé'
        );

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

    fetch(`actions/get_project_data.php?projet=${idProjet}`)
        .then(response => response.json())
        .then(data => {
            const popupContent = document.createElement('span');
            popupContent.textContent = `${data.features[0].properties.surface} m²`;

            const style = {
                color: "#000000",
                weight: 2,
                opacity: 1,
                fillColor: "#d63384",
                fillOpacity: 0.2,
                dashArray: "0",
            };

            const geojsonLayer = L.geoJSON(data, { style }).addTo(map);
            layerControl.addOverlay(geojsonLayer, "Zone reforestée");
            geojsonLayer.bindPopup(popupContent);
            map.fitBounds(geojsonLayer.getBounds());
            setTimeout(() => { map.invalidateSize(); }, 200);
        })
        .catch(err => console.error("Erreur :", err));

    const URL_WMS_COPERNICUS = "https://sh.dataspace.copernicus.eu/ogc/wms/040a9e84-1617-4bf1-9b85-1e537e4fcb0d";

    const copernicusLayers = {
        'Couleurs naturelles': L.tileLayer.wms(URL_WMS_COPERNICUS, { layers: 'TRUE_COLOR' }),
        'Indice de végétation': L.tileLayer.wms(URL_WMS_COPERNICUS, { layers: 'VEGETATION_INDEX' }),
        'Infrarouge colorisé': L.tileLayer.wms(URL_WMS_COPERNICUS, { layers: 'COLOR_INFRARED' }),
    };

    layerControl.addBaseLayer(L.layerGroup(), '<b>Imagerie Temporelle</b>');
    Object.entries(copernicusLayers).forEach(([nom, layer]) => {
        layerControl.addBaseLayer(layer, nom);
    });

    function getSelectedDate() {
        const yearSelect = document.getElementById("year-select");
        const monthSelect = document.getElementById("month-select");
        if (yearSelect && monthSelect) {
            return `${yearSelect.value}-${monthSelect.value}`;
        }
        return new Date().toJSON().slice(0, 7);
    }

    function updateCopernicusLayers(date) {
        Object.values(copernicusLayers).forEach(layer => {
            layer.setParams({ TIME: date });
        });
    }

    updateCopernicusLayers(getSelectedDate());

    const calendar = document.getElementById("calendar");
    if (calendar) {
        calendar.addEventListener("change", () => {
            updateCopernicusLayers(getSelectedDate());
        });
    }

    googleSat.addTo(map);
}