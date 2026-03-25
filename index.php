<?php
// index.php
require_once __DIR__ . '/config/database.php';

// Récupération des projets pour la vue
$conn = getDbConnection();
$result = pg_query($conn, "SELECT id, nom, image FROM zones");
$projets = [];

while ($zone = pg_fetch_assoc($result)) {
    $projets[] = $zone;
}

// Affichage de la vue
require __DIR__ . '/views/home.php';