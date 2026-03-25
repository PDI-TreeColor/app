<?php
// actions/add_photo.php
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = getPdoConnection();
    $zone_id = $_POST['zone_id'] ?? null;
    $description = $_POST['description'] ?? '';
    $lat = $_POST['lat'] ?? null;
    $lng = $_POST['lng'] ?? null;

    if (!$zone_id || !isset($_FILES['photo'])) {
        die(json_encode(['error' => 'Données manquantes']));
    }

    $photo = $_FILES['photo'];
    if ($photo['error'] !== UPLOAD_ERR_OK) {
        die(json_encode(['error' => 'Erreur lors de l’upload']));
    }

    // Extraction EXIF obligatoire
    if (function_exists('exif_read_data')) {
        $exif = @exif_read_data($photo['tmp_name']);
        if ($exif && isset($exif['GPSLatitude'], $exif['GPSLongitude'])) {
            // Fonction helper pour convertir le format EXIF (Degrés, Minutes, Secondes) en décimal
            function getGps($exifCoord, $hemi) {
                $degrees = count($exifCoord) > 0 ? gps2Num($exifCoord[0]) : 0;
                $minutes = count($exifCoord) > 1 ? gps2Num($exifCoord[1]) : 0;
                $seconds = count($exifCoord) > 2 ? gps2Num($exifCoord[2]) : 0;
                $flip = ($hemi == 'W' or $hemi == 'S') ? -1 : 1;
                return $flip * ($degrees + ($minutes / 60) + ($seconds / 3600));
            }
            function gps2Num($coordPart) {
                $parts = explode('/', $coordPart);
                if (count($parts) <= 0) return 0;
                if (count($parts) == 1) return $parts[0];
                return $parts[0] / $parts[1];
            }

            $lat = getGps($exif['GPSLatitude'], $exif['GPSLatitudeRef']);
            $lng = getGps($exif['GPSLongitude'], $exif['GPSLongitudeRef']);
        } else {
            die(json_encode(['error' => 'Cette image n’est pas géo-référencée. Impossible de l’ajouter.']));
        }
    } else {
        die(json_encode(['error' => 'Le module EXIF n’est pas activé sur le serveur.']));
    }

    // Stockage physique
    $dossier = __DIR__ . '/../data/' . $zone_id . '/photos/';
    if (!is_dir($dossier)) {
        mkdir($dossier, 0777, true);
    }

    $extension = pathinfo($photo['name'], PATHINFO_EXTENSION);
    $filename = uniqid('img_') . '.' . $extension;
    $destination = $dossier . $filename;
    move_uploaded_file($photo['tmp_name'], $destination);

    // Insertion BDD
    $sql = "INSERT INTO public.photos (zone_id, filename, geom, description) 
            VALUES (:zone_id, :filename, ST_SetSRID(ST_MakePoint(:lng, :lat), 4326), :description)";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([
        'zone_id' => $zone_id,
        'filename' => $filename,
        'lng' => $lng,
        'lat' => $lat,
        'description' => $description
    ]);

    echo json_encode(['success' => true, 'filename' => $filename, 'lat' => $lat, 'lng' => $lng]);
}
