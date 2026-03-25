<?php
// actions/delete_photo.php
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = getPdoConnection();
    $id = $_POST['id'] ?? null;

    if (!$id) {
        die(json_encode(['error' => 'ID manquant']));
    }

    // Récupérer le nom du fichier pour le supprimer physiquement
    $stmt = $db->prepare("SELECT zone_id, filename FROM public.photos WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $photo = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($photo) {
        $filepath = __DIR__ . '/../data/' . $photo['zone_id'] . '/photos/' . $photo['filename'];
        if (file_exists($filepath)) {
            unlink($filepath);
        }

        $stmt = $db->prepare("DELETE FROM public.photos WHERE id = :id");
        $stmt->execute(['id' => $id]);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Photo non trouvée']);
    }
}
