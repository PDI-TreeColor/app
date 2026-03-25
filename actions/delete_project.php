<?php
// actions/delete_project.php
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['supprimer_zone'])) {
    $conn = getDbConnection();
    $id = $_POST['supprimer_zone'];

    // Nettoyage de l'image stockée dans le dossier /data/{id}/
    $dossier = __DIR__ . '/../data/' . $id . '/';
    if (is_dir($dossier)) {
        $files = glob($dossier . '*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        rmdir($dossier);
    }

    $query = "DELETE FROM zones WHERE id = $1";
    pg_query_params($conn, $query, [$id]);

    header('Location: ../index.php');
    exit();
}