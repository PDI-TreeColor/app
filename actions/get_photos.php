<?php
// actions/get_photos.php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';

$zone_id = $_GET['projet'] ?? null;
if (!$zone_id) {
    die(json_encode(['type' => 'FeatureCollection', 'features' => []]));
}

$db = getPdoConnection();

$sql = "SELECT jsonb_build_object(
    'type',     'FeatureCollection',
    'features', COALESCE(jsonb_agg(feature), '[]'::jsonb)
)
FROM (
  SELECT jsonb_build_object(
    'type',       'Feature',
    'geometry',   ST_AsGeoJSON(geom)::jsonb,
    'properties', jsonb_build_object(
        'id', id,
        'filename', filename,
        'description', description,
        'date_photo', date_photo,
        'url', 'data/' || zone_id || '/photos/' || filename
    )
  ) AS feature
  FROM public.photos 
  WHERE zone_id = :zone_id
) features";

$stmt = $db->prepare($sql);
$stmt->execute(['zone_id' => $zone_id]);
$result = $stmt->fetchColumn();

echo $result ? $result : json_encode(['type' => 'FeatureCollection', 'features' => []]);
