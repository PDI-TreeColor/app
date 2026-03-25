<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projet TreeColor</title>
    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link href="https://fonts.googleapis.com/css2?family=Belleza&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/carto.css">
</head>

<body>
    <!-- Conteneur de la carte Leaflet -->
    <div id="map"></div>

    <!-- Sélecteurs de date pour filtrer les données -->
    <div id="calendar" class="date-picker-container">
        <div class="calendar-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
        </div>
        <div class="select-group">
            <select name="month" id="month-select" class="custom-select"></select>
            <select name="year" id="year-select" class="custom-select"></select>
        </div>
    </div>

    <div id="accueil">
        <a href="index.php"><button class="bouton_accueil">Retour à la page d'accueil</button></a>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script src="js/date-selection.js"></script>
    <script src="js/map.js"></script>
</body>

</html>