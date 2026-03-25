<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>Accueil TreeColor</title>
    <link href="https://fonts.googleapis.com/css2?family=Belleza&display=swap" rel="stylesheet">
    <link href="styles/accueil.css" rel="stylesheet">
    <!-- Leaflet & Leaflet Draw -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
</head>

<body>
    <h1>Bienvenue sur l'application de suivi des projets TreeColor</h1>

    <div class="projets-container">
        <?php foreach ($projets as $projet): ?>
        <div class="projet">
            <h3>Projet
                <?php echo htmlspecialchars($projet['nom']); ?>
            </h3>
            <img src="data/<?php echo htmlspecialchars($projet['image']); ?>" class="card-img-top" alt="...">
            <div class="card-body">
                <form action="visualisation.php" method="GET">
                    <input type="hidden" name="projet" value="<?php echo htmlspecialchars($projet['id']); ?>">
                    <button class="carte-bouton">Voir le projet</button>
                </form>

                <form action="actions/delete_project.php" method="POST">
                    <button class="bouton-suppr" name="supprimer_zone"
                        value="<?php echo htmlspecialchars($projet['id']); ?>">Supprimer le projet</button>
                </form>
            </div>
        </div>
        <?php
endforeach; ?>
    </div>

    <div id="PLUS">
        <button onclick="ouvrirForm()">AJOUTER UN PROJET</button>
    </div>

    <div id="formulaire">
        <form action="actions/create_project.php" method="POST" enctype="multipart/form-data">
            <div class="form-layout">
                <div class="form-fields">
                    <label for="projet">Nom du projet :</label>
                    <input type="text" id="projet" name="projet" required><br>

                    <label for="image">Image de la carte (facultatif) :</label>
                    <input type="file" id="image" name="image" accept=".png,.jpg,.jpeg"><br><br>

                    <button type="submit" name="ajouter_zone">Valider</button>
                    <button type="button" onclick="fermerForm()">Fermer</button>
                </div>

                <div class="form-map">
                    <label>Dessiner la zone du projet :</label><br>
                    <div id="map-draw"></div>
                    <input type="hidden" id="geojson-input" name="geojson" required>
                    <small id="draw-help" style="color: red; display: none; margin-bottom: 10px;">Veuillez dessiner un
                        polygone sur la carte.</small><br>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>

    <!-- Leaflet JS & Draw JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

    <script src="js/map.js"></script>

    <script>
        let drawMap;
        let drawnItems;

        // Fonction pour initialiser la carte de dessin
        function initDrawMap() {
            if (drawMap) {
                drawMap.invalidateSize();
                return;
            }

            drawMap = L.map('map-draw').setView([0, 0], 2);

            const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            });

            const googleSat = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
                attribution: '© Google'
            });

            const baseMaps = {
                "Satellite (Google)": googleSat,
                "Plan (OSM)": osmLayer
            };

            googleSat.addTo(drawMap);
            L.control.layers(baseMaps).addTo(drawMap);

            drawnItems = new L.FeatureGroup();
            drawMap.addLayer(drawnItems);

            const drawControl = new L.Control.Draw({
                draw: {
                    polygon: true,
                    polyline: false,
                    rectangle: false,
                    circle: false,
                    marker: false,
                    circlemarker: false
                },
                edit: {
                    featureGroup: drawnItems,
                    remove: true
                }
            });
            drawMap.addControl(drawControl);

            drawMap.on(L.Draw.Event.CREATED, function (e) {
                var layer = e.layer;

                // On limite à un seul polygone
                drawnItems.clearLayers();
                drawnItems.addLayer(layer);

                // Mettre à jour l'input caché avec la géométrie GeoJSON
                var geojson = layer.toGeoJSON();
                document.getElementById('geojson-input').value = JSON.stringify(geojson.geometry);
                document.getElementById('draw-help').style.display = 'none';
            });

            drawMap.on(L.Draw.Event.DELETED, function (e) {
                document.getElementById('geojson-input').value = '';
            });
        }

        // Surcharge de ouvrirForm pour s'assurer que Leaflet s'affiche correctement
        const oldOuvrirForm = window.ouvrirForm;
        window.ouvrirForm = function () {
            if (oldOuvrirForm) oldOuvrirForm();
            setTimeout(initDrawMap, 100);
        };

        // Validation du formulaire avant envoi
        const formElt = document.querySelector('#formulaire form');
        if (formElt) {
            formElt.addEventListener('submit', function (e) {
                if (!document.getElementById('geojson-input').value) {
                    e.preventDefault();
                    document.getElementById('draw-help').style.display = 'block';
                }
            });
        }
    </script>
</body>

</html>