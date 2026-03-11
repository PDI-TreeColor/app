<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['projet'];

    // 1. Ici, vous faites vos traitements (Base de données, validation, etc.)
    
    // 2. Redirection vers la page de visualisation avec un paramètre
    header("Location: visualisation.php?status=success&nom=" . urlencode($nom));
    
    // 3. Toujours quitter le script après un header pour stopper l'exécution
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test de l'API Copernicus</title>
    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin=""/>
    <style>
        #map {
            height: 100em;
            z-index: 0;
        }

        #calendrier {
            position: absolute;
            top: 100px;
            left: 20px;
            z-index: 1;
        }


    </style>
</head>
<body>
    <div id="map"></div>
    <input type="date" id="calendrier" onchange="handler(event);"/>
    
    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>
    
    <script src="js/map.js"></script>
</body>
</html>