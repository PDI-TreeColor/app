<!-- 
    Page de visualisation.
    Affiche une carte interactive (Leaflet) et des contrôles temporels.
-->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projet TreeColor</title>
    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin=""/>
    <link rel="stylesheet" href="style_visualisation.css">
</head>
<body>
    <!-- Conteneur de la carte Leaflet -->
    <div id="map"></div>
    
    <!-- Sélecteurs de date pour filtrer les données -->
    <div id="calendar">
        <select name="year" id="year-select"></select>
        <select name="month" id="month-select"></select>
    </div>

    <div id="accueil">
        <a href="index.php"><button class="bouton_accueil">Retour à la page d'accueil</button></a>
    </div>
    
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>
    
    <script src="js/date-selection.js"></script>
    <script src="js/map.js"></script>
</body>
</html>