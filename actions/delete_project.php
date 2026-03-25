<?php
// actions/delete_project.php
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['supprimer_zone'])) {
    $conn = getDbConnection();
    $id = $_POST['supprimer_zone'];

    // Fonction helper pour tout supprimer dans un dossier
    function supprimerDossierRecursif($dir) {
        if (!file_exists($dir)) return true;
        if (!is_dir($dir)) return unlink($dir);
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') continue;
            if (!supprimerDossierRecursif($dir . DIRECTORY_SEPARATOR . $item)) return false;
        }
        return rmdir($dir);
    }

    $dossier = __DIR__ . '/../data/' . $id . '/';
    supprimerDossierRecursif($dossier);

    $query = "DELETE FROM zones WHERE id = $1";
    pg_query_params($conn, $query, [$id]);

    header('Location: ../index.php');
    exit();
}