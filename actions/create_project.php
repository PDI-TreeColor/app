<?php
// actions/create_project.php
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter_zone'])) {
    $conn = getDbConnection();
    $nom = $_POST['projet'];

    // Récupération GeoJSON depuis Leaflet Draw
    $geometryJson = $_POST['geojson'];
    $geojsonArray = json_decode($geometryJson, true);

    $dossier = __DIR__ . '/../data/' . $nom . '/';
    if (!is_dir($dossier)) {
        mkdir($dossier, 0777, true);
    }

    // Sauvegarde en fichier GeoJSON
    file_put_contents($dossier . $nom . '.geojson', json_encode([
        'type' => 'FeatureCollection',
        'features' => [[
            'type' => 'Feature',
            'geometry' => $geojsonArray,
            'properties' => ['nom' => $nom]
        ]]
    ]));

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK && !empty($_FILES['image']['name'])) {
        $nomImage = $nom . '/' . basename($_FILES['image']['name']);
        $destination = $dossier . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $destination);
    } else {
        $nomImage = 'vignette_defaut.png';
    }

    $query = "
        INSERT INTO zones (nom, geom, image)
        VALUES (
            $1,
            ST_SetSRID(ST_GeomFromGeoJSON($2),4326),
            $3
        )
    ";

    pg_query_params($conn, $query, [$nom, $geometryJson, $nomImage]);

    header('Location: ../index.php');
    exit();
}
