<?php
// actions/create_project.php
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter_zone'])) {
    $conn = getDbConnection();
    $nom = $_POST['projet'];

    // Récupération GeoJSON depuis Leaflet Draw
    $geometryJson = $_POST['geojson'];

    $nomImage = 'vignette_defaut.png';

    // On insère d'abord en BDD pour récupérer l'ID unique
    $query = "
        INSERT INTO zones (nom, geom, image)
        VALUES (
            $1,
            ST_SetSRID(ST_GeomFromGeoJSON($2),4326),
            $3
        ) RETURNING id
    ";

    $result = pg_query_params($conn, $query, [$nom, $geometryJson, $nomImage]);
    $row = pg_fetch_assoc($result);
    $newId = $row['id'];

    // S'il y a une image d'uploadée, on l'enregistre dans data/{id}/ et on met à jour la base
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK && !empty($_FILES['image']['name'])) {
        $dossier = __DIR__ . '/../data/' . $newId . '/';
        if (!is_dir($dossier)) {
            mkdir($dossier, 0777, true);
        }
        $nomImageOrig = basename($_FILES['image']['name']);
        $destination = $dossier . $nomImageOrig;
        move_uploaded_file($_FILES['image']['tmp_name'], $destination);
        
        $imagePathDb = $newId . '/' . $nomImageOrig;
        $updateQuery = "UPDATE zones SET image = $1 WHERE id = $2";
        pg_query_params($conn, $updateQuery, [$imagePathDb, $newId]);
    }

    header('Location: ../index.php');
    exit();
}
