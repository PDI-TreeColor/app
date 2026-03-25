<?php
// visualisation.php
$projet_id = $_GET['projet'] ?? null;
if (!$projet_id) {
    header('Location: index.php');
    exit();
}

// Affichage de la vue
require __DIR__ . '/views/carto.php';