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

// --- Gestion des Photos de terrain ---

let photoLayer = L.layerGroup();
let manualLocationMode = false;
let tempMarker = null;

window.ouvrirPhotoForm = function() {
    document.getElementById('photo-form-container').style.display = 'flex';
};

window.fermerPhotoForm = function() {
    document.getElementById('photo-form-container').style.display = 'none';
    resetPhotoForm();
};

function resetPhotoForm() {
    document.getElementById('add-photo-form').reset();
}

if (document.getElementById('map')) {
    const map = L.map('map');

    const osmLayer = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    });

    const googleSat = L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
        attribution: '© Google'
    });

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
            // Un petit hack pour forcer Leaflet à se rafraîchir
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

    // --- Initialisation des Photos ---
    photoLayer.addTo(map);
    layerControl.addOverlay(photoLayer, "Photos de terrain");

    function loadPhotos() {
        if (!idProjet) return;
        fetch(`actions/get_photos.php?projet=${idProjet}`)
            .then(res => res.json())
            .then(data => {
                photoLayer.clearLayers();
                L.geoJSON(data, {
                    pointToLayer: function(feature, latlng) {
                        const photoIcon = L.divIcon({
                            html: `<div style="background-image: url('${feature.properties.url}'); background-size: cover; width: 40px; height: 40px; border: 2px solid #000; box-shadow: 3px 3px 0px #d63384;"></div>`,
                            className: 'photo-marker-icon',
                            iconSize: [40, 40],
                            iconAnchor: [20, 20]
                        });
                        return L.marker(latlng, { icon: photoIcon });
                    },
                    onEachFeature: function(feature, layer) {
                        const popupContent = `
                            <div class="photo-popup" style="width: 250px;">
                                <a href="${feature.properties.url}" target="_blank" title="Cliquez pour agrandir">
                                    <img src="${feature.properties.url}" style="width: 100%; border: 1px solid #000; cursor: pointer;">
                                </a>
                                <p style="margin: 10px 0; font-size: 13px;">${feature.properties.description || 'Sans description'}</p>
                                <small style="display: block; color: #666; margin-bottom: 10px;">${new Date(feature.properties.date_photo).toLocaleDateString()}</small>
                                <button onclick="deletePhoto(${feature.properties.id})" style="font-size: 11px; color: #d63384; border: 1px solid #d63384; background: none; cursor: pointer; padding: 3px 8px; text-transform: uppercase;">Supprimer</button>
                            </div>
                        `;
                        layer.bindPopup(popupContent, { maxWidth: 300 });
                    }
                }).addTo(photoLayer);
            });
    }

    window.deletePhoto = function(id) {
        if (!confirm("Supprimer cette photo ?")) return;
        const formData = new FormData();
        formData.append('id', id);
        fetch('actions/delete_photo.php', {
            method: 'POST',
            body: formData
        }).then(() => loadPhotos());
    };

    loadPhotos();

    const photoForm = document.getElementById('add-photo-form');
    if (photoForm) {
        photoForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('actions/add_photo.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    fermerPhotoForm();
                    loadPhotos();
                } else {
                    alert("Erreur : " + data.error);
                }
            });
        });
    }

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

    copernicusLayers['Couleurs naturelles'].addTo(map);
}